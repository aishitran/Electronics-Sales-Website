<style>
    .home-image {
        max-width: 400px;
        width: 70%;
        height: auto;
        margin: 20px auto;
        display: block;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        object-fit: cover;
    }

    .banner-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    .banner-image {
        width: 80%;
        max-width: 1200px;
        border-radius: 10px;
    }

    .slider-images {
        position: relative;
        width: 100%;
        height: 300px; /* Đặt chiều cao cố định */
        overflow: hidden;
    }

    .slider-images .slide {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .slider-images .slide.active {
        opacity: 1;
    }

    .slider-dots {
        text-align: center;
        margin-top: 20px;
    }

    .slider-dots .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #ddd;
        display: inline-block;
        margin: 0 5px;
        cursor: pointer;
    }

    .slider-dots .dot.active {
        background-color: #333;
    }
</style>

<div class="container mt-4 px-3">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar">
                <h5><i class="bi bi-list"></i> Danh mục</h5>
                <ul class="list-group">
                    <li class="list-group-item"><a href="#">Bàn phím</a></li>
                    <li class="list-group-item"><a href="#">Màn hình</a></li>
                    <li class="list-group-item"><a href="#">Laptop Gaming, văn phòng</a></li>
                    <li class="list-group-item"><a href="#">Gaming Gear</a></li>
                    <li class="list-group-item"><a href="#">Máy tính để bàn</a></li>
                    <li class="list-group-item"><a href="#">Linh kiện máy tính</a></li>
                    <li class="list-group-item"><a href="#">Linh kiện khác</a></li>
                </ul>
            </div>
            <!--Sản phẩm nổi bật -->
            <h3 class="featured-products-title mt-4">Sản phẩm nổi bật <i class="bi bi-fire text-danger"></i></h3>
        </div>
        
        <div class="col-md-9">
            <h2>Trang chủ</h2>
            <div class="slider-images">
                <img src="https://i.imgur.com/eOrOjIv.png" alt="Image 1" data-slide="0" class="slide active">
                <img src="https://i.imgur.com/wBYX31O.png" alt="Image 2" data-slide="1" class="slide">
                <img src="https://i.imgur.com/qaoFYfj.png" alt="Image 3" data-slide="2" class="slide">
            </div>
            <div class="slider-dots">
                <span class="dot active" data-slide="0"></span>
                <span class="dot" data-slide="1"></span>
                <span class="dot" data-slide="2"></span>
            </div>
        </div>
    </div>
</div>

<div class="banner-container">
    <img src="https://www.istore.com.ng/cdn/shop/files/MacBook_Air_M3_Now_Available_Desktop_Banner_2000x.jpg?v=1711115634" alt="MacBook Air" class="banner-image">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dots = document.querySelectorAll('.slider-dots .dot');
        const slides = document.querySelectorAll('.slider-images .slide');
        let currentSlide = 0;
        let slideInterval;

        function changeSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                dots[i].classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                    dots[i].classList.add('active');
                }
            });
            currentSlide = index;
        }

        function nextSlide() {
            let nextIndex = (currentSlide + 1) % slides.length;
            changeSlide(nextIndex);
        }

        function startAutoSlide() {
            slideInterval = setInterval(nextSlide, 3000); // Chuyển slide mỗi 3 giây
        }

        function stopAutoSlide() {
            clearInterval(slideInterval);
        }

        // Xử lý click vào dot
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                stopAutoSlide();
                const slideIndex = parseInt(this.getAttribute('data-slide'));
                changeSlide(slideIndex);
                startAutoSlide();
            });
        });

        // Bắt đầu tự động chạy slider
        startAutoSlide();
    });
</script>
