@extends('layouts.app')

@section('title') CAREERS | DRIVE | Roadside @endsection

@section('head')
@endsection


@section('header')
@endsection

@section('content')

    <!-- Contact Section -->
    <section class="careers-section">
        <div class="container">
            <div class="careers-content">
                <h2>Come Work With Us</h2>
                <form action="" class="career-form">
                    <div class="field-wrap flex sb">
                        <div class="field">
                            <input type="text" placeholder="First Name">
                        </div>
                        <div class="field">
                            <input type="text" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="field-wrap flex sb">
                        <div class="field">
                            <input type="email" placeholder="Email">
                        </div>
                        <div class="field">
                            <input type="text" placeholder="Phone">
                        </div>
                    </div>
                    <div class="field-wrap flex sb">
                        <div class="field">
                            <select name="" id="">
                                <option value="">option1</option>
                                <option value="">option2</option>
                            </select>
                        </div>
                        <div class="field">
                            <input type="date" placeholder="Available Date">
                        </div>
                    </div>
                    <div class="field-wrap flex sb">
                        <div class="field">
                            <input type="text" placeholder="Link to Your Resume">
                        </div>
                        <div class="field">
                            <input type="submit" value="Submit" style="    background: linear-gradient(to bottom, #0475ad, #348fbc);
    color: white;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('footer')
@endsection

@section('js')
@endsection
