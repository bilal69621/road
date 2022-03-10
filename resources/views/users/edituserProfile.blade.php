@include('layouts/head')
<body>
@include('layouts/header')
<main>
     <div class="dashboard-container">
           <div class="dashboard-sidebar">
            
               <div class="sidebar-leftbar">
                    <section class="leftbar-container">
                        <div class="main-title">
                            <h3>Edit Profile</h3>
                        </div>
                        <div class="grey-container">
                            <form action="#" method="POST" id="editProfileUser" enctype="multipart/form-data">
                                @csrf
                            <div class="edit-profile">
                                <div class="profile-imgs">
                                    <div class="profile-imgs" id="profileImage" style="background-image: url({{asset('public/svg/'.$user->avatar)}});">
                                        <input type="file" style="display:none;" name="p_image"  id="profile-imgs"/>
                                        <label for="profile-imgs">
                                            <img src="{{asset('public/assets/images/camera.svg')}}" class="camera-icons"/>
                                        </label>
                                    </div>
                                </div>
                                <div class="edit-row">
                                    <div class="field-repeater">
                                        <label>Name</label>
                                        <div class="field-row">
                                            <input type="text" name="name" class="white-field" placeholder="add name" value="{{$user->name}}">
                                        </div>
                                    </div>
                                    <div class="field-repeater">
                                        <label>Email Address</label>
                                        <div class="field-row">
                                            <input type="email" name="email" class="white-field" placeholder="marie.meyer@mail.com" value="{{$user->email}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-row">
                                    <div class="field-repeater">
                                        <label>Phone No.</label>
                                        <div class="field-row">
                                            <input type="text" name="contact_number" class="white-field" placeholder="(116)944-0372" value="{{$user->contact_number}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-row">
                                    <div class="full-width">
                                        <label>Street Address</label>
                                        <div class="field-row">
                                            <input type="text"  name="address"class="white-field" placeholder="Royal Street 123" value="{{$user->address}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-row">
                                    <div class="field-repeater">
                                        <label>City</label>
                                        <div class="field-row">
                                            <input type="text" name="city" class="white-field" placeholder="New York" value="{{$user->city}}">
                                        </div>
                                    </div>
                                    <div class="field-repeater">
                                        <label>State</label>
                                        <div class="field-row">
                                            <input type="text" name="state" class="white-field" placeholder="New York" value="{{$user->state}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-row">
                                    <div class="field-repeater">
                                        <label>Zip Code</label>
                                        <div class="field-row">
                                            <input type="text" name="zipcode" class="white-field" placeholder="23432" value="{{$user->zipcode}}">
                                        </div>
                                    </div>
                                    <div class="field-repeater">
                                        <label>Country</label>
                                        <div class="field-row">
                                            <input type="text" name="country" class="white-field" placeholder="United States America" value="{{$user->country}}">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="userId" value="{{$user->id}}"/>
                                <input type="hidden" name="routeupdate" id="routeupdate" value="{{route('updateUserProfile')}}"/>
                                <input type="hidden" name="routeupdateP" id="routeupdateP" value="{{route('updateprofilepic')}}"/>
                                <input type="hidden" name="imagePath" id="imagePath" value="{{asset('public/svg/')}}" />
                                <div class="edit-row">
                                    <div class="full-width">
                                        <button type="submit" class="btn btn-blue">Save Changes</button>
                                    </div>
                                </div>    
                            </div>
                            </form>
                        </div>
                    </section>
               </div>
           </div>
       </div>
</main>
@include('layouts/sidebar')
@include('layouts/footer')

</body>
</html>

