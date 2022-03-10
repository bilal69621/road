@extends('layouts.app')

@section('title') DRIVE | Roadside Assistance Plans @endsection

@section('head')
@endsection


@section('header')
@endsection

@section('content')

    <!-- Partnership Section -->
    <section class="partnership-section">
        <div class="container">
            <div class="partnership-content">
                <img src="{{ config('app.asset_template') }}images/partner.png" alt="">
                <h2>Drive | <span>A True Partnership</span></h2>
                <p>Our top priority is our partners & the members they serve</p>
                <p>We believe that trust & dependability are the cornerstones of any true partnership.</p>
                <p>You handle the sales, We handle the risk & service while providing you a partner sized split up to 40%.</p>
                <p>Receive residual commissions for the life of the memberships even if the customer cancels & sign up with us</p>
                <br>
                <p>“If you sell something, you make a customer today. If you help someone, you make a customer for life”</p>
                <h5> –Jay Baer</h5>
                <br>
                <p>“The best and quickest way to succeed is by helping others to succeed.“</p>
                <h5> – Napoleon Hill</h5>
            </div>
        </div>
    </section>
    <!-- Profits Section -->
    <section class="profits-section">
        <div class="container">
            <div class="profits-content">
                <img src="{{ config('app.asset_template') }}images/increased-profit.png" alt="">
                <h2>Drive | <span>increased profit</span></h2>
                <p>The average agency increases their companies profit by 32% in the first 12 months of working with us.</p>
                <h3>Example</h3>
                <h6>Agency sells 18,000 car insurance policies annually, equating to $12M in premium.  12% Commission = $1.4M</h6>
                <p>Agency adds DRIVE to 18,000 car policies a month equating to  $1.8M in premium. 40% Commission = $720K</p>
                <a href="https://roadsidemembership.com/signup" target="_blank"class="join_link">Become Partner</a>
            </div>
        </div>
    </section>
    <!-- Partner Dashboard -->
    <section class="partner-dashboard">
        <div class="container">
            <div class="partner-content">
                <h2>Drive | <span>Partner Dashboard</span></h2>
                <div class="dashboard-data flex sb">
                    <div class="dashboard-feature">
                        <h5>Manage agencies Sale</h5>
                        <h6>Manage commissions</h6>
                    </div>
                    <div class="dashboard-image">
                        <img src="{{ config('app.asset_template') }}images/dashboard.png" alt="">
                    </div>
                    <div class="dashboard-feature right">
                        <h5>Run detailed reports</h5>
                        <h6>track payouts</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Drive Section -->
    <section class="drive-section">
        <div class="container">
            <div class="drive-content flex sb">
                <div class="drive-image">
                    <img src="{{ config('app.asset_template') }}images/network-mid.png" alt="">
                </div>
                <div class="drive-text">
                    <h2>Drive | <span>More than roadside</span></h2>
                    <p>In addition to the traditional 24/7 phone support, our industry-leading <br> roadside assistance app provides members the ability to skip the traditional time-consuming pre-rescue verification process. We enable users to share their membership information and exact location with our closest roadside providers instantly. Members also have the ability to view their rescue truck on a live map until it arrives (Which has been proven to reduce stress levels). Members can also access additional app features to find mechanics, order rides, rent  cars, set insurance payment reminders, find the lowest priced gas stations and more…</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Retention -->
    <section class="retention-section">
        <div class="container">
            <div class="retention-content">
                <img src="{{ config('app.asset_template') }}images/Retention.png" alt="">
                <h2>Drive | <span>increased retention</span></h2>
                <p>Quickly build an automated book of business/residual income with automatic payments & renewals.</p>
                <p>Claims won’t increase car insurance rates, which helps prevent clients from shopping around at renewal.</p>
                <p>We provided members outstanding service in moments that matter most, which creates customer loyalty</p>
                <a href="https://roadsidemembership.com/signup" target="_blank" class="join_link">Become a Partner</a>
            </div>
        </div>
    </section>
    <!-- Dashboard Functionalities -->
    <section class="dashboard-func">
        <div class="container">
            <div class="func-content">
                <div class="func-row flex">
                    <div class="func-image">
                        <img src="{{ config('app.asset_template') }}images/signup.png" alt="">
                    </div>
                    <div class="func-text">
                        <h2>Sign up</h2>
                        <p>Enter some basic information on our partner portal and wait for one of our territory managers to contact you. While you wait you can take a tour of your Partner dashboard and its capabilities.</p>
                    </div>
                </div>
                <div class="func-row flex">
                    <div class="func-image portal">
                        <img src="{{ config('app.asset_template') }}images/portal.png" alt="">
                    </div>
                    <div class="func-text">
                        <h2>Access Web portal</h2>
                        <p>Once approved you will receive a custom web-link which will be used to track sales & analytics. This link will be in your dashboard and can be shared and added to your website.</p>
                    </div>
                </div>
                <div class="func-row flex zero">
                    <div class="func-image">
                        <img src="{{ config('app.asset_template') }}images/selling.png" alt="">
                    </div>
                    <div class="func-text">
                        <h2>Start Selling</h2>
                        <p>Use your new custom link to start processing sales.</p>
                    </div>
                </div>
                <a href="https://roadsidemembership.com/signup" target="_blank" class="join_link">Become a Partner</a>
            </div>
        </div>
    </section>
    <!-- Network Section -->
    <section class="network-section">
        <div class="network-row network-row2 partnership">
            <div class="container">
                <div class="flex">
                    <div class="network-icon car-icon">
                        <img src="{{ config('app.asset_template') }}images/car.jpg" alt="">
                        <h5>we cover you in any car</h5>
                    </div>
                    <div class="network-icon">
                        <img src="{{ config('app.asset_template') }}images/network4.png" alt="">
                        <h5>usage won't increase Car<br> insurance RATES</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="network-row flex partnership">
            <div class="container">
                <div class="flex">
                    <div class="network-icon">
                        <img src="{{ config('app.asset_template') }}images/network1.png" alt="">
                        <h5>request help faster & track <br> rescue truck using our app</h5>
                    </div>
                    <div class="network-mid partnership">
                        <img src="{{ config('app.asset_template') }}images/network-mid.png" alt="">
                    </div>
                    <div class="network-icon">
                        <img src="{{ config('app.asset_template') }}images/network2.png" alt="">
                        <h5>24/7 Roadside assistance<br> phone support</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="network-head partnership">
            <h2>network</h2>
            <h3>11 Million+ <span>Drivers rescued each year</span></h3>
        </div>
    </section>
@endsection

@section('footer')
@endsection

@section('js')
@endsection
