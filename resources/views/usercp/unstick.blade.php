@extends('layouts.app')
@section('title', 'Unstick Character')

@section('content')
<div class="page-header"><h2>Unstick Character</h2></div>

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
                <td><strong>Zen</strong></td>
                <td></td>
            </tr>
            @foreach($characters as $char)
            <tr>
                <form action="{{ route('usercp.unstick') }}" method="POST">
                    @csrf
                    <input type="hidden" name="character" value="{{ $char->Name }}">
                    <td><span class="label label-default">{{ \App\Support\MuHelper::classCss($char->Class) }}</span></td>
                    <td>{{ $char->Name }}</td>
                    <td>{{ number_format($char->Money) }}</td>
                    <td><button type="submit" class="btn btn-primary btn-xs">Unstick</button></td>
                </form>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@if($zenCost > 0)
<div class="text-center text-muted" style="margin-top:10px;">
    <p>Zen Cost: <strong>{{ number_format($zenCost) }}</strong></p>
</div>
@endif
@endif
@endsection
