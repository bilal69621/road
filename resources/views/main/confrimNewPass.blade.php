@include('layouts/head')<!-- comment -->
<body>
@include('layouts/main_header')
<main class="page-wrap parent-main">
 <section class="account-area">
            <div class="container">
                <div class="account-area-content">
                    <span><img src="{{asset('public/assets/images/clock.svg')}}" alt="icon"></span>
                    <span>GET HELP FAST</span>
                    <div class="login-area">
                        <form class="loginform" id="changePasswordForm" action="javascriptvoid(0)" method="post">
                            @csrf
                           <div class="login-fields">
                               <input type="text" id="reset_pass_new" name="password" class="login-field" placeholder="Enter Password"/>
                           </div>
                           <div class="login-fields">
                               <input type="text" id="reset_pass_new_c" name="c_password" class="login-field" placeholder="Confirm Password"/>
                           </div>
                            <input type="hidden" name="token" value="" id="passtoken">

                           <div class="login-fields">
                               <button type="button" class="guest-link" >Reset Password</button>
                           </div>
                       </form>
                    </div>
                </div>
            </div>
        </section>
    <script src="<?= asset('public/bower_components/jquery/dist/jquery.min.js')?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= asset('public/bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
          const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })
    $('.guest-link').on('click',function(){
        var pass = $('#reset_pass_new').val();
        var c_pass = $('#reset_pass_new_c').val();
        if(pass != c_pass)
        {
            Toast.fire({
            icon: 'error',
            title: "Both Password should be same",
            }); 
        }else if((pass == "") ||(c_pass == '')){
            Toast.fire({
            icon: 'error',
            title: "Fields are required",
            });
        }else{
                $.ajax({
                type: 'POST',
                url: '{{route('change_password')}}',
                data: $('#changePasswordForm').serialize(),
                success: function (data) {
            if(data.error == 1)
                {
                     Toast.fire({
                        icon: 'error',
                        title: data.message,
                      });
                }else
                {
                    $('#changePasswordForm').trigger("reset");
                        Toast.fire({
                        icon: 'success',
                        title: data.message,
                      });
                      window.location.href = "{{URL::to('userlogin')}}";
                }


                }
            });
        }
    })
</script>
<script>
    var url = window.location.href;
    var array = url.split("/");
    var num = array.length;
    $('#passtoken').val(array[num-1]);
</script>
</main>
@include('layouts/footer')
    </body>
</html>

