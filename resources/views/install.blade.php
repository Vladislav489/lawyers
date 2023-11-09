<html>
    <head>
        <style>
            .pace.pace-inactive {
                display: none;
            }

            .pace {
                -webkit-pointer-events: none;
                pointer-events: none;

                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;

                z-index: 2000;
                position: fixed;
                height: 300px;
                width: 500px;
                margin: auto;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }

            .pace .pace-progress {
                z-index: 2000;
                position: absolute;
                height: 300px;
                width: 500px;

                -webkit-transform: translate3d(0, 0, 0) !important;
                -ms-transform: translate3d(0, 0, 0) !important;
                transform: translate3d(0, 0, 0) !important;
            }

            .pace .pace-progress:before {
                content: attr(data-progress-text);
                text-align: center;
                color: #fff;
                background: #29d;
                border-radius: 50%;
                font-family: "Helvetica Neue", sans-serif;
                font-size: 40px;
                font-weight: 100;
                line-height: 1;
                padding: 20% 0 7px;
                width: 50%;
                height: 40%;
                left: 105px;
                top:40px;
                margin: 10px 0 0 30px;
                display: block;
                z-index: 999;
                position: absolute;
            }

            .pace .pace-activity {
                font-size: 15px;
                line-height: 1;
                z-index: 2000;
                position: absolute;
                height: 300px;
                width: 500px;

                display: block;
                -webkit-animation: pace-theme-center-atom-spin 2s linear infinite;
                -moz-animation: pace-theme-center-atom-spin 2s linear infinite;
                -o-animation: pace-theme-center-atom-spin 2s linear infinite;
                animation: pace-theme-center-atom-spin 2s linear infinite;
            }

            .pace .pace-activity {
                border-radius: 50%;
                border: 10px solid #29d;
                content: ' ';
                display: block;
                position: absolute;
                top: 0;
                left: 0;
                height: 300px;
                width: 500px;
            }

            .pace .pace-activity:after {
                border-radius: 50%;
                border: 10px solid #29d;
                content: ' ';
                display: block;
                position: absolute;
                top: -5px;
                left: -5px;
                height: 300px;
                width: 500px;

                -webkit-transform: rotate(60deg);
                -moz-transform: rotate(60deg);
                -o-transform: rotate(60deg);
                transform: rotate(60deg);
            }

            .pace .pace-activity:before {
                border-radius: 50%;
                border: 10px solid #29d;
                content: ' ';
                display: block;
                position: absolute;
                top: -5px;
                left: -5px;
                height: 300px;
                width: 500px;

                -webkit-transform: rotate(120deg);
                -moz-transform: rotate(120deg);
                -o-transform: rotate(120deg);
                transform: rotate(120deg);
            }

            @-webkit-keyframes pace-theme-center-atom-spin {
                0%   { -webkit-transform: rotate(0deg) }
                100% { -webkit-transform: rotate(359deg) }
            }
            @-moz-keyframes pace-theme-center-atom-spin {
                0%   { -moz-transform: rotate(0deg) }
                100% { -moz-transform: rotate(359deg) }
            }
            @-o-keyframes pace-theme-center-atom-spin {
                0%   { -o-transform: rotate(0deg) }
                100% { -o-transform: rotate(359deg) }
            }
            @keyframes pace-theme-center-atom-spin {
                0%   { transform: rotate(0deg) }
                100% { transform: rotate(359deg) }
            }
        </style>
    </head>
    <body>
        <h1 style="text-align: center">Идет развертывание на сервере</h1>
        <div class="pace pace-active">
            <div class="pace-progress" data-progress="0" data-progress-text="0%" style="-webkit-transform: translate3d(50%, 0px, 0px); -ms-transform: translate3d(50%, 0px, 0px); transform: translate3d(50%, 0px, 0px);">
                <div class="pace-progress-inner">

                </div>
            </div>
            <div class="pace-activity"></div>
        </div>
    </body>
    <script>
        var proggresBar = document.querySelector('.pace').querySelector('.pace-progress')
        var interval = setInterval(function () {
            var intProgress = parseInt(proggresBar.getAttribute('data-progress'));
            proggresBar.setAttribute('data-progress',intProgress+=1);
            proggresBar.setAttribute('data-progress-text',(intProgress+=1)+'%');
            if(intProgress == 100){
                clearInterval(interval);
                location.href="/";
            }
        },200)
    </script>
</html>