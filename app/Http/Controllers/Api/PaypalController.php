<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\WebEngine\Credit;
use App\Models\WebEngine\CreditLog;
use App\Models\WebEngine\PaypalTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaypalController extends Controller
{
    private const VERIFY_URI         = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    private const SANDBOX_VERIFY_URI = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    public function ipn(Request $request)
    {
        // PayPal requires HTTP 200 regardless of outcome.
        // We process everything and return 200 at the end.

        $cfg       = config('webengine.paypal');
        $sandbox   = (bool) $cfg['sandbox'];
        $rawPost   = $request->getContent();
        $post      = $request->post();

        try {
            if (!$this->verifyIpn($rawPost, $sandbox)) {
                Log::warning('PayPal IPN verification failed.', ['post' => $post]);
                return response('INVALID', 200);
            }

            // Guard: receiver email must match configured seller email
            if (strtolower($post['receiver_email'] ?? '') !== strtolower($cfg['email'])) {
                Log::warning('PayPal IPN receiver email mismatch.', ['post' => $post]);
                return response('EMAIL_MISMATCH', 200);
            }

            $txnId        = $post['txn_id'] ?? null;
            $txnType      = $post['txn_type'] ?? null;
            $paymentStatus= $post['payment_status'] ?? null;
            $amount       = (float) ($post['mc_gross'] ?? 0);
            $currency     = $post['mc_currency'] ?? '';
            $payerEmail   = $post['payer_email'] ?? '';
            $accountId    = $post['custom'] ?? null;   // we pass memb___id as 'custom'
            $itemNumber   = $post['item_number'] ?? null;

            // Validate account
            $account = Account::where('memb___id', $accountId)->first();
            if (!$account) {
                Log::warning('PayPal IPN: account not found.', ['custom' => $accountId]);
                return response('ACCOUNT_NOT_FOUND', 200);
            }

            if ($paymentStatus === 'Completed') {
                $credits = (int) floor($amount * (float) $cfg['conversion_rate']);

                DB::transaction(function () use ($account, $txnId, $amount, $currency, $payerEmail, $itemNumber, $credits) {
                    // Idempotency: skip duplicate IPN notifications
                    if (PaypalTransaction::where('transaction_paypal_id', $txnId)->exists()) {
                        return;
                    }

                    // Add credits (upsert)
                    Credit::updateOrCreate(
                        ['memb___id' => $account->memb___id],
                        ['credits'   => DB::raw("credits + {$credits}")]
                    );

                    // Credit log
                    CreditLog::create([
                        'log_account'     => $account->memb___id,
                        'log_credits'     => $credits,
                        'log_type'        => 'donation',
                        'log_date'        => now(),
                        'log_description' => "PayPal donation \${$amount} {$currency} — txn {$txnId}",
                    ]);

                    // Transaction record
                    PaypalTransaction::create([
                        'transaction_account'  => $account->memb___id,
                        'transaction_amount'   => $amount,
                        'transaction_currency' => $currency,
                        'transaction_status'   => 'Completed',
                        'transaction_paypal_id'=> $txnId,
                        'transaction_date'     => now(),
                        'transaction_credits'  => $credits,
                    ]);
                });

                Log::info("PayPal IPN: {$credits} credits added to [{$account->memb___id}].", compact('txnId'));

            } elseif (in_array($paymentStatus, ['Reversed', 'Refunded', 'Chargedback'])) {
                // Reverse: block account and update transaction status
                $account->update(['bloc_code' => 1]);

                PaypalTransaction::where('transaction_paypal_id', $itemNumber)
                    ->update(['transaction_status' => $paymentStatus]);

                Log::info("PayPal IPN: account [{$account->memb___id}] blocked due to {$paymentStatus}.", compact('txnId'));
            }

        } catch (\Throwable $e) {
            Log::error('PayPal IPN exception.', ['exception' => $e, 'post' => $post]);
        }

        return response('OK', 200);
    }

    private function verifyIpn(string $rawPost, bool $sandbox): bool
    {
        $verifyUri = $sandbox ? self::SANDBOX_VERIFY_URI : self::VERIFY_URI;
        $body      = 'cmd=_notify-validate&' . $rawPost;

        $response = Http::withHeaders([
            'User-Agent' => 'Laravel-PayPal-IPN',
            'Connection' => 'Close',
        ])->withBody($body, 'application/x-www-form-urlencoded')
          ->post($verifyUri);

        return $response->ok() && $response->body() === 'VERIFIED';
    }
}
