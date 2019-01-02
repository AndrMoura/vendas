@extends('layouts.app')

<head>
    <title> Password retrieve</title>
</head>
@section('content')

<link href = "{{ asset('css/register.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
                        
            <h1>{{ __('Reset Password') }}</h1>

            <form method="POST" action="{{ url('password/reset') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                    <label for="email">{{ __('E-Mail Address') }}</label>

                        <input id="email" type="email" class="inputs" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                        <strong>{{ $errors->first('email') }}</strong>
                        @endif
                
                    <label for="password" >{{ __('Password') }}</label>
                    <input id="password" type="password" class="inputs" name="password" required>

                        @if ($errors->has('password'))
                        <strong>{{ $errors->first('password') }}</strong>
                        @endif

                    <label for="password-confirm">{{ __('Confirm Password') }}</label>

                        <input id="password-confirm" type="password" class="inputs" name="password_confirmation" required>
                
                        <button type="submit" >
                            {{ __('Reset Password') }}
                        </button>
                     
@endsection
