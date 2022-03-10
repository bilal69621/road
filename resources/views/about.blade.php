@extends('layouts.app')

@section('title') ABOUT US | DRIVE | Roadside @endsection

@section('head')
@endsection


@section('header')
@endsection

@section('content')
    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <h2>about us</h2>
                <p><strong>DRIVE</strong> is a technology company that provides innovative products within the insurance industry. We utilize the latest technology to offer more efficient solutions for our partners & members. Our mission is to provide the highest level of service by integrating cutting-edge technology to automate & enhance our member's experience. Our roadside assistance platform and membership have been strategically designed & carefully crafted to provide our members with superior roadside assistance while increasing our partner's retention & profitability.</p>
            </div>
            <div class="contact_information">
                <h2>CONTACT US</h2>
            </div>
            <div class="cus">
                <div class="cus_contact">
                    <h2>CUSTOMER CARE</h2>
                    <p>info@driveroadside.com</p>
                </div>
                <div class="cus_contact">
                    <h2>REFUND REQUEST</h2>
                    <p>refunds@driveroadside.com</p>
                </div>
                <div class="cus_contact">
                    <h2>PARTNERSHIPS</h2>
                    <p>partners@driveroadside.com</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Map -->
    <!-- <section class="google-map">
        <div class="container">
            <div class="googlemap-content">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3322.192320647417!2d-111.94442378448761!3d33.62625504750489!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x872b75b21ed7e0d5%3A0x547e3724e880995e!2sDrive%20%7C%20Roadside%20Assistance!5e0!3m2!1sen!2s!4v1574687827591!5m2!1sen!2s" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            </div>
        </div>
    </section> -->
    <?php include resource_path('views/admin/include/messages.php'); ?>
    <!-- Contact Section -->
    <section id="contact-us" class="contact-section">
      <!--  <div class="container">
            <div class="contact-content">
                <h2>Contact Us</h2>
                <form action="{{url('/contact_us')}}" method="POST" class="contact-form">
                    @csrf
                    <div class="field">
                        <input type="text" name="first_name" placeholder="First Name" required>
                    </div>
                    <div class="field">
                        <input type="text" name="last_name" placeholder="Last Name" required>
                    </div>
                    <div class="field">
                        <input type="text" name="email" placeholder="Email" required>
                    </div>
                    <div class="field">
                        <input type="text" name="phone_number" placeholder="Phone Number" required>
                    </div>
                    <div class="field">
                        <textarea name="message" id="" cols="30" rows="4" placeholder="Type your message here..." required></textarea>
                    </div>
                    <div class="field btn">
                        <input type="submit" value="Submit" style="    background: linear-gradient(to bottom, #0475ad, #348fbc);color: white;">
                    </div>
                </form>
            </div>
        </div> -->
    </section>
@endsection

@section('footer')
@endsection

@section('js')
@endsection
