@extends('layouts.app')

@section('content')
<style>

    *{
        margin:0;
        padding:0;
    }
    body{
        overflow: hidden;
    }

    .bg-image{
        /* Set rules to fill background */
        min-height: 100%;
        min-width: 1024px;
            
        /* Set up proportionate scaling */
        width: 100%;
        height: auto;
            
        /* Set up positioning */
        position: fixed;
        top: 0;
        left: 0;

        filter: blur(5px);
        -webkit-filter: blur(2.5px);
        
    }
    image-container{}
    @media only screen and (max-width: 600px) {
        #errLogin {
            font-size:10px;
        }
    }
</style>
<img src="/images/bg-02.jpg" class="bg-image">
<div class="container-login100" style="">
    <div class="wrap-login100 login-container-md">
        <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
            <div class=container>
                <span class="login100-form-logo">
                    <img class="login-logo" src="/images/logo_circuit-solutions.png" style="width:70%; height:auto;">
                </span>
            </div>
            <br>

            @csrf

            <div class="container">
                <div class="form-group">
                    <div class="form__group col-md-10 fg_margin">
                        <input id="email" type="email" class="form__field{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
                        <label for="email" class="span-header form__label"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Email Address</label>
                        @if ($errors->has('email'))
                            <label id="errLogin" style="color:red;"><i class="fa fa-times" aria-hidden="true" style="margin-right:3px"></i>{{ $errors->first('email') }}</label>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="form__group col-md-10 fg_margin">
                        <input id="password" type="password" class="form__field{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" name="password" required>
                        <label for="password" class="span-header form__label"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Password</label>
                        @if ($errors->has('password'))
                            <label id="errLogin" style="color:red";><i class="fa fa-times" aria-hidden="true" style="margin-right:3px"></i>{{ $errors->first('password') }}</label>
                        @endif
                    </div>

                    <div class="form__group col-md-10 fg_margin">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="" style="text-align: center">
                    <div class="form__group col-md-10 fg_margin">
                        <input type="submit" class="btn button blue" value="{{ __('Login') }}" style="border-radius:40px; width:150px;">
                        <!-- <button type="submit" class="btn button blue" style="border-radius:40px; width:150px;">
                            {{ __('Login') }}
                        </button> -->
                    </div>
                    <div class="form__group col-md-10 fg_margin">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
    
</div>
@endsection
