@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="page-header"><h2>Contact Us</h2></div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Your Email</label>
                        <input type="email" class="form-control" name="contact_email" value="{{ old('contact_email') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" name="contact_message" rows="6" required>{{ old('contact_message') }}</textarea>
                        <span class="help-block">Max 300 characters.</span>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
