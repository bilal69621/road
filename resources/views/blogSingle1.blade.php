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
                        <h3>Testing Your Car Battery Without a Multimeter</h3>
                        <p>A dead car battery can be a great inconvenience, particularly when it leaves you far from home and in deed of an emergency roadside assistance. However, testing a battery is not as easy as checking your tires <b>(which you can read more about here insert the link to previous blog article)</b> and it usually requires the use of a multimeter.</p>
                        <p>Car batteries all have different life spans and a lot of it has to do with the actual use that you make of your car. As a result of that, you might never know for sure what the status of the battery is unless you test it.</p>
                        <p>In this article we will help you with testing your battery without a multimeter.</p>
                        <h4>Make a visual inspection of your battery</h4>
                        <img src="{{asset('public/assets/images/blog4.png')}}"/>
                        <p>Your battery should never look “bloated”. This is usually a very clear indication that the battery might die at some point or another. You should also make sure that there are no leaks either as this would require an immediate replacement to avoid further issues.</p>
                        <p>Basically, all the sides of your battery should be perfectly straight. If that’s not the case you might end up being stuck somewhere anytime soon.</p>
                        <h4>Can you still use your car without it being on?</h4>
                        <img src="{{asset('public/assets/images/blog6.png')}}"/>
                        <p>Your battery should be able to still play the radio and have your lights on without the ending being turned on for at least 10 to 15 minutes.</p>
                        <p>
                        Turn on the car after you wait 15 minutes and if the headlight showcases a strong dimming then that means that your battery cannot produce enough voltage and that the likelihood of it stopping working is very high.
                        </p>
                        <h4>Make sure your engine doesn’t start slowly</h4>
                        <img src="{{asset('public/assets/images/blog7.png')}}"/>
                        <p>This is common knowledge and it is nonetheless very useful. Your engine shouldn’t sound “weak” when you start it. Your car should start pretty much immediately after you turn the engine on.</p>
                        <p>A slow engine start is an indicator that your battery might be on the verge of dying and that you should consider replacing it.</p>
                        <h4>Watch out for a clicking sound</h4>
                        <img src="{{asset('public/assets/images/blog8.png')}}"/>
                        <p>Batteries that are about to die might make a clicking sound when you are trying to switch on your car. This is a sound that you shouldn’t ignore as it means that your car is likely to not be starting anytime soon.</p>
                        <p>You should bring the car to replace its battery right away!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@include('layouts/footer')
</body>
</html>
