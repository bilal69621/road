<!DOCTYPE html>
<html>
<head>
    <title>{{$title}}</title>
      @isset($description)
    <meta charset="UTF-8" name="description" content={{$description}}>
    @endisset
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ config('app.asset_template') }}images/Fav.svg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="shortcut icon" type="image/png" href="{{asset('public/assets/images/fevicon.png')}}" />
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/css/all.css')}}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-155840371-1"></script>
    <script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"137021812"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>
     <script
    src="https://www.paypal.com/sdk/js?client-id=AXnl9SjO8UDN-9YuwQUCJ1p4j4g6Wn3guSGgXpCa3Blhb1NUGgTEcKchVqmxP7VswcJ4iEJKUbFMYe6H&disable-funding=credit">  </script>
    <link
     rel="stylesheet"
     href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
   />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-155840371-1');
        gtag('config', 'AW-676743917');
    </script>
</head>
