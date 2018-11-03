@extends('layouts.app')

@section('content')

<link href = "{{ asset('css/register.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">


                    @if (session('status'))
                    {{ session('status') }}
                    @endif

                    <form method="POST" action="{{ url('password/email') }}">
                        @csrf
                    <label for="email" class="emailadressreturn">{{ __('E-Mail Address')}}</label>
                    <input id="email" type="email" class ="inputs" name="email" value="{{ old('email') }}" required>
                     
                    @if ($errors->has('email'))
                    <strong>{{ $errors->first('email') }}</strong>
                    @endif
            
                    <button type="submit" >{{ __('Send Password Reset Link') }}</button>
                    </form>
                    @endsection
