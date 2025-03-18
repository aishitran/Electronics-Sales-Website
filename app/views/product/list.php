<?php include 'app/views/shares/header.php'; ?> 

<div class="container mt-4 px-3"> 
    <h1 class="mb-4">Danh sách sản phẩm</h1> 

    <div class="row"> 
        <?php foreach ($products as $product): ?> 
            <div class="col-md-4 mb-4"> 
                <div class="card h-100"> 
                    <?php if ($product->image): ?> 
                        <img src="/dacnpm/<?php echo $product->image; ?>" alt="Product Image" class="card-img-top img-fluid" style="max-height: 200px; object-fit: cover;">
                    <?php endif; ?>

                    <!-- Nhãn giảm giá -->
                    <span class="discount-badge">Giảm 11%</span>
                    <span class="installment-badge">Trả góp 0%</span>

                    <!-- Hình ảnh sản phẩm -->
                    <?php if ($product->image): ?> 
                        <img src="/dacnpm/<?php echo $product->image; ?>" alt="Product Image" class="card-img-top img-fluid product-image">
                    <?php endif; ?>

                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <a href="/dacnpm/Product/show/<?php echo $product->id; ?>" class="text-dark text-decoration-none">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <!-- Giá sản phẩm -->
                        <p class="card-price">
                            <span class="new-price"><?php echo number_format($product->price, 0, ',', '.'); ?>đ</span>
                            <span class="old-price"><?php echo number_format($product->old_price, 0, ',', '.'); ?>đ</span>
                        </p>
                    </div>
                    
                    <!-- Mô tả ngắn -->
                    <p class="product-desc">
                            Không phí chuyển đổi khi trả góp 0% qua thẻ tín dụng kỳ hạn 3-6 tháng
                    </p>
                    
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <a href="#" class="wishlist"><i class="bi bi-heart"></i> Yêu thích</a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?> 
