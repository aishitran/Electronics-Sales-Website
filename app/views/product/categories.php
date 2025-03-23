<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar">
                <h5><i class="bi bi-list"></i> Danh mục</h5>
                <ul class="list-group">
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item has-submenu">
                            <a href="#"><?php echo $category['name']; ?> <i class="bi bi-chevron-right"></i></a>
                            <ul class="submenu">
                                <?php foreach ($subcategories[$category['id']] as $subcategory): ?>
                                    <li><a href="#"><?php echo $subcategory['name']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <h2>Danh mục sản phẩm</h2>
            <p>Danh sách các danh mục và danh mục con.</p>
        </div>
    </div>
</div>