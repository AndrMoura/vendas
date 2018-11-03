@extends('layouts.app')

@section('content')
<link href = "{{ asset('css/register.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">


                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                            <label for="username">{{ __('Username') }}</label>
                            <input id="username" type="text" class="inputs" name="username" value="{{ old('username') }}" autofocus required>
                            @if ($errors->has('username'))
                            <strong>{{ $errors->first('username') }}</strong>
                            @endif
                            
                      
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class="inputs" name="password" required>
                            @if ($errors->has('password'))
                            <strong>{{ $errors->first('password') }}</strong>
                            @endif
                           

                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember"> {{ __('Remember Me') }}</label>
                          
                            <hr>
                            <button type="submit" > {{ __('Login') }}</button>

                            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                            
@endsection
