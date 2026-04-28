<!-- Modal section start -->
@php
    $pwa = getPwaData();
@endphp
<div class="pwa-popup" id="pwaInstallPopup">
    <div class="header">
        <div class="d-flex flex-wrap">
            <img src="{{isset($pwa['single']['media']->image)?getFile($pwa['single']['media']->image->driver,$pwa['single']['media']->image->path):""}}" alt="PWA Logo"
                 class="pwa-logo">
            <div class="header-text ms-3">
                <h2>{{$pwa['single']['title']??''}}</h2>
                <p>{{$pwa['single']['domain_name']??''}}</p>
            </div>
        </div>
        <button class="close-btn carousel_close_btn">×</button>
    </div>
    <p class="description">
        {{$pwa['single']['short_description']??''}}
    </p>
    <p class="sub-description">
        {{$pwa['single']['description']??''}}
    </p>
    <div class="carousel-container" id="carouselContainer">
        <div class="carousel">
            <button class="carousel-btn left" id="prevBtn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>
            <div class="carousel-content" id="carouselContent">
                @if(isset($pwa))
                    @foreach(collect($pwa['multiple'])->toArray() as $item)
                        <img src="{{isset($item['media']->image)?getFile($item['media']->image->driver,$item['media']->image->path):''}}" alt="Carousel Image">
                    @endforeach
                @endif
            </div>
            <button class="carousel-btn right" id="nextBtn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="actions">
        <button class="action-btn less" id="toggleCarousel">@lang('More')</button>
        <button class="action-btn install btn-custom" id="installButton">@lang('Install')</button>
    </div>
</div>

<style>
    /* Pwa Css */
    #pwaInstallPopup .header{
        background-color: transparent;
        border-bottom: none;
    }
    .pwa-popup {
        width: 400px;
        background-color: #1e1e1e;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        padding: 20px;
        box-sizing: border-box;
    }

    /* Header */
    .pwa-popup .header {
        display: flex;
        align-items: start;
        justify-content: space-between;
    }

    .pwa-popup .pwa-logo {
        max-width: 32px;
        max-height: 32px;
        border-radius: 6px;
    }

    .pwa-popup .header-text h2 {
        margin: 0;
        font-size: 18px;
        color: #fff;
    }

    .pwa-popup .header-text p {
        margin: 0;
        color: #fff;
        font-size: 14px;
    }

    .pwa-popup .close-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #fff;
    }

    /* Description */
    .pwa-popup .description {
        font-size: 14px;
        color: #fff;
        margin: 10px 0;
    }

    .pwa-popup .sub-description {
        font-size: 14px;
        color: #fff;
        margin-bottom: 20px;
    }

    /* Carousel */
    .pwa-popup .carousel-container {
        height: 0;
        overflow: hidden;
        opacity: 0;
        transition: height 0.5s ease, opacity 0.5s ease;

    }

    .pwa-popup .carousel-container.active {
        opacity: 1;
    }

    .pwa-popup .carousel {
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .pwa-popup .carousel-content {
        display: flex;
        transition: transform 0.5s ease;
        width: 100%;
    }

    .pwa-popup .carousel-content img {
        width: 100%;
        border-radius: 10px;
        flex-shrink: 0;
        max-height: min(45vh, 500px);
        box-shadow: rgba(0, 0, 0, 0.15) 0px 3px 10px 0px;
        scroll-snap-align: center;
        scroll-snap-stop: always;
        position: relative;
        object-fit: contain;
    }
    .pwa-popup .carousel-btn {
        background-color: #a7c7fa;
        border: none;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
    }

    .pwa-popup .carousel-btn:hover {
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 8px rgba(0, 123, 255, 0.5);
    }

    .pwa-popup .carousel-btn.left {
        left: 10px;
    }

    .pwa-popup .carousel-btn.right {
        right: 10px;
    }

    .pwa-popup .carousel-btn svg {
        width: 20px;
        height: 20px;
    }

    /* Actions */
    .pwa-popup .actions {
        display: flex;
        justify-content: space-between;
    }

    .pwa-popup .action-btn {
        padding: 8px 50px;
        font-size: 14px;
        font-weight: bold;
        font-family: sans-serif;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        display: inline-block;
        text-transform: uppercase;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
        background-color: #a7c7fa;
        transition: transform 0.2s ease;
    }

    .pwa-popup .action-btn.less {

        background-color: rgba(0, 0, 0, 0);
        color: #a7c7fa;
    }
    .pwa-popup .action-btn.less:hover{
        color: #a7c7fa;
        background-color: rgba(167,199,250, 0.1);
    }

    .pwa-popup .action-btn.less:hover {
        transform: translateY(-2px);
    }

    .pwa-popup .action-btn.install {
        background-color: #a7c7fa;
        color: #1B222C;
        box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
        font-size: 14px;
        font-weight: 500;
    }

    .pwa-popup .action-btn.install:hover {
        transform: translateY(-2px);
        background-color: #a7c7fa;
    }

    #pwaInstallPopup {
        display: none;
        margin: auto;
        position: fixed;
        top: 6%;
        left: 50%;
        transform: translateX(-50%);
        z-index: 99999;
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.8s;
    }


    @-webkit-keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top: 3%; opacity:1}
    }

    @keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top: 3%; opacity:1}
    }

    @keyframes animatebottom {
        from {bottom:-300px; opacity:0}
        to {bottom: 1%; opacity:1}
    }
    @media (max-width: 575px) {
        #pwaInstallPopup {
            top: auto;
            bottom: 1% !important;
            width: 95%;
            z-index: 9999;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 0.4s;
            animation-name: animatebottom;
            animation-duration: 0.8s;
        }
    }
    @media (max-width: 360px) {
        .pwa-popup .action-btn{
            padding: 8px 35px;
        }
    }
