
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="cart.css">
      <!--Boostrap CSS-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>


<?php @include '../shares/header.php'; ?>



<h2>Giỏ hàng của bạn</h2>

<div class="container_cart">
    <div class="cart">
    <table class="cart-table">
    <thead>
        <tr>
            <th>Thông tin sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Dữ liệu giả lập, có thể thay bằng dữ liệu từ database
        
        $cart = [
            [
            "image" => "https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_8__4_83.png",
            "name" => "Màn hình Viewsonic VX758 4K", 
            "price" => 6200000, 
            "quantity" => 1],
            [
            "image" => "https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/o/loa-bluetooth-edifier-es300-_12_.png", 
            "name" => "Loa Edifier S300", 
            "price" => 4500000, 
            "quantity" => 1],
        ];

        $total = 0;
        foreach ($cart as $item) {
            $subtotal = $item["price"] * $item["quantity"];
            $total += $subtotal;
            echo "<tr>
                <td>
                <div class='product_info'>
                 <div claSS ='image'>
                    <img src='{$item["image"]}' alt='{$item["name"]}'>
                    </div>
                    <div class='info'>
                    <p>{$item["name"]}</p>
                    <a href='#' class='remove'>Xóa</a>
                    </div>
                </div>
                </td>
                <td>" . number_format($item["price"], 0, ',', '.') . "đ</td>
                <td>
                    <div class='buttonthembot'>
                    <button>-</button>
                    <input type='text' value='{$item["quantity"]}' readonly>
                    <button>+</button>
                    </div>
                </td>
                <td>" . number_format($subtotal, 0, ',', '.') . "đ</td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<div class="total">
        <p><strong>Tổng tiền:</strong> <?php echo number_format($total, 0, ',', '.'); ?>đ</p>
        <button class="checkout-btn">Thanh toán</button>
    </div>
    </div>
    
</div>


<?php @include '../shares/footer.php'; ?>

</body>
</html>