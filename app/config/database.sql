--- DELETE DATABASE BEGIN
IF EXISTS (SELECT name FROM sys.databases WHERE name = N'ECommerceDB')
BEGIN
    ALTER DATABASE ECommerceDB SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
    DROP DATABASE ECommerceDB;
END
--- DELETE DATABASE END

--- CREATE AND SELECT DATABASE BEGIN
CREATE DATABASE ECommerceDB;
GO

USE ECommerceDB;
GO
--- CREATE AND SELECT DATABASE END

-- Bảng Vai Trò
CREATE TABLE VAITRO (
    MaVaiTro INT IDENTITY(1,1) PRIMARY KEY,
    TenVaiTro NVARCHAR(50) UNIQUE NOT NULL
);

-- Bảng Người Dùng
CREATE TABLE NGUOIDUNG (
    MaNguoiDung INT IDENTITY(1,1) PRIMARY KEY,
    HoTen NVARCHAR(100) NOT NULL,
    Email NVARCHAR(255) UNIQUE NOT NULL,
    MatKhau VARBINARY(64) NOT NULL,
    SoDienThoai NVARCHAR(15) CHECK (SoDienThoai LIKE '[0-9]%'),
    DiaChi NVARCHAR(255),
    MaVaiTro INT FOREIGN KEY REFERENCES VAITRO(MaVaiTro) NOT NULL,
    NgayTao DATETIME DEFAULT GETDATE()
);

-- Bảng Danh Mục
CREATE TABLE DANHMUC (
    MaDanhMuc INT IDENTITY(1,1) PRIMARY KEY,
    TenDanhMuc NVARCHAR(100) NOT NULL UNIQUE
);

-- Bảng Sản Phẩm (Integrated HinhAnh into initial creation)
CREATE TABLE SANPHAM (
    MaSanPham INT IDENTITY(1,1) PRIMARY KEY,
    TenSanPham NVARCHAR(255) NOT NULL,
    MoTa NVARCHAR(MAX),
    Gia DECIMAL(18,2) NOT NULL CHECK (Gia >= 0),
    SoLuong INT NOT NULL DEFAULT 0 CHECK (SoLuong >= 0),
    MaDanhMuc INT FOREIGN KEY REFERENCES DANHMUC(MaDanhMuc) ON DELETE CASCADE,
    HinhAnh NVARCHAR(255) NULL,
    NgayThem DATETIME DEFAULT GETDATE()
);

-- Bảng Giỏ Hàng
CREATE TABLE CART (
    MaNguoiDung INT NOT NULL,
    MaSanPham INT NOT NULL,
    SoLuong INT NOT NULL DEFAULT 1 CHECK (SoLuong >= 1),
    PRIMARY KEY (MaNguoiDung, MaSanPham),
    FOREIGN KEY (MaNguoiDung) REFERENCES NGUOIDUNG(MaNguoiDung),
    FOREIGN KEY (MaSanPham) REFERENCES SANPHAM(MaSanPham)
);

-- Bảng Đơn Hàng (Integrated TrangThai and ChuKy into initial creation)
CREATE TABLE DONHANG (
    MaDonHang INT IDENTITY(1,1) PRIMARY KEY,
    MaNguoiDung INT FOREIGN KEY REFERENCES NGUOIDUNG(MaNguoiDung) ON DELETE CASCADE,
    TongTien DECIMAL(18,2) NOT NULL CHECK (TongTien >= 0),
    TrangThai NVARCHAR(50) CHECK (TrangThai IN (N'Chờ xác nhận', N'Đang giao', N'Hoàn thành', N'Hủy', N'Đã thanh toán')) DEFAULT N'Chờ xác nhận',
    ChuKy NVARCHAR(50) NULL UNIQUE,
    NgayDatHang DATETIME DEFAULT GETDATE()
);

-- Bảng Chi Tiết Đơn Hàng
CREATE TABLE CHITIETDONHANG (
    MaChiTietDonHang INT IDENTITY(1,1) PRIMARY KEY,
    MaDonHang INT FOREIGN KEY REFERENCES DONHANG(MaDonHang) ON DELETE CASCADE,
    MaSanPham INT FOREIGN KEY REFERENCES SANPHAM(MaSanPham) ON DELETE CASCADE,
    SoLuong INT NOT NULL CHECK (SoLuong > 0),
    Gia DECIMAL(18,2) NOT NULL
);

