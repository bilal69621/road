

$( "#loginform" ).on( "submit", function( event ) {
  event.preventDefault();
  $formdata = $( this ).serialize();
  $('#loginbutton').html('<i class="fa fa-spinner fa-spin"></i>Loading');
  $.ajax({
  url: "uservarify",
  type:'POST',
  data:$formdata,
  dataType: 'JSON',
  success: function(data){
    if(data.error == 1)
    {
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
          Toast.fire({
            icon: 'error',
            title: data.message,
          })
          $('#loginbutton').html('Login');
    }else{
        
       location.reload();
    }
  }
});
});

// ajax call for getting years // bilal

$('.car-column').on('click',function(){
   $.ajax({
     url : 'getyears',
     type:'get',
    success: function(data){
        $('#yearSelect').html(data.years);
  },
  error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
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
          Toast.fire({
            icon: 'error',
            title: msg,
          })

    },
   });
});
// ajax for geting make with respect to years ----- bilal-----

$('#yearSelect').on('change',function(){

    $selectedYear = $('#yearSelect').val();
   $.ajax({
     url : 'getmake',
     type:'get',
     data:{year:$selectedYear},
    success: function(data){
        $('#selectMake').html(data.makes);
        $( "#selectMake" ).prop( "disabled", false );
  },
  error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
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
          Toast.fire({
            icon: 'error',
            title: msg,
          })

    },
   });
});
//---------------//

//------ajax for getting modal by year and make-------------------//

$('#selectMake').on('change',function(){
    $selectedYear = $('#yearSelect').val();
    $selectedmake = $('#selectMake').val();
   $.ajax({
     url : 'getmodal',
     type:'get',
     data:{year:$selectedYear,make:$selectedmake},
    success: function(data){
        $('#modalSelect').html(data.modals);
        $( "#modalSelect" ).prop( "disabled", false );
  },
  error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
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
          Toast.fire({
            icon: 'error',
            title: msg,
          })

    },
   });
});


//---ajax for addin new user car ----//

$( "#addCarForm" ).on( "submit", function( event ) {
  event.preventDefault();
  $formdata = $( this ).serialize();
  console.log($formdata);
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
        $.ajax({
        url: "addusercar",
        type:'POST',
        data:$formdata,
        dataType: 'JSON',
        success: function(data){
          if(data.error == 1)
          {
                Toast.fire({
                  icon: 'error',
                  title: data.message,
                })
          }else{
              window.location.href = 'dashboard';
          }
        }
      });
});

/// sawl confermation for delete user car ... bilal///

    function archiveFunction($carId) {
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
//          Swal.fire(
//            'Deleted!',
//            'Your file has been deleted.',
//            'success'
//          )
    $('#carId').val($carId);
    $("#deletecarForm").submit()
        }
      })
    }

        $( "#deletecarForm" ).on( "submit", function( event ) {
      event.preventDefault();
      $formdata = $( this ).serialize();
      $carId = $('#carId').val();
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
            $.ajax({
            url: "deletecar",
            type:'POST',
            data:$formdata,
            dataType: 'JSON',
            success: function(data){
              if(data.error == 1)
              {
                    Toast.fire({
                      icon: 'error',
                      title: data.message,
                    })
              }else{
                  $("#carDiv_"+$carId).css("display", "none");
                   Toast.fire({
                      icon: 'success',
                      title: data.message,
                    })
              }
            }
          });
    });

///// user profile edit functionality ajax ...bilal ...////

        $( "#editProfileUser" ).on( "submit", function( event ) {
  event.preventDefault();
  $formdata = $( this ).serialize();
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
  $.ajax({
  url: $('#routeupdate').val(),
  type:'POST',
  data:$formdata,
  dataType: 'JSON',
  success: function(data){
    if(data.error == 1)
    {

          Toast.fire({
            icon: 'error',
            title: data.message,
          })
    }else{
       Toast.fire({
            icon: 'success',
            title: data.message,
          })
    }
  }
});
});

