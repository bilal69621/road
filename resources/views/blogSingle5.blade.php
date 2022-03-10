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
                        <p>Taking good care of your car doesn’t necessarily mean that you need to invest a lot of time into it. Most of the things you could take care of are effortless and can be done while pumping gas or in less than 5 minutes. <b>Additionally, this decreases the odds to need repairs or, even worse, the need of emergency roadside assistance.</b>Here are a few tips that anybody could follow.</p>
                        <h4>1.	Clean your windshield</h4>
                        <img src="{{asset('public/assets/images/blog5.png')}}"/>
                        <p>This is something that can be done while pumping gas and it helps removing dirt that might be hard to get using your wipers.</p>
                        <p>Keeping your windshield clean while driving is essential particularly for extended trips or nighttime. Don’t forget to clean your wipers too or they won’t clean as well.</p>
                        <h4>2.	Check your tire pressure</h4>
                        <img src="{{asset('public/assets/images/blog51.png')}}"/>
                        <p>This is another effortless task that you should perform every other week. Make sure to not run your car with the wrong tire pressure to not increase your tire wear and tear but most importantly to not risk any tire related issue.</p>
                        <p>
                        Here are some further tips on checking the tire pressure:
                        </p>
                        <p>- Always check your tire pressure with cold tires since you will be getting an accurate reading</p>
                        <p>- Always check what your ideal tire pressure should be. This is usually written in a sticker inside your driver seat</p>
                        <h4>3.	Check and refill your engine oil level</h4>
                        <img src="{{asset('public/assets/images/blog52.png')}}"/>
                        <p>Engine oil is essential for your car to run and not break down. It works as a lubricant for all the mechanical parts involved in the correct operation of the vehicle. Your dashboard will notify you when your oil level is low. We recommend to always have a refill in the trunk of your car and replace it immediately when needed.</p>
                        <p>There are also a few general guidelines to follow to reduce the risks of emergency situations requiring roadside assistance:</p>
                        <p>- Change your oil every 5,000 miles<b>- Change air filter every 12,000 miles<b>- Rotate tires every 5,000 to 10,000 miles<br>- Clean the interior and exterior of your cars at least once a month</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@include('layouts/footer')
</body>
</html>
