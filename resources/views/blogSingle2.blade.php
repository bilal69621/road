@include('layouts/head')
<body>
<div class="blog-header">
    @include('layouts/header')
</div>
<main>
    <section class="blog-singles">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="blog-single-content">
                        {!!html_entity_decode($blog->blog)!!}

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@include('layouts/footer')
<script>
    $(document).ready(function () {
        var alt_text = 'Roadside assistance - {{ $blog->name }}';
        $('.blog-singles .blog-single-content img').attr('alt', alt_text);
    });
</script>
</body>
</html>