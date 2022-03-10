@include('layouts/head')
<body>
    @include('layouts/guestuserheader')

<main class="page-wrap parent-main">
    <section class="account-area">
        <div class="container">
            <div class="account-area-content">
                <span><img src="{{asset('public/assets/images/clock.svg')}}" alt="icon"></span>
                <span>FAST HELP</span>
                <div class="login-area">
                    <a href="{{route('userlogin')}}" class="login-link">Member Login</a>
                    <span>Get Help</span>
                    <a href="{{route('rescue')}}" class="guest-link">Continue As Guest</a>
                </div>
            </div>
        </div>
    </section>
</main>
@include('layouts/footer')
</body>
</html>
