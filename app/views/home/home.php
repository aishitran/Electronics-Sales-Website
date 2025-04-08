<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clone T-Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<!--Danh mục-->
<div class="container mt-4 px-3">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar rounded shadow-sm">
                <h5><i class="bi bi-list fs-4 mt-4 mb-3"></i> Danh mục</h5>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="/index.php?action=viewCategory&id=1">Bàn phím</a> 
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    <li class="list-group-item">
                        <a href="/index.php?action=viewCategory&id=2">Màn hình</a> 
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    <li class="list-group-item">
                        <a href="/index.php?action=viewCategory&id=3">Laptop Gaming, văn phòng</a> 
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    <li class="list-group-item">
                        <a href="/index.php?action=viewCategory&id=4">Gaming Gear</a> 
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    <li class="list-group-item">
                        <a href="/index.php?action=viewCategory&id=5">Máy tính để bàn</a> 
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    <li class="list-group-item">
                        <a href="/index.php?action=viewCategory&id=6">Linh kiện máy tính</a> 
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    <li class="list-group-item">
                        <a href="/index.php?action=viewCategory&id=7">Linh kiện khác</a> 
                        <i class="bi bi-chevron-right"></i>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="slider-container">
                <div class="slider-images">
                    <img src="/app/images/1st-1200x300.jpg" alt="Image 1" data-slide="0" class="slide active">
                    <img src="/app/images/2nd-1200x300.jpg" alt="Image 2" data-slide="1" class="slide">
                    <img src="/app/images/3rd-1200x300.jpg" alt="Image 3" data-slide="2" class="slide">
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
        <div>
            <a href="/index.php?action=viewCategory&id=3">
                <i class="fas fa-laptop"></i>
                <p>Laptop</p>
            </a>
        </div>
        <div>
            <a href="/index.php?action=viewCategory&id=1">
                <i class="fas fa-keyboard"></i>
                <p>Bàn phím</p>
            </a>
        </div>
        <div>
            <a href="/index.php?action=viewCategory&id=2">
                <i class="fas fa-display"></i>
                <p>Màn hình</p>
            </a>
        </div>
    </div>

    <div class="container border rounded p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold fs-4"><i class="bi bi-list"></i> Danh Sách Sản Phẩm</h5>
            <a href="/index.php?action=viewAllProduct" class="text-decoration-none">Xem tất cả -></a>
        </div>
        <div class="d-flex flex-wrap justify-content-start gap-3">
            <?php 
            $productCount = 0;
            $hasProducts = false;
            foreach ($products as $product): 
                // Skip products with zero quantity
                if ($product['SoLuong'] <= 0) continue;
                $hasProducts = true;
                if ($productCount >= 8) break; // Increased from 4 to 8 products
                $productCount++;
            ?>
                <div class="col-md-3" style="flex: 0 0 calc(25% - 12px); max-width: calc(25% - 12px);">
                    <div class="card h-100">
                        <?php if (!empty($product['HinhAnh']) && file_exists('public/' . $product['HinhAnh'])): ?>
                            <img src="/public/<?= htmlspecialchars($product['HinhAnh']) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['TenSanPham']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/200" class="card-img-top product-image" alt="No Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <a href="/index.php?action=viewProduct&id=<?= htmlspecialchars($product['MaSanPham'] ?? '') ?>" class="text-decoration-none">
                                <?= htmlspecialchars($product['TenSanPham'] ?? 'Sản phẩm không có tên') ?>
                            </a>
                            <p class="card-text"><?= htmlspecialchars($product['MoTa']) ?></p>
                            <p class="card-text"><strong>Giá:</strong> <?= number_format($product['Gia'], 0, ',', '.') ?> VND</p>
                            <div class="d-flex gap-2">
                                <a href="/index.php?action=viewProduct&id=<?= $product['MaSanPham'] ?>" class="btn btn-primary">Xem Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; 
            if (!$hasProducts) {
                echo "<p>Không có sản phẩm nào để hiển thị.</p>";
            }
            ?>
        </div>
    </div>

    <div class="container border rounded p-3 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold fs-4"><i class="bi bi-heart"></i> Bạn có thể thích ?</h5>
            <a href="/index.php?action=viewAllProduct" class="text-decoration-none">Xem tất cả -></a>
        </div>
        <!-- Category Buttons -->
        <div class="category-buttons mt-2 d-flex gap-2">
            <button class="btn category-btn active" data-category="1">Bàn phím</button>
            <button class="btn category-btn" data-category="2">Màn hình</button>
            <button class="btn category-btn" data-category="3">Laptop</button>
        </div>
        <div class="d-flex flex-wrap justify-content-start gap-3 mt-3" id="youMayLikeProducts">
            <?php 
            $productCount = 0;
            $categoryCounts = [];
            $hasProducts = false;
            foreach ($products as $product): 
                // Skip products with zero quantity
                if ($product['SoLuong'] <= 0) continue;
                $hasProducts = true;
                $categoryId = $product['MaDanhMuc'];
                if (!isset($categoryCounts[$categoryId])) {
                    $categoryCounts[$categoryId] = 0;
                }
                
                // Only include up to 4 products per category
                if ($categoryCounts[$categoryId] < 4) {
                    $categoryCounts[$categoryId]++;
                    $productCount++;
            ?>
                <div class="col-md-3" style="flex: 0 0 calc(25% - 12px); max-width: calc(25% - 12px);" data-category="<?= htmlspecialchars($product['MaDanhMuc'] ?? '') ?>">
                    <div class="card h-100">
                        <?php if (!empty($product['HinhAnh']) && file_exists('public/' . $product['HinhAnh'])): ?>
                            <img src="/public/<?= htmlspecialchars($product['HinhAnh']) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['TenSanPham']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/200" class="card-img-top product-image" alt="No Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <a href="/index.php?action=viewProduct&id=<?= htmlspecialchars($product['MaSanPham'] ?? '') ?>" class="text-decoration-none">
                                <?= htmlspecialchars($product['TenSanPham'] ?? 'Sản phẩm không có tên') ?>
                            </a>
                            <p class="card-text"><?= htmlspecialchars($product['MoTa']) ?></p>
                            <p class="card-text"><strong>Giá:</strong> <?= number_format($product['Gia'], 0, ',', '.') ?> VND</p>
                            <div class="d-flex gap-2">
                                <a href="/index.php?action=viewProduct&id=<?= $product['MaSanPham'] ?>" class="btn btn-primary">Xem Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            endforeach; 
            if (!$hasProducts) {
                echo "<p>Không có sản phẩm nào để hiển thị.</p>";
            }
            ?>
        </div>
    </div>
</div>

<!--Script chuyển động slider sản phẩm-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dots = document.querySelectorAll('.slider-dots .dot');
        const slides = document.querySelectorAll('.slider-images .slide');
        const buttons = document.querySelectorAll('.category-btn');
        const products = document.querySelectorAll('#youMayLikeProducts .col-md-3');
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

        // Filter products based on selected category
        function filterProducts(selectedCategory) {
            console.log("Filtering products for category:", selectedCategory);
            let visibleCount = 0;
            
            products.forEach(product => {
                const productCategory = product.getAttribute('data-category');
                console.log("Product category:", productCategory, "Selected category:", selectedCategory);
                
                if (productCategory === selectedCategory) {
                    product.style.display = 'block';
                    visibleCount++;
                    console.log("Product visible:", product.querySelector('a').textContent.trim());
                } else {
                    product.style.display = 'none';
                }
            });
            
            // If no products are visible for the selected category, show a message
            if (visibleCount === 0) {
                const noProductsMessage = document.createElement('div');
                noProductsMessage.className = 'col-12 text-center mt-3';
                noProductsMessage.textContent = 'Không có sản phẩm nào trong danh mục này.';
                document.getElementById('youMayLikeProducts').appendChild(noProductsMessage);
            }
        }

        // Handle category button clicks
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                buttons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                // Filter products based on selected category
                const selectedCategory = this.getAttribute('data-category');
                filterProducts(selectedCategory);
            });
        });

        // Start auto-sliding
        startAutoSlide();
        
        // Initialize with the first category (Bàn phím)
        filterProducts('1');
    });
</script>
</body>
</html>
