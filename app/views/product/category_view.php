<?php
$pageTitle = 'Danh Mục Sản Phẩm';
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
        <div class="col-md-9 border rounded">
            <?php if (isset($selectedCategory)): ?>
                <h5 class="fw-bold fs-4 mt-4 mb-3"><?= htmlspecialchars($selectedCategory['TenDanhMuc']) ?></h3>
            <?php endif; ?>
            <div class="row">
                <?php if (empty($products)): ?>
                    <div class="col-12">
                        <p class="text-center">Không có sản phẩm nào trong danh mục này.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if (!empty($product['HinhAnh']) && file_exists('public/' . $product['HinhAnh'])): ?>
                                    <img src="/public/<?= htmlspecialchars($product['HinhAnh']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['TenSanPham']) ?>">
                                <?php else: ?>
                                    <div class="card-img-top text-center py-4">No Image</div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($product['TenSanPham']) ?></h5>
                                    <p class="card-text"><?= number_format($product['Gia'], 0, ',', '.') ?> VND</p>
                                    <a href="/index.php?action=viewProduct&id=<?= htmlspecialchars($product['MaSanPham']) ?>" class="btn btn-primary">Xem Chi Tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">