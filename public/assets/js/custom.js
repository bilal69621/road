$(document).ready(function(){
    // Team Slider
    $('.great-team.owl-carousel').owlCarousel({
            loop: true,
            margin: 20,
            nav: false,
            autoplay: true,
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
});