<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href = "{{ asset('css/app.css') }}" rel ="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"><link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


</head>
<body>

<div id="appimage">

</div>

        <div class="navbar">
        <nav>
                <a href="{{ url('/') }}" id = "homeclass">
                   <strong> Homepage </strong>
                </a>
                        <!-- Authentication Links -->
                        @guest
                        <a  href="{{ route('login') }}">{{ __('Login') }}</a>
                        
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                         @endif
                            
                        @else
                        <div class="dropdown">
                        <button class="dropbtn">{{ Auth::user()->name }} </button>

                        <div class="dropdown-content">
                        @if (Auth::user()->role =='user')
                                <a  href="/profile/{{Auth::user()->id}}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Profile 
                                </a>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                                                
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>

                        @else
                        <a  href="/manage/products" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Manage products
                                </a> 
                                
                        <a  href="/manage/users" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Manage users 
                         </a> 


                                <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                </form>

                        @endif
                        @endguest    
                 </nav>
            </div>
     </div>
</div>
</body>
        <main class="py-4">
            @yield('content')
        </main>
    

</html>
