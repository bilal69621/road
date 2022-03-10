@include('layouts/head')
<body>
@include('layouts/header')

<main>
  <div class="dashboard-container">
           <div class="dashboard-sidebar">
               
               <div class="sidebar-leftbar">
                    <section class="leftbar-container">
                        <div class="main-title">
                            <h3>Change Password</h3>
                        </div>
                        <div class="grey-container">
                            <div class="password-title">
                                <h5>Enter Current Password</h5>
                                <div class="field-repeater">
                                    <label>Old Password</label>
                                    <div class="field-row">
                                        <input type="password" class="white-field o_pass" placeholder="" name="oldpassword" id="oldpassword"/>
                                        <img src="{{asset('public/assets/images/password_Show.png')}}" onclick="showhide('o_pass')"/>
                                    </div>
                                </div>
                            </div>
                            <div class="password-title">
                                <h5>Set New Password</h5>
                                <div class="field-repeater">
                                    <label>New Password</label>
                                    <div class="field-row">
                                        <input type="password" class="white-field n_pass" placeholder=""/>
                                        <img src="{{asset('public/assets/images/password_Show.png')}}" onclick="showhide('n_pass')"/>
                                    </div>
                                </div>
                                <div class="field-repeater">
                                    <label>Confirm New Password</label>
                                    <div class="field-row">
                                        <input type="password" class="white-field c_n_pass" placeholder=""/>
                                        <img src="{{asset('public/assets/images/password_Show.png')}}" onclick="showhide('c_n_pass')"/>
                                    </div>
                                </div>
                                <div class="field-repeater">
                                    <button class="btn btn-blue saveChanges">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </section>
               </div>
           </div>
       </div>   
</main>
<script>

</script>
@include('layouts/sidebar')
@include('layouts/footer')
</body>
</html>

