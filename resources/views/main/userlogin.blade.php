@include('layouts/head')<!-- comment -->
<body>
@include('layouts/main_header')
<main class="page-wrap parent-main">
 <section class="account-area">
            <div class="container">
                <div class="account-area-content">
                    <span><img src="{{asset('public/assets/images/clock.svg')}}" alt="icon"></span>
                    <span>GET HELP FAST</span>
                    <div class="login-area">
                        <form id="loginform">
                            @csrf
                           <div class="login-fields">
                               <input type="email" name="email" class="login-field" placeholder="Email Address"/>
                           </div>
                           <div class="login-fields">
                               <input type="password" name="password" class="login-field" placeholder="Password"/>
                           </div>
                           <div class="login-fields forgot-password">
                               <a href="{{route('foget_password')}}">Forgot Password</a>
                           </div>
                           <div class="login-fields">
                               <button type="submit" class="guest-link" id="loginbutton">Login</button>
                           </div>
                       </form>
                    </div>
                </div>
            </div>
        </section>
</main>
@include('layouts/footer')
    </body>
</html>

