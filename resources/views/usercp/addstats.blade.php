@extends('layouts.app')
@section('title', 'Add Stats')

@section('content')
<div class="page-header"><h2>Add Stats</h2></div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($characters->isEmpty())
    <div class="alert alert-warning">No characters found on this account.</div>
@else
    @foreach($characters as $char)
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2 text-center">
                    <p class="text-muted small">{{ \App\Support\MuHelper::className($char->Class) }}</p>
                    <p><strong>{{ $char->Name }}</strong></p>
                </div>
                <div class="col-sm-10">
                    <form class="form-horizontal" action="{{ route('usercp.addstats') }}" method="POST">
                        @csrf
                        <input type="hidden" name="character" value="{{ $char->Name }}">
                        <div class="form-group">
                            <label class="col-sm-5 control-label"></label>
                            <div class="col-sm-7">
                                <span class="text-muted">Available Points: <strong>{{ number_format($char->LevelUpPoint) }}</strong></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Strength ({{ $char->Strength }})</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="add_str" min="0" max="{{ $maxStats }}" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Agility ({{ $char->Dexterity }})</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="add_agi" min="0" max="{{ $maxStats }}" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Vitality ({{ $char->Vitality }})</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="add_vit" min="0" max="{{ $maxStats }}" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Energy ({{ $char->Energy }})</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="add_ene" min="0" max="{{ $maxStats }}" placeholder="0">
                            </div>
                        </div>
                        @if(in_array($char->Class, $cmdClasses))
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Command ({{ $char->Leadership }})</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="add_cmd" min="0" max="{{ $maxStats }}" placeholder="0">
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Add Stats</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
