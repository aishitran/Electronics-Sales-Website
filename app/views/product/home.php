<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clone T-Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
    /*Banner*/
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

    /*Slider*/
    .slider-container {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .slider-images {
        position: relative;
        width: 80%;
        height: 100%; /* Đặt chiều cao cố định */
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
        position: absolute;
        bottom: 10px;
        left: 50%;
        border-radius: 50%;
        display: flex;
        justify-content: center;
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

    .sidebar {
        background-color:#2E1A16; 
        padding: 10px;
        border-radius: 5px;
    }

    .sidebar h5 {
        color: white;
        font-weight: bold;
    }

    .sidebar .list-group-item a {
        text-decoration: none;
        color: #333;
    }
    .sidebar .list-group-item a:hover {
            color: #ff6600;
    }

    /*Image nhỏ bên hông*/
    .promo-container{
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .promo-container a img{
        width: 100%;
        border-radius: 10px;
    }

    /*Danh mục icons*/
    .category-bar{
        top: -170px;
        max-width: 50%;
        margin: 15px auto ;
        padding: 15px 10px;
        background-color:rgb(164, 231, 120);
        border-radius: 10px;
        display: flex;
        justify-content: center;
        gap: 30px;
        position: relative;
    }

    .category-bar div{
        text-align: center;
    }

    .category-bar i{
        font-size: 24px;
    }

    .category-bar p{
        font-size: 14px;
        margin-top: 5px;
    }

    
    
</style>
<body>

<!--Danh mục-->
<div class="container mt-4 px-3">
    <div class="row">
        <div class="col-md-3 ">
            <div class="sidebar rounded shadow-sm">
                    <h5><i class="bi bi-list"></i> Danh mục</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="#">Bàn phím</a> <i class="bi bi-chevron-right"></i></li>
                        <li class="list-group-item"><a href="#">Màn hình</a> <i class="bi bi-chevron-right"></i></li>
                        <li class="list-group-item"><a href="#">Laptop Gaming, văn phòng</a> <i class="bi bi-chevron-right"></i></li>
                        <li class="list-group-item"><a href="#">Gaming Gear</a> <i class="bi bi-chevron-right"></i></li>
                        <li class="list-group-item"><a href="#">Máy tính để bàn</a> <i class="bi bi-chevron-right"></i></li>
                        <li class="list-group-item"><a href="#">Linh kiện máy tính</a> <i class="bi bi-chevron-right"></i></li>
                        <li class="list-group-item"><a href="#">Linh kiện khác</a> <i class="bi bi-chevron-right"></i></li>
                    </ul>
            </div>
            <!--Sản phẩm nổi bật -->
            <!--<h3 class="featured-products-title mt-4">Sản phẩm nổi bật <i class="bi bi-fire text-danger"></i></h3>-->
        </div>

        <div class="col-md-6">
            <!--<h2>Không mua ở đây thì mua ở đâu?</h2>-->
            <div class="slider-container">
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

        <div class="col-md-3 promo-container">
            <a href="https://discord.com" target="_blank"><img src="https://i.pinimg.com/736x/aa/b3/c1/aab3c1a8641fef428542e38ff59743a7.jpg" alt="Tham gia discord"></a>
            <a href="#"><img src="https://i.pinimg.com/736x/e8/4b/79/e84b793b51a0b90c02b16453d653ee67.jpg" alt="2025"></a>
        </div>
    </div>

        <!--Danh mục nhỏ dưới slider -->
        <div class="category-bar">
            <div><i class = "fas fa-laptop"><p>Laptop</p></i></div>
            <div><i class = "fas fa-keyboard"> <p>Bàn phím</p></i></div>
            <div><i class = "fas fa-display"> <p>Màn hình</p></i></div>
        </div>
</div>

<div class="banner-container">
    <img src="https://www.istore.com.ng/cdn/shop/files/MacBook_Air_M3_Now_Available_Desktop_Banner_2000x.jpg?v=1711115634" alt="MacBook Air" class="banner-image">
</div>

<!--Script chuyển động slider sản phẩm-->
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
</body>
</html>