$(".toggle-password").click(function() {
    alert('hitttt');
//  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});

    function showhide($class)
    {
        if ($('.'+$class).attr("type") == "password") {
        $('.'+$class).attr("type", "text");
        } else {
          $('.'+$class).attr("type", "password");
        }
    }

    $('.o_pass').on('change',function(){
        $oldPassword = $(this).val();
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
        $.ajax({
        url: 'checkpassword',
        type:'get',
        data:{pass:$oldPassword},
        dataType: 'JSON',
        success: function(data){
          if(data.error == 1)
          {
               $('.n_pass').prop( "disabled", true );
                $('.c_n_pass').prop( "disabled", true );
                Toast.fire({
                  icon: 'error',
                  title: data.message,
                })
                document.getElementById("oldpassword").focus();
          }else{
               $('.n_pass').prop( "disabled", false );
                $('.c_n_pass').prop( "disabled", false );
          }
        }
      });
    })



    $( document ).ready(function() {
    $('.n_pass').prop( "disabled", true );
     $('.c_n_pass').prop( "disabled", true );
    })



    $('.saveChanges').on('click',function(){
        $n_pass = $('.n_pass').val();
        $c_n_pass = $('.c_n_pass').val();
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
        if($n_pass == $c_n_pass)
        {
                    $.ajax({
        url: 'updatepassword',
        type:'get',
        data:{pass:$n_pass},
        dataType: 'JSON',
        success: function(data){
          if(data.error == 1)
          {
                Toast.fire({
                  icon: 'error',
                  title: data.message,
                })
          }else{
                  Toast.fire({
                  icon: 'success',
                  title: data.message,
                });
                $('.c_n_pass').val('');
                $('.n_pass').val('');
                $('.o_pass').val('');
          }
        }
      });
        }else{
              Toast.fire({
                  icon: 'error',
                  title: 'Passwords are not same',
                })
        }
    })


        $( "#createCard" ).on( "submit", function( event ) {
      event.preventDefault();
      $formdata = $( this ).serialize();
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
      $.ajax({
      url: $('#createcard').val(),
      type:'POST',
      data:$formdata,
      dataType: 'JSON',
      success: function(data){
        if(data.error == 1)
        {

              Toast.fire({
                icon: 'error',
                title: data.message,
              })
        }else{
           Toast.fire({
                icon: 'success',
                title: data.message,
              })
        }
      }
    });
    });

        // delete card confermation ///..bilal

function DeleteCardComfermation($cardId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
//          Swal.fire(
//            'Deleted!',
//            'Your file has been deleted.',
//            'success'
//          )
            $('#cardId').val($cardId);
            $("#deleteCardForm").submit()
        }
    })
}
$( "#deleteCardForm" ).on( "submit", function( event ) {
    event.preventDefault();
    $formdata = $( this ).serialize();
    $cardId = $('#cardId').val();
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
    $.ajax({
        url: "deletecard",
        type:'POST',
        data:$formdata,
        dataType: 'JSON',
        success: function(data){
            if(data.error == 1)
            {
                Toast.fire({
                    icon: 'error',
                    title: data.message,
                })
            }else{
                $("#card_"+$cardId).css("display", "none");
                Toast.fire({
                    icon: 'success',
                    title: data.message,
                })
            }
        }
    });
});

    $(document).ready(function() {
    $('#profile-imgs').change(function(){
        var file_data = $('#profile-imgs').prop('files')[0];
        var form_data = new FormData();
        var url = $('#routeupdateP').val();
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
        form_data.append('file', file_data);
        $.ajax({
            url: url,
            type: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
            processData:false,
            success: function(data){
               var imagePath =  $('#imagePath').val();
               var imageUrl = "url("+imagePath+"/"+data.imageName+")";
               document.getElementById('profileImage').style.backgroundImage=imageUrl;
               document.getElementById('userHeadImage').style.backgroundImage=imageUrl;
                    Toast.fire({
                    icon: 'success',
                    title: data.message,
                })
            }
        });
    });
});




