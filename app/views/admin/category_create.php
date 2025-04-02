<?php
$pageTitle = 'Thêm Danh Mục';
?>

<div class="container mt-4">
    <h2 class="text-center">Thêm Danh Mục</h2>
    <form method="POST" action="/index.php?action=createCategory">
        <div class="mb-3">
            <label for="tenDanhMuc" class="form-label">Tên Danh Mục</label>
            <input type="text" class="form-control" id="tenDanhMuc" name="tenDanhMuc" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="/index.php?action=adminCategories" class="btn btn-secondary">Hủy</a>
    </form>
</div>