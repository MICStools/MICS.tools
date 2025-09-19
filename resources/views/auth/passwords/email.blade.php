@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('admin.home') }}">
            {{ trans('panel.site_title') }}
        </a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">
            {{ trans('global.reset_password') }}
        </p>

        {{-- Show any generic session-wide messages --}}
        @if(session('message'))
            <div class="alert alert-warning" role="alert">
                {{ session('message') }}
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- Honeypot field --}}
            <x-honeypot />

            <input type="hidden" name="form_start" value="{{ now()->timestamp }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email"
                       required autocomplete="email" autofocus
                       placeholder="{{ trans('global.login_email') }}"
                       value="{{ old('email') }}">

                @if($errors->has('email'))
                    <span class="help-block" role="alert">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-flat btn-block">
                        {{ trans('global.send_password') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
