@include('layouts/head')
<body>
<div class="blog-header">
    @include('layouts/header')
</div>
<main>
    <section class="news-banner" style="backgrounnd-image:url({{asset('public/assets/images/down-arrow.png')}});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="news-titles">
                        <h1></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="home-banner" style="background-image:url({{asset('public/assets/images/blog15.png')}});">
        <div class="container">
            <div class="home-banner-text">
                <h2>DRIVE | How to maintain your vehicle</h2>
                <!-- <h4>May 13th</h4> -->
            </div>

            <!----
			<div class="home-banner-btn">
				<a class="read-more" href="#">Read<i class="fa fa-angle-right"></i></a>
			</div> --->
        </div>
    </section>
    <section class="custom-blogs">
        <div id="exTab1" class="container">
            <ul class="nav nav-pills">
                <li>
                    <a href="{{url('news/all')}}" >All</a>
                </li>
                @foreach($categories as $category)
                    <li><a href="{{url('news/'.$category->id)}}">{{$category->name}}</a>
                    </li>
                @endforeach
{{--                <li><a href="#3a" data-toggle="tab">safety</a>--}}
{{--                </li>--}}
{{--                <li><a href="#4a" data-toggle="tab">travel</a>--}}
{{--                </li>--}}
{{--                <li><a href="#1a" data-toggle="tab">Vehicle Maintenance</a>--}}
{{--                </li>--}}
            </ul>

            {{--        <div class="tab-content clearfix">--}}
            {{--            <div class="tab-pane active" id="1a">--}}
            {{--                <div class="row">--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}

            {{--                </div>--}}

            {{--            </div>--}}
            {{--            <div class="tab-pane" id="2a">--}}
            {{--                <div class="row">--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}

            {{--                </div>--}}
            {{--            </div>--}}
            {{--            <div class="tab-pane" id="3a">--}}
            {{--                <div class="row">--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}

            {{--                </div>--}}
            {{--            </div>--}}
            {{--            <div class="tab-pane" id="4a">--}}
            {{--                <div class="row">--}}
            {{--                    <div class="col-md-4">--}}
            {{--                        <div class="producrsec-p">--}}
            {{--                            <a href="#">--}}
            {{--                                <div class="image-text-box">--}}
            {{--                                    <div class="image-text-bg"--}}
            {{--                                         style="background-image:url(http://localhost/roadsideapi/public/blog/v3kKrNFDSX.jpg);">--}}

            {{--                                    </div>--}}
            {{--                                    <div class="image-text-detail">--}}
            {{--                                        <h4>featured-news</h4>--}}
            {{--                                        <h2>asdd</h2>--}}
            {{--                                        <h4>12345</h4>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}

            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
        </div>
    </section>


    <section class="grid-sec">
        <div class="container">
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="col-md-4" id="nav-tab1">
                        <a href="{{url('blog/'.$blog->id)}}">
                            <div class="image-text-box">
                                <div class="image-text-bg"
                                     style="background-image:url({{asset('public/blog/'.$blog->main_image)}});">
                                </div>
                                <div class="image-text-detail">
                                    <h4>featured-news</h4>
                                    <h2>{{$blog->name}}</h2>
                                    <h4>{{$blog->created_at->format('m/d/Y')}}</h4>
                                </div>

                            </div>
                        </a>

                    </div>
                @endforeach
            </div>

        </div>

    </section>
</main>
@include('layouts/footer')
</body>
</html>
