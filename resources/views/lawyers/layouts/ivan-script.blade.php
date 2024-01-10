<script src="/lawyers/scripts/script.js"></script>
<script src="/lawyers/scripts/popup.js"></script>
<script>
    document.querySelectorAll('.nav-ul > li').forEach(item => {
        selectRow(item);
    });

    $(document).ready(function() {
        $(".specialists-carousel").owlCarousel({
            items: 1,
            dots: true,
            nav: true,
            margin: 16,
            autoplay: true,
            autoplayTimeout: 4000,
            loop: true,
            navElement: 'img src="/lawyers/images/icons/arrow-icon-gray.svg"',

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
</script>