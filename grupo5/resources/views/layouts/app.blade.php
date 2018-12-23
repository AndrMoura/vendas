<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href = "{{ asset('css/app.css') }}" rel ="stylesheet">
    

</head>
<img id="appimage" src="https://d2v9y0dukr6mq2.cloudfront.net/video/thumbnail/itCjTBE/bright-green-tech-abstract-animated-background-motion-world-map-graphic-design-clip-ultra-hd-4k-3840x2160_rhdbgsd1e_thumbnail-full04.png">
<body>
        <div class="navbar">
        <nav>
                <a href="{{ url('/home') }}" id = "homeclass">
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
