@extends('layouts.app')

@section('content')
<link href = "{{ asset('css/register.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
                        
                    	
        <title>Sign Up Form</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
                  
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                         <fieldset>
                         <legend>Fill the form</legend>
                        <br>
                            <label for="name">{{ __('Name') }}</label>
                            <input id="name" type="text"  name="name" class = "inputs" value="{{ old('name') }}" autofocus required>
                             @if ($errors->has('name'))
                            <strong>{{ $errors->first('name') }}</strong>
                             @endif
                             <br>


                            <label for="username">{{ __('Username') }}</label>
                            <input id="username" type="text"  name="username" class = "inputs" value="{{ old('username') }}" autofocus required>
                            @if ($errors->has('username'))
                            <strong>{{ $errors->first('username') }}</strong>
                            @endif
                             <br>

                        
                            <label for="email" >{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" name="email" class = "inputs" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                            <strong>{{ $errors->first('email') }}</strong>
                            @endif
                            <br>


                            <label for="password" >{{ __('Password') }}</label>
                            <input id="password" type="password" name="password"  class = "inputs" required>
                            @if ($errors->has('password'))
                            <strong>{{ $errors->first('password') }}</strong>
                            @endif
                            <br>

                        
                            <label for="password-confirm" >{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" name="password_confirmation" class = "inputs" required>
                           

                            <hr>
                        
                            <button type="submit" class = "registercss">
                            {{ __('Register') }}
                            </button>
                            
                    
                     </div>

@endsection
