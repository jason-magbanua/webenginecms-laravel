@extends('layouts.admin')
@section('title', 'PayPal Transactions')

@section('content')
<h1 class="page-header">PayPal Transactions</h1>

<table class="table table-striped table-condensed">
    <thead><tr><th>Account</th><th>Amount</th><th>Credits</th><th>Status</th><th>PayPal ID</th><th>Date</th></tr></thead>
    <tbody>
        @forelse($transactions as $tx)
        <tr>
            <td>{{ $tx->transaction_account }}</td>
            <td>{{ $tx->transaction_amount }} {{ $tx->transaction_currency }}</td>
            <td>{{ number_format($tx->transaction_credits) }}</td>
            <td><span class="label label-{{ $tx->transaction_status === 'Completed' ? 'success' : 'warning' }}">{{ $tx->transaction_status }}</span></td>
            <td><small>{{ $tx->transaction_paypal_id }}</small></td>
            <td>{{ $tx->transaction_date?->format('Y-m-d H:i') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-muted text-center">No transactions.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $transactions->links() }}
@endsection
