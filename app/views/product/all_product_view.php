<?php
$pageTitle = 'Tất Cả Sản Phẩm';
?>
<div class="container mt-4">
    <div class="row">
        <!-- Left Sidebar (Miscellaneous Column) -->
        <div class="col-md-3">
            <div class="sidebar rounded shadow-sm p-3">
                <h5><i class="bi bi-funnel"></i> Bộ lọc</h5>

                <!-- Sắp xếp sản phẩm -->
                <div class="mb-3">
                    <h6>Sắp xếp</h6>
                    <select class="form-select" id="sortSelect" onchange="applySorting()">
                        <option value="">Mặc định</option>
                        <option value="name_asc">Tên A-Z</option>
                        <option value="name_desc">Tên Z-A</option>
                        <option value="price_asc">Giá thấp đến cao</option>
                        <option value="price_desc">Giá cao xuống thấp</option>
                    </select>
                </div>

                <!-- Lọc theo giá -->
                <div>
                    <h6>Chọn mức giá</h6>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="javascript:void(0);" onclick="applyFilter('under100')" class="text-decoration-none text-dark">Giá dưới 100.000đ</a>
                        </li>
                        <li class="list-group-item">
                            <a href="javascript:void(0);" onclick="applyFilter('100-200')" class="text-decoration-none text-dark">100.000đ - 200.000đ</a>
                        </li>
                        <li class="list-group-item">
                            <a href="javascript:void(0);" onclick="applyFilter('200-400')" class="text-decoration-none text-dark">200.000đ - 400.000đ</a>
                        </li>
                        <li class="list-group-item">
                            <a href="javascript:void(0);" onclick="applyFilter('400-700')" class="text-decoration-none text-dark">400.000đ - 700.000đ</a>
                        </li>
                        <li class="list-group-item">
                            <a href="javascript:void(0);" onclick="applyFilter('above700')" class="text-decoration-none text-dark">Giá trên 700.000đ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content (Right Column - Products of Selected Category) -->
        <div class="col-md-9">
            <div class="border rounded p-3 mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold fs-4"><i class="bi bi-list"></i> Tất Cả Sản Phẩm</h5>
                </div>
                <div class="d-flex flex-wrap justify-content-start gap-3">
                    <?php 
                    if (!empty($products)) {
                        foreach ($products as $product): 
                    ?>
                        <div class="col-md-4" style="flex: 0 0 calc(33.33% - 12px); max-width: calc(33.33% - 12px);">
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
                                    <a href="/index.php?action=viewProduct&id=<?= $product['MaSanPham'] ?>" class="btn btn-primary">Xem Chi Tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach; 
                    } else {
                        echo "<p>Không có sản phẩm nào để hiển thị.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to get URL parameters
    function getUrlParams() {
        let params = new URLSearchParams(window.location.search);
        return {
            sort: params.get('sort') || '',
            price: params.get('price') || ''
        };
    }

    // Function to update sorting parameter in URL
    function applySorting() {
        let sortValue = document.getElementById("sortSelect").value;
        let params = getUrlParams();
        params.sort = sortValue;

        updateUrl(params);
    }

    // Function to apply filter and update URL
    function applyFilter(priceRange) {
        let params = getUrlParams();
        params.price = priceRange;

        updateUrl(params);
    }

    // Function to update the URL without reloading
    function updateUrl(params) {
        let url = new URL(window.location);
        url.searchParams.set("sort", params.sort);
        url.searchParams.set("price", params.price);

        window.location.href = url.href; // Redirect to new URL with updated parameters
    }

    // Auto-select the current sorting option when the page loads
    document.addEventListener("DOMContentLoaded", function () {
        let params = getUrlParams();
        if (params.sort) {
            document.getElementById("sortSelect").value = params.sort;
        }
    });
</script>
