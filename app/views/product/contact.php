<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/app/css/style.css"> <!-- Adjust path as needed -->
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Left Column: Contact Info and Form -->
            <div class="col-md-6">
                <h2 class="text-uppercase mb-4">Temp</h2>
                <ul class="list-unstyled contact-info mb-4">
                    <li class="mb-3">
                        <i class="bi bi-geo-alt me-2"></i>
                        10/80c Song Hành Xa Lộ Hà Nội, Phường Tân Phú, Thủ Đức, Hồ Chí Minh
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-envelope me-2"></i>
                        Email: cskh@temp.net</a>
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-telephone me-2"></i>
                        Hotline: 02835120785
                    </li>
                </ul>

                <h4 class="text-uppercase mb-3">Liên hệ với chúng tôi</h4>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" placeholder="Nhập họ và tên" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Nhập email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Điện thoại</label>
                        <input type="tel" class="form-control" id="phone" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Nội dung</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Nhập nội dung" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi thông tin</button>
                </form>
            </div>

            <!-- Right Column: Map -->
            <div class="col-md-6">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.4276420661968!2d106.78537299999999!3d10.8550427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527c3debb5aad%3A0x5fb58956eb4194d0!2zxJDhuqFpIEjhu41jIEh1dGVjaCBLaHUgRQ!5e0!3m2!1svi!2s!4v1742326519082!5m2!1svi!2s" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</body>
</html>

