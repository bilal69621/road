<script>


$( "#loginform" ).on( "submit", function( event ) {
  event.preventDefault();
  $formdata = $( this ).serialize();
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
    }else{
        window.location.href = 'userdashboard';
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
              window.location.href = '{{route('udashboard')}}';
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
            alert('hittt');
  event.preventDefault();
  $formdata = $( this ).serialize();
  $.ajax({
  url: {{route('updateUserProfile')}}",
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
    }else{
        window.location.href = 'userdashboard';
    }
  }
});
});




</script>

