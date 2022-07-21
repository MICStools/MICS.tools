@extends('layouts.frontend')
@section('content')

<div class="container">
    <div class="row">
        <div class="topnav col-2">
       
        </div>

        {{-- @include('partials.welcome') --}}  
    </div>
</div>

<div class="container p-0">
    <div class="card mb-2">
        <div class="card-header" style="background: white; color: var(--mics-navy);">
            Log in to MICS <small class="text-muted">- You need to log in to add a citizen-science project to the MICS platform.</small>
        </div>
        <div class="row">
            <div class="col-7 m-auto text-center">
                <object type="image/svg+xml" style="width: 100%" data="/micswheel.svg"></object>
                
            </div>
            <div class="col-4 ml-auto px-5">

                @if(session('message'))
                    <div class="alert alert-info" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

            
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <p class="text-muted pt-4">Log in with a social media account</p>
                    <div class="flex-row align-items-center">
                        <div class="mb-1">
                            <a href="/login/google" class="btn btn-secondary btn-block"><i class="fa fa-google"></i> Google</a>
                        </div>
                        <div class="mb-1">
                            <a href="/login/facebook" class="btn btn-secondary btn-block"><i class="fa fa-facebook"></i> Facebook</a>
                        </div>
                        <div class="mb-1">
                            <a href="/login/twitter" class="btn btn-secondary btn-block"><i class="fa fa-twitter"></i> Twitter</a>
                        </div>
                        <div class="mb-1">
                            <a href="/login/linkedin" class="btn btn-secondary btn-block"><i class="fa fa-linkedin"></i> LinkedIn</a>
                        </div>
                        {{-- <div class="mb-1">
                            <a href="/login/microsoft" class="btn btn-secondary btn-block"><i class="fab fa-microsoft"></i> Microsoft</a>
                        </div> --}}
                        <div class="mb-1">
                            <a href="/login/github" class="btn btn-secondary btn-block"><i class="fa fa-github"></i> Github</a>
                        </div>
                    </div>

                    <hr>

                    <p class="text-muted pt-1">Or log in with email and password</p>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>

                        <input id="email" name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">

                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        </div>

                        <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}">

                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-4">
                        <div class="col-12 text-right">
                            @if(Route::has('password.request'))
                                <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                    {{ trans('global.forgot_password') }}
                                </a><br>
                            @endif
                        </div>
                        {{-- 
                        <div class="form-check checkbox">
                            <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                            <label class="form-check-label" for="remember" style="vertical-align: middle;">
                                {{ trans('global.remember_me') }}
                            </label>
                        </div>
                        --}}
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ trans('global.login') }}
                            </button>
                        </div>
                    </div> 

                    <hr>

                    <p class="text-muted pt-1">Do you want to register for an account?</p>

                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-primary btn-block" href="/register">
                                {{ trans('global.register') }}
                            </a><br>
                        </div>
                            
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>

        
@endsection