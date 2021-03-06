@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-4">
                            <a href="{{ url('/facebook') }}" class="btn btn-facebook">
                                <svg viewBox="0 0 32 32" role="presentation" aria-hidden="true" focusable="false" style="height: 18px; width: 18px; fill: currentcolor; margin-right: 5px;">
                                    <path d="m8 14.41v-4.17c0-.42.35-.81.77-.81h2.52v-2.08c0-4.84 2.48-7.31 7.42-7.35 1.65 0 3.22.21 4.69.64.46.14.63.42.6.88l-.56 4.06c-.04.18-.14.35-.32.53-.21.11-.42.18-.63.14-.88-.25-1.78-.35-2.8-.35-1.4 0-1.61.28-1.61 1.73v1.8h4.52c.42 0 .81.42.81.88l-.35 4.17c0 .42-.35.71-.77.71h-4.21v16c0 .42-.35.81-.77.81h-5.21c-.42 0-.8-.39-.8-.81v-16h-2.52a.78.78 0 0 1 -.78-.78" fill-rule="evenodd"></path>
                                </svg>
                                Connexion avec Facebook
                            </a>
                        </div>
                        <div class="col-md-4 col-md-offset-4">
                            <a href="{{ url('/google') }}" class="btn btn-google">
                            <svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="height: 18px; width: 18px; margin-right: 5px;">
                                <g fill="none" fill-rule="evenodd">
                                    <path d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z" fill="#EA4335"></path>
                                    <path d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.1.83-.64 2.08-1.84 2.92l2.84 2.2c1.7-1.57 2.68-3.88 2.68-6.62z" fill="#4285F4"></path>
                                    <path d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05"></path>
                                    <path d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.84-2.2c-.76.53-1.78.9-3.12.9-2.38 0-4.4-1.57-5.12-3.74L.97 13.04C2.45 15.98 5.48 18 9 18z" fill="#34A853"></path>
                                    <path d="M0 0h18v18H0V0z"></path>
                                </g>
                            </svg>
                                Connexion avec Google
                            </a>
                        </div>
                    </div>
                    
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="col-md-12" style="margin-top: 16px;margin-bottom: 16px;">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div style="margin-top: 16px;margin-bottom: 16px;">
                            <div class="lineOverall">
                                <span class="line">
                                    <span class="textOu">
                                        <span>ou</span>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-md-4 col-md-offset-4">
                                <input id="email" type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                                <svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="fill: currentcolor; position: absolute; display: inline-block; width: 25px; height: 25px; left: 87%; top: 5px;}">
                                    <path d="m22.5 4h-21c-.83 0-1.5.67-1.5 1.51v12.99c0 .83.67 1.5 1.5 1.5h20.99a1.5 1.5 0 0 0 1.51-1.51v-12.98c0-.84-.67-1.51-1.5-1.51zm.5 14.2-6.14-7.91 6.14-4.66v12.58zm-.83-13.2-9.69 7.36c-.26.2-.72.2-.98 0l-9.67-7.36h20.35zm-21.17.63 6.14 4.67-6.14 7.88zm.63 13.37 6.3-8.1 2.97 2.26c.62.47 1.57.47 2.19 0l2.97-2.26 6.29 8.1z" fill-rule="evenodd"></path>
                                </svg>
                                
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-4 col-md-offset-4">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                                <svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="fill: currentcolor; position: absolute; display: inline-block; width: 25px; height: 25px; left: 87%; top: 5px;">
                                    <path d="m19.5 9h-.5v-2a7 7 0 1 0 -14 0v2h-.5c-.78 0-1.5.72-1.5 1.5v12c0 .78.72 1.5 1.5 1.5h15c .78 0 1.5-.72 1.5-1.5v-12c0-.78-.72-1.5-1.5-1.5zm.5 13.5c0 .22-.28.5-.5.5h-15c-.22 0-.5-.28-.5-.5v-12c0-.22.28-.5.5-.5h1a .5.5 0 0 0 .5-.5v-2.5a6 6 0 1 1 12 0v2.5a.5.5 0 0 0 .5.5h1c .22 0 .5.28.5.5zm-8-10.5a3 3 0 0 0 -3 3c0 .83.36 1.59.94 2.15l-.9 2.16a.5.5 0 0 0 .46.69h5a .5.5 0 0 0 .46-.69l-.87-2.19c.56-.55.91-1.31.91-2.13a3 3 0 0 0 -3-3zm1.04 5.19.72 1.81h-3.51l.74-1.79a.5.5 0 0 0 -.17-.6 2 2 0 1 1 3.18-1.61c0 .64-.31 1.24-.8 1.6a.5.5 0 0 0 -.17.59zm-1.04-14.19a4 4 0 0 0 -4 4v2.5a.5.5 0 0 0 .5.5h7a .5.5 0 0 0 .5-.5v-2.5a4 4 0 0 0 -4-4zm3 6h-6v-2a3 3 0 1 1 6 0z" fill-rule="evenodd"></path>
                                </svg>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label style="float:left;">
                                        <input type="checkbox" name="remember" style="font-size: 16px;" {{ old('remember') ? 'checked' : '' }}> <span style="font-weight:100;font-size:15px;">Se souvenir de moi</span>
                                    </label>
                                    <div style="float:left;margin-left:11%;">
                                        <button type="button" class="afficheMDP" aria-disabled="false" aria-busy="false">
                                            <span>Afficher le mot de passe</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-primary connexion">
                                    Connexion
                                </button>
                            </div>
                            <div class="col-md-4 col-md-offset-4">
                                <a class="btn btn-link forgetMDP" href="{{ route('password.request') }}">
                                    Mot de passe oublié ?
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <span style="font-weight:100;">Vous n'avez pas de compte?</span> 
                                <a class="inscription" href="{{ route('register') }}">Inscription</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection