$(document).ready(function(){
    $(".specialists-carousel").owlCarousel({
        items: 1,
        dots: true,
        nav: true,
        margin: 16,
        autoplay: true,
        autoplayTimeout: 4000,
        loop: true,
        navElement: 'img src="images/icons/arrow-icon-gray.svg"',

        responsive: {
            1280 : {
                items: 3,
            },
            980 : {
                items: 2,
            }
        }
    });
});