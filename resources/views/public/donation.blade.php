@extends('layouts.app')
@section('title', 'Donate')

@section('content')
<div class="page-header"><h2>Support the Server</h2></div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Donation</h3></div>
            <div class="panel-body">
                <p>Support the server by making a donation. Your contribution helps us keep the server running and improving.</p>
                <a href="{{ route('donation') }}/paypal" class="btn btn-primary">
                    Donate via PayPal
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
