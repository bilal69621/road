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
                        <form class="loginform" id="resetlinkForm" action="javascriptVoid(0)" method="POST">
                            @csrf
                           <div class="login-fields">
                               <input type="email" name="email" class="login-field" placeholder="Email Address"/>
                           </div>

                           <div class="login-fields">
                               <button type="button" id="guest-link" class="guest-link" >Send Link</button>
                           </div>
                       </form>
                    </div>
                </div>
            </div>
        </section>
</main>
@include('layouts/footer')
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
        document.getElementById("guest-link").style.background='#012b54';
        $('#guest-link').html('<i class="fa fa-spinner fa-spin"></i> Sending');
        $('#guest-link').prop('disabled', true);
                $.ajax({
                type: 'POST',
                url: '{{route('send_rest_link')}}',
                data: $('#resetlinkForm').serialize(),
                success: function (data) {
            if(data.error == 1)
                {
                    document.getElementById("guest-link").style.background='#11A9C9';
                    $('#guest-link').html('Send Link');
                     Toast.fire({
                        icon: 'error',
                        title: data.message,
                      });
                }else
                {
                    $('#resetlinkForm').trigger("reset");
                    document.getElementById("guest-link").style.background='#11A9C9';
                    $('#guest-link').html('Send Link');
                        Toast.fire({
                        icon: 'success',
                        title: data.message,
                      });
                      
                }


                }
            });
    })
</script>
    </body>
</html>

