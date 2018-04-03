<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>My Ice Tool</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Branding Image -->
                    <a class="navbar-brand logo1" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo.png')}}" alt="My Ice Tool" class="brand">
                    </a>

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed burgerhead" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        
                        @auth
                            @if(Auth::user()->isAdmin != 1)
                                <li><a href="#">Devenir admin</a></li>
                            @endif
                        @endauth
                        
                        @guest
                            <li><a href="#">Devenir admin</a></li>
                            <li><a href="{{ route('login') }}">Connexion</a></li>
                        @else
                        
                        <li><a href="{{ route('favoris') }}">Favoris</a></li>
                        
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->prenom }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('details') }}">
                                                Modifier mon profil
                                        </a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            Déconnexion
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <center><h4>Voici les dernières temperatures de la Zone</h4></center>
                <div class="modal-body">
                    <div>
                        <center id="noTemperature"><strong>Aucune température pour cette cascade !</strong></center>
                        <canvas id="lineChartTest" width="400" height="200"></canvas>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3I1xh6XQVZNT7jJ65hPUBDN_A6YAcAuE&libraries=geometry,places,drawing"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('js/Marker.js') }}"></script>
    <script src="{{ asset('js/Zone.js') }}"></script>
    <script src="{{ asset('js/Cascade.js') }}"></script>
    <script src="{{ asset('js/Autocompletion.js') }}"></script>
    <script src="{{ asset('js/Map.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    

    @auth
        <script>
            $cascade.data().showPostComment = true
            $cascade.data().user_id = {{Auth::user()->id}}
        </script>

        @if(Auth::user()->isAdmin == 1)
            <script src="{{ asset('js/Tooltip.js') }}"></script>
            <script src="{{ asset('js/Drawing.js') }}"></script>
            <script src="{{ asset('js/admin.js') }}"></script>
        @endif
    @endauth


    <div class="footer">
        <div class="footer-left">
        <p>© My Ice Tool</p>
        </div>
        <div class="footer-right">
            <ul>
                <li><a href="{{ route('conditions') }}">Conditions</a></li>
                <li><a href="{{ route('confidentialite') }}">Politique de confidentialité</a></li>
                <li><a href="{{ route('mentions') }}">Mentions légales</a></li>
                <li class="social-media" id="facebook">
                    <a href="{{ url('https://www.facebook.com/myicetool') }}" target="blank" class="btn" style="margin-left: -15px;">
                        <svg viewBox="0 0 32 32" role="presentation" aria-hidden="true" focusable="false" style="height: 15px; width: 15px; fill: currentcolor; margin-right: 5px;">
                            <path d="m8 14.41v-4.17c0-.42.35-.81.77-.81h2.52v-2.08c0-4.84 2.48-7.31 7.42-7.35 1.65 0 3.22.21 4.69.64.46.14.63.42.6.88l-.56 4.06c-.04.18-.14.35-.32.53-.21.11-.42.18-.63.14-.88-.25-1.78-.35-2.8-.35-1.4 0-1.61.28-1.61 1.73v1.8h4.52c.42 0 .81.42.81.88l-.35 4.17c0 .42-.35.71-.77.71h-4.21v16c0 .42-.35.81-.77.81h-5.21c-.42 0-.8-.39-.8-.81v-16h-2.52a.78.78 0 0 1 -.78-.78" fill-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
                <li class="social-media">
                    <a href="{{ url('/#') }}" class="btn" style="margin-left: -25px;">
                        <svg viewBox="0 0 32 32" role="img" aria-label="Twitter" focusable="false" style="height: 18px; width: 18px; display: block; fill: rgb(118, 118, 118);">
                            <path d="m31 6.36c-1.16.49-2.32.82-3.55.95 1.29-.76 2.22-1.87 2.72-3.38a13.05 13.05 0 0 1 -3.91 1.51c-1.23-1.28-2.75-1.94-4.51-1.94-3.41 0-6.17 2.73-6.17 6.12 0 .49.07.95.17 1.38-4.94-.23-9.51-2.6-12.66-6.38-.56.95-.86 1.97-.86 3.09 0 2.07 1.03 3.91 2.75 5.06-1-.03-1.92-.3-2.82-.76v.07c0 2.89 2.12 5.42 4.94 5.98-.63.17-1.16.23-1.62.23-.3 0-.7-.03-1.13-.13a6.07 6.07 0 0 0 5.74 4.24c-2.22 1.74-4.78 2.63-7.66 2.63-.56 0-1.06-.03-1.43-.1 2.85 1.84 6 2.76 9.41 2.76 7.29 0 12.83-4.01 15.51-9.3 1.36-2.66 2.02-5.36 2.02-8.09v-.46c-.03-.17-.03-.3-.03-.33a12.66 12.66 0 0 0 3.09-3.16" fill-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
                <li class="social-media">
                    <a href="{{ url('/#') }}" class="btn" style="margin-left: -15px;">
                        <svg viewBox="0 0 24 24" role="img" aria-label="Instagram" focusable="false" style="height: 18px; width: 18px; display: block; fill: rgb(118, 118, 118);">
                            <path d="m23.09.91c-.61-.61-1.33-.91-2.17-.91h-17.84c-.85 0-1.57.3-2.17.91s-.91 1.33-.91 2.17v17.84c0 .85.3 1.57.91 2.17s1.33.91 2.17.91h17.84c.85 0 1.57-.3 2.17-.91s.91-1.33.91-2.17v-17.84c0-.85-.3-1.57-.91-2.17zm-14.48 7.74c.94-.91 2.08-1.37 3.4-1.37 1.33 0 2.47.46 3.41 1.37s1.41 2.01 1.41 3.3-.47 2.39-1.41 3.3-2.08 1.37-3.41 1.37c-1.32 0-2.46-.46-3.4-1.37s-1.41-2.01-1.41-3.3.47-2.39 1.41-3.3zm12.66 11.63c0 .27-.09.5-.28.68a.92.92 0 0 1 -.67.28h-16.7a.93.93 0 0 1 -.68-.28.92.92 0 0 1 -.27-.68v-10.13h2.2a6.74 6.74 0 0 0 -.31 2.05c0 2 .73 3.71 2.19 5.12s3.21 2.12 5.27 2.12a7.5 7.5 0 0 0 3.75-.97 7.29 7.29 0 0 0 2.72-2.63 6.93 6.93 0 0 0 1-3.63c0-.71-.11-1.39-.31-2.05h2.11v10.12zm0-13.95c0 .3-.11.56-.31.77a1.05 1.05 0 0 1 -.77.31h-2.72c-.3 0-.56-.11-.77-.31a1.05 1.05 0 0 1 -.31-.77v-2.58c0-.29.11-.54.31-.76s.47-.32.77-.32h2.72c.3 0 .56.11.77.32s.31.47.31.76z" fill-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</body>
</html>