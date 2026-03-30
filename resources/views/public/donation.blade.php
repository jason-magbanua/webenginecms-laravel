@extends('layouts.app')
@section('title', 'Donate')

@section('content')
<div class="page-header"><h2>Support the Server</h2></div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Donate via PayPal</h3></div>
            <div class="panel-body">
                @if(!$paypalEmail)
                    <div class="alert alert-warning">Donations are not configured yet.</div>
                @else
                    <p>Support the server by making a donation. Your credits will be added automatically after payment.</p>
                    <p><strong>{{ number_format($conversionRate) }} credits per $1 {{ $currency }}</strong></p>

                    <form action="{{ $paypalAction }}" method="post">
                        <input type="hidden" name="cmd"           value="_xclick">
                        <input type="hidden" name="business"      value="{{ $paypalEmail }}">
                        <input type="hidden" name="item_name"     value="{{ $itemName }}">
                        <input type="hidden" name="item_number"   value="{{ md5(uniqid()) }}">
                        <input type="hidden" name="currency_code" value="{{ $currency }}">
                        <input type="hidden" name="no_shipping"   value="1">
                        <input type="hidden" name="shipping"      value="0.00">
                        <input type="hidden" name="tax"           value="0.00">
                        <input type="hidden" name="no_note"       value="1">
                        <input type="hidden" name="return"        value="{{ $returnUrl }}">
                        <input type="hidden" name="cancel_return" value="{{ $returnUrl }}">
                        <input type="hidden" name="notify_url"    value="{{ $notifyUrl }}">
                        <input type="hidden" name="custom"        value="{{ auth()->id() }}">

                        <div class="form-group">
                            <label>Amount (${{ $currency }})</label>
                            <div class="input-group" style="max-width:200px;">
                                <span class="input-group-addon">$</span>
                                <input type="number" name="amount" id="donation-amount" class="form-control"
                                       min="1" max="999" step="1" required placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>You will receive: <strong><span id="credits-preview">0</span> credits</strong></label>
                        </div>

                        <button type="submit" class="btn btn-primary">Donate via PayPal</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var rate = {{ (int) $conversionRate }};
        var input = document.getElementById('donation-amount');
        var preview = document.getElementById('credits-preview');
        if (input && preview) {
            input.addEventListener('input', function () {
                var amount = parseInt(this.value) || 0;
                preview.textContent = Math.floor(amount * rate).toLocaleString();
            });
        }
    })();
</script>
@endsection