-- Bảng Thanh Toán (Integrated UNIQUE constraint into initial creation)
CREATE TABLE THANHTOAN (
    MaThanhToan INT IDENTITY(1,1) PRIMARY KEY,
    MaDonHang INT UNIQUE FOREIGN KEY REFERENCES DONHANG(MaDonHang) ON DELETE CASCADE,
    PhuongThuc NVARCHAR(50) CHECK (PhuongThuc IN (N'Tiền mặt', N'Chuyển khoản', N'Thẻ tín dụng')) NOT NULL,
    TrangThai NVARCHAR(50) CHECK (TrangThai IN (N'Chưa thanh toán', N'Đã thanh toán')) DEFAULT N'Chưa thanh toán',
    NgayThanhToan DATETIME NULL
);
-- 📌 TẠO DỮ LIỆU MẪU

-- Chèn Vai Trò
INSERT INTO VAITRO (TenVaiTro) VALUES (N'Khách Hàng'), (N'Admin');

-- Chèn Người Dùng
INSERT INTO NGUOIDUNG (HoTen, Email, MatKhau, SoDienThoai, DiaChi, MaVaiTro) VALUES 
(N'Nguyễn Văn A', 'nva@example.com', HASHBYTES('SHA2_256', 'password123'), '0987654321', N'Hà Nội', 1);

-- Chèn Danh Mục
INSERT INTO DANHMUC (TenDanhMuc) VALUES 
    (N'Bàn phím'),
    (N'Màn hình'),
    (N'Laptop Gaming, văn phòng'),
    (N'Gaming Gear'),
    (N'Máy tính để bàn'),
    (N'Linh kiện máy tính'),
    (N'Linh kiện khác'),
	(N'Phụ kiện'),
	(N'Điện thoại');

