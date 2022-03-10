<script src="{{asset('public/assets/js/jquery-3.1.1.js')}}"></script>
<script src="{{asset('public/assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('public/assets/js/custom.js')}}"></script>
<script src="{{asset('public/assets/js/user.js')}}"></script>
<script src="{{asset('public/assets/js/jQuerySimpleCounter.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>
  // $(document).ready(function(){

  //     $end  = $('#servicePrice').val();
  //     $('#count-example').jQuerySimpleCounter({
  //
  //         // start number
  //         start:  150,
  //
  //         // end number
  //
  //         end:    $end-1,
  //
  //         // easing effect
  //         easing: 'swing',
  //
  //         // duration time in ms
  //         duration: 800,
  //
  //         // callback function
  //         complete: ''
  //
  //     });
  //
  // });
    $('.user-name').click(function(){
        $('.sub-dropdwon-manu').slideToggle();
    });
    function myFunction(x) {
        if (x.matches) { // If media query matches
            $('.manu-btn').click(function(){
                $('body').addClass('manu-open');
            });
            $('.close-sidemanu').click(function(){
                $('body.manu-open').removeClass('manu-open');
            });
        } else {

        }
    }

    var x = window.matchMedia("(max-width: 767px)")
    myFunction(x) // Call listener function at run time
    x.addListener(myFunction) // Attach listener function on state changes

    $('.manu-btn.main-mobile-btns').click(function(){
        console.log(x.matches);
        $('body').addClass('manu-open');
    });
        $('.manu-btn1.main-mobile-btns1').click(function(){
        console.log();
        if(x.matches)
        {$('body').addClass('manu-open');}else{}

    });
    $('.close-sidemanu').click(function(){
        $('body.manu-open').removeClass('manu-open');
    });
    $(".down-arrows").click(function() {
        $('html, body').animate({
            scrollTop: $(".service-section").offset().top
        }, 2000);
    });


</script>
<!-- Start of ChatBot (www.chatbot.com) code -->

<!-- End of ChatBot code -->
<script>
    function getLocation() {
//        alert('hittt');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(changeposition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function changeposition(position) {

        $('.lat').val(position.coords.latitude);
        $('.lng').val(position.coords.longitude);
       window.location.href = '/gassprices/'+position.coords.latitude+','+position.coords.longitude;

    }

</script>