</style>
<script>
    const popup = document.getElementById('pwaInstallPopup');
    const showButton = document.getElementById('toggleCarousel');
    const closeButton = document.querySelector('.close-btn');

    document.querySelector('.carousel_close_btn').addEventListener('click',()=>{
        var $modal = $("#pwaInstallPopup");
        $modal.hide();

        localStorage.setItem('pwa_install','not_install');
    })
    function showPwa() {
        $("#pwaInstallPopup").show();
    }


    const carouselContainer = document.getElementById('carouselContainer');
    const toggleCarouselBtn = document.getElementById('toggleCarousel');
    const carouselContent = document.getElementById('carouselContent');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let currentIndex = 0;

    // Toggle visibility of the carousel with dynamic height
    toggleCarouselBtn.addEventListener('click', () => {
        if (carouselContainer.classList.contains('active')) {
            // Collapse the carousel
            carouselContainer.style.height = '0';
            carouselContainer.classList.remove('active');
            toggleCarouselBtn.textContent = 'More';
        } else {
            // Expand the carousel to 80% of the viewport height
            const carouselHeight = window.innerHeight * 0.5;
            carouselContainer.style.height = `${carouselHeight}px`;
            carouselContainer.classList.add('active');
            toggleCarouselBtn.textContent = 'Less';
        }
    });

    // Carousel navigation logic
    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentIndex < carouselContent.children.length - 1) {
            currentIndex++;
            updateCarousel();
        }
    });

    function updateCarousel() {
        const offset = -currentIndex * 100; // Move carousel by 100% per slide
        carouselContent.style.transform = `translateX(${offset}%)`;
    }




    window.addEventListener('load', () => {
        const isFirefox = navigator.userAgent.includes('Firefox');
        let deferredPrompt = null;

        if (!isFirefox && 'BeforeInstallPromptEvent' in window) {
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;

                const installButton = document.getElementById('installButton');
                let pwaStatus = localStorage.getItem('pwa_install');
                if (pwaStatus !== 'not_install' && pwaStatus !== 'install') {
                    $("#pwaInstallPopup").show(); // Show your modal
                }

                installButton.addEventListener('click', async () => {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    if (outcome === 'accepted') {
                        localStorage.setItem('pwa_install', 'install');
                    } else {
                        localStorage.setItem('pwa_install', 'not_install');
                    }
                });
            });
        } else if (isFirefox) {
            const installButton = document.getElementById('installButton');
            let pwaStatus = localStorage.getItem('pwa_install');
            if (pwaStatus !== 'not_install' && pwaStatus !== 'install') {
                $("#pwaInstallPopup").show(); // Show your modal with Firefox-specific instructions
            }

            installButton.addEventListener('click', () => {
                // Show custom instructions for Firefox
                alert("To install the app on Firefox, use a mobile device. Open the browser menu (three dots in the top-right corner) and select 'Add to Home Screen'.");
                localStorage.setItem('pwa_install', 'install');
            });
        }
    });


</script>