-- Chèn Sản Phẩm
INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'Keychron K2 V2', N'Bàn phím cơ không dây 75% layout', 1890000, 10, 1),
(N'Ducky One 2 Mini', N'Bàn phím cơ 60%, switch Cherry MX', 2590000, 8, 1),
(N'Logitech MX Keys', N'Bàn phím văn phòng cao cấp', 2890000, 12, 1),
(N'Corsair K95 RGB Platinum', N'Bàn phím cơ fullsize RGB switch MX', 4590000, 7, 1),
(N'Razer Huntsman Mini', N'Bàn phím cơ TKL, switch quang học', 2690000, 9, 1),
(N'Akko 3098B Black&Gold', N'Bàn phím không dây, keycap PBT', 1990000, 15, 1),
(N'SteelSeries Apex Pro', N'Bàn phím chơi game với switch OmniPoint', 4990000, 6, 1),
(N'E-Dra EK387', N'Bàn phím cơ giá rẻ, switch Huano', 890000, 20, 1),
(N'Fuhlen D87M', N'Bàn phím cơ giá tốt, full LED', 1190000, 18, 1),
(N'Asus ROG Claymore II', N'Bàn phím gaming cao cấp, tháo rời numpad', 4990000, 5, 1);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'Dell U2723QE', N'Màn hình 27 inch 4K IPS cao cấp', 13990000, 6, 2),
(N'Samsung Odyssey G5 32"', N'Màn hình cong 2K, 165Hz', 6990000, 10, 2),
(N'LG UltraGear 27GP850-B', N'Màn hình 2K 180Hz Nano IPS', 8990000, 8, 2),
(N'ASUS ProArt PA278CV', N'Màn hình đồ họa chuyên nghiệp', 10900000, 4, 2),
(N'ViewSonic VX3276', N'Màn hình 32 inch, Full HD, viền mỏng', 4390000, 12, 2),
(N'Gigabyte M28U', N'Màn hình 4K 144Hz HDMI 2.1', 15990000, 5, 2),
(N'AOC 24G2', N'Màn hình gaming 144Hz IPS', 4290000, 11, 2),
(N'MSI Optix MAG274QRF', N'Màn hình 2K Rapid IPS 165Hz', 8790000, 7, 2),
(N'BenQ EX3501R', N'Màn hình cong 35" Ultrawide', 11900000, 3, 2),
(N'Huawei MateView GT 34"', N'Màn hình cong chơi game, soundbar tích hợp', 9490000, 6, 2);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'MacBook Air M2 2023', N'Laptop văn phòng Apple hiệu năng cao', 27990000, 10, 3),
(N'ASUS ROG Strix G16', N'Laptop gaming RTX 4060, màn hình 165Hz', 32900000, 5, 3),
(N'HP Pavilion 15', N'Laptop học tập, văn phòng tầm trung', 13900000, 12, 3),
(N'Lenovo Legion 5 Pro', N'Laptop gaming Ryzen 7 + RTX 4070', 36900000, 4, 3),
(N'Dell XPS 13', N'Laptop siêu nhẹ, màn hình cảm ứng 4K', 32900000, 6, 3),
(N'Acer Aspire 7', N'Laptop văn phòng cấu hình tốt, GTX 1650', 15490000, 8, 3),
(N'MSI Bravo 15', N'Laptop gaming giá rẻ, Ryzen 5, RX5500M', 13900000, 9, 3),
(N'Gigabyte Aorus 15X', N'Laptop chơi game cao cấp, RTX 4080', 45900000, 2, 3),
(N'ASUS ZenBook 14 OLED', N'Laptop mỏng nhẹ, màn hình OLED đẹp mắt', 19990000, 7, 3),
(N'Surface Laptop 5', N'Laptop cao cấp của Microsoft', 30900000, 5, 3);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'Logitech G502 X', N'Chuột chơi game có dây siêu nhạy', 2290000, 15, 4),
(N'Razer DeathAdder V2 Pro', N'Chuột không dây cho game thủ', 2990000, 10, 4),
(N'SteelSeries Arctis Nova 7', N'Tai nghe gaming không dây chất lượng', 3990000, 7, 4),
(N'Razer Seiren Mini', N'Microphone thu âm cho streamer', 1290000, 12, 4),
(N'Corsair MM700 RGB', N'Lót chuột RGB cỡ lớn', 990000, 18, 4),
(N'Elgato Stream Deck Mini', N'Công cụ hỗ trợ streamer chuyên nghiệp', 2590000, 6, 4),
(N'Logitech G733 Lightspeed', N'Tai nghe không dây, RGB, pin lâu', 3290000, 9, 4),
(N'HyperX Pulsefire Haste', N'Chuột siêu nhẹ cho eSports', 1390000, 14, 4),
(N'Razer Tartarus V2', N'Bàn phím phụ chuyên game MMO', 1890000, 5, 4),
(N'Asus ROG Scabbard II', N'Lót chuột kháng nước, bề mặt lớn', 790000, 20, 4);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'Intel NUC 11', N'Máy tính mini cho văn phòng', 12900000, 8, 5),
(N'PC Gaming Ryzen 5 + RTX 4060', N'Cấu hình chơi game Full HD max settings', 25900000, 6, 5),
(N'HP ProDesk 400 G7', N'Máy bộ doanh nghiệp nhỏ gọn', 15900000, 10, 5),
(N'ASUS ExpertCenter D5', N'Máy để bàn văn phòng mạnh mẽ', 13490000, 12, 5),
(N'AIO Dell Inspiron 24"', N'Máy tính all-in-one gọn nhẹ', 18900000, 4, 5),
(N'PC Intel Core i7 + RTX 3070', N'Máy tính chơi game cấu hình cao', 34900000, 3, 5),
(N'MSI Cubi 5', N'Mini PC hiệu năng tốt cho học sinh', 9990000, 9, 5),
(N'PC đồ họa Xeon + Quadro P2200', N'Cấu hình thiết kế 3D chuyên nghiệp', 38900000, 2, 5),
(N'PC chơi game GVN Mid', N'PC lắp sẵn, giá tốt cho game thủ', 17900000, 7, 5),
(N'Lenovo ThinkCentre Neo 50s', N'Máy doanh nghiệp nhỏ gọn, ổn định', 14900000, 11, 5);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'CPU AMD Ryzen 7 5800X', N'Bộ xử lý 8 nhân mạnh mẽ', 7290000, 10, 6),
(N'Mainboard ASUS B550 TUF Gaming', N'Main cho AMD Ryzen, tản tốt', 3590000, 8, 6),
(N'RAM Corsair Vengeance 16GB DDR4', N'RAM bus 3200MHz, tản nhiệt tốt', 1490000, 20, 6),
(N'SSD Samsung 980 Pro 1TB', N'Ổ cứng tốc độ cao PCIe Gen 4', 3490000, 9, 6),
(N'Nguồn Corsair RM750e', N'PSU 750W chuẩn 80+ Gold', 2690000, 7, 6),
(N'Tản nhiệt Noctua NH-D15', N'Tản nhiệt khí cao cấp cho CPU', 2690000, 5, 6),
(N'VGA ASUS RTX 4070 Dual', N'Card đồ họa chơi game 2K mượt mà', 13900000, 4, 6),
(N'Case NZXT H510 Elite', N'Vỏ máy đẹp, kính cường lực', 2990000, 6, 6),
(N'Fan ARGB Lian Li Uni', N'Bộ quạt RGB kết nối dễ dàng', 990000, 15, 6),
(N'M2 SSD Kingston NV2 500GB', N'Ổ cứng M.2 giá rẻ hiệu quả', 890000, 20, 6);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'Card mạng TP-Link AX3000', N'Card mạng Wi-Fi 6, PCIe', 1290000, 12, 7),
(N'Bluetooth USB Orico 5.0', N'USB Bluetooth nhỏ gọn cho PC', 350000, 25, 7),
(N'Hub USB-C Baseus 8-in-1', N'Bộ chia cổng đa năng cho laptop', 890000, 18, 7),
(N'Cáp HDMI 2.1 Ugreen 3M', N'Chuẩn HDMI 8K, tốc độ cao', 390000, 20, 7),
(N'Dock ổ cứng Orico 2-bay', N'Dock SATA hỗ trợ ổ 2.5/3.5"', 1190000, 8, 7),
(N'USB Wi-Fi D-Link AC1300', N'USB thu Wi-Fi băng tần kép', 690000, 14, 7),
(N'Giá đỡ laptop nhôm', N'Giá nâng laptop giúp tản nhiệt tốt', 590000, 15, 7),
(N'Adapter SATA to USB 3.0', N'Cáp chuyển ổ cứng sang USB', 290000, 22, 7),
(N'Controller Xbox Wireless', N'Tay cầm chơi game kết nối PC/Bluetooth', 1690000, 10, 7),
(N'Bo mạch chuyển PCIe sang M.2', N'Cho phép gắn SSD M.2 vào main không hỗ trợ', 490000, 13, 7);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'Sạc GaN Anker 65W', N'Sạc nhanh nhiều cổng cho laptop/điện thoại', 1090000, 18, 8),
(N'Pin sạc dự phòng Xiaomi 20000mAh', N'Dung lượng lớn, hỗ trợ sạc nhanh', 790000, 20, 8),
(N'Tai nghe Sony WH-CH520', N'Tai nghe Bluetooth pin lâu', 1190000, 14, 8),
(N'Đế sạc không dây Magsafe', N'Sạc không dây cho iPhone', 590000, 17, 8),
(N'Giá đỡ điện thoại Baseus', N'Dùng để bàn, góc nhìn linh hoạt', 290000, 25, 8),
(N'Kính cường lực iPhone 15 Pro Max', N'Kính chống vỡ, chống xước cao cấp', 190000, 30, 8),
(N'Ốp lưng Spigen iPhone 14', N'Bảo vệ tốt, thiết kế thời trang', 390000, 28, 8),
(N'Chuột không dây Logitech M350', N'Chuột văn phòng nhỏ gọn', 499000, 22, 8),
(N'Balo laptop chống sốc Tomtoc', N'Dành cho laptop đến 16 inch', 1290000, 10, 8),
(N'Cáp sạc Ugreen USB-C to Lightning', N'Chứng nhận MFi, sạc nhanh', 390000, 26, 8);

INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc) VALUES 
(N'iPhone 15 Pro Max 256GB', N'Mẫu flagship mới nhất của Apple', 34990000, 12, 9),
(N'Samsung Galaxy S24 Ultra', N'Màn hình lớn, camera zoom 100x', 32990000, 8, 9),
(N'Xiaomi 14 Pro', N'Flagship giá tốt, hiệu năng khủng', 19990000, 10, 9),
(N'OPPO Reno11 5G', N'Thiết kế đẹp, camera chân dung AI', 10900000, 15, 9),
(N'Vivo V30e', N'Smartphone mỏng nhẹ, pin trâu', 8990000, 17, 9),
(N'Realme C67', N'Máy giá rẻ pin khủng', 3990000, 25, 9),
(N'Nokia G60 5G', N'Điện thoại bền bỉ, hỗ trợ cập nhật lâu dài', 5690000, 18, 9),
(N'ASUS ROG Phone 7', N'Máy chơi game cấu hình khủng', 23900000, 6, 9),
(N'Google Pixel 8', N'Android gốc, camera AI siêu nét', 18900000, 9, 9),
(N'Vsmart Joy 4', N'Máy Việt cấu hình ổn', 2890000, 30, 9);


-- TẠO INDEX ĐỂ TỐI ƯU TRUY VẤN
CREATE INDEX IX_NGUOIDUNG_Email ON NGUOIDUNG(Email);
CREATE INDEX IX_SANPHAM_TenSanPham ON SANPHAM(TenSanPham);
CREATE INDEX IX_DONHANG_NgayDatHang ON DONHANG(NgayDatHang);
CREATE INDEX IX_CHITIETDONHANG_MaDonHang ON CHITIETDONHANG(MaDonHang);
