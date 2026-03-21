@extends('layouts.app')
@section('title', 'Reset Character')

@section('content')
<div class="page-header"><h2>Reset Character</h2></div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($characters->isEmpty())
    <div class="alert alert-warning">No characters found on this account.</div>
@else
<div class="panel panel-default">
    <div class="panel-body" style="padding:0;">
        <table class="table" style="margin:0;">
            <tr>
                <td></td>
                <td><strong>Character</strong></td>
                <td><strong>Level</strong></td>
                <td><strong>Zen</strong></td>
                <td><strong>Resets</strong></td>
                <td></td>
            </tr>
            @foreach($characters as $char)
            <tr>
                <form action="{{ route('usercp.reset') }}" method="POST">
                    @csrf
                    <input type="hidden" name="character" value="{{ $char->Name }}">
                    <td><span class="label label-default">{{ \App\Support\MuHelper::classCss($char->Class) }}</span></td>
                    <td>{{ $char->Name }}</td>
                    <td>{{ $char->cLevel }}</td>
                    <td>{{ number_format($char->Money) }}</td>
                    <td>{{ number_format($char->RESETS) }}</td>
                    <td><button type="submit" class="btn btn-primary btn-xs">Reset</button></td>
                </form>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<div class="text-center text-muted" style="margin-top:10px;">
    @if($settings['required_level'] >= 1)
        <p>Required Level: <strong>{{ $settings['required_level'] }}</strong></p>
    @endif
    @if($settings['zen_cost'] >= 1)
        <p>Zen Cost: <strong>{{ number_format($settings['zen_cost']) }}</strong></p>
    @endif
    @if($settings['credit_cost'] >= 1)
        <p>Credit Cost: <strong>{{ number_format($settings['credit_cost']) }}</strong></p>
    @endif
    @if($settings['maximum_resets'] >= 1)
        <p>Maximum Resets: <strong>{{ number_format($settings['maximum_resets']) }}</strong></p>
    @endif
    @if($settings['credit_reward'] >= 1)
        <p>Credit Reward: <strong>{{ number_format($settings['credit_reward']) }}</strong></p>
    @endif
</div>
@endif
@endsection
