Hệ Thống Website Bán Giày Thể Thao Sử Dụng Laravel

Đây là dự án website thương mại điện tử bán giày thể thao được xây dựng bằng Laravel (PHP) và MySQL.
Hệ thống mô phỏng quy trình hoạt động thực tế của một cửa hàng bán giày online với đầy đủ chức năng dành cho:

Khách hàng
Nhân viên
Quản trị viên (Admin)

Dự án được phát triển theo mô hình MVC của Laravel nhằm đảm bảo khả năng mở rộng, bảo trì và quản lý hệ thống hiệu quả.

📌 Tổng Quan Dự Án

Website hỗ trợ:

Hiển thị và tìm kiếm sản phẩm
Lọc sản phẩm theo thương hiệu, size, giá
Giỏ hàng và thanh toán
Quản lý đơn hàng
Quản lý kho hàng
Quản lý khuyến mãi
Báo cáo doanh thu
Thống kê sản phẩm bán chạy

Hệ thống hướng đến trải nghiệm mua sắm thân thiện và quy trình quản lý thực tế trong thương mại điện tử.

✨ Chức Năng Chính
👤 Chức Năng Người Dùng
Xem danh sách sản phẩm
Tìm kiếm sản phẩm
Lọc sản phẩm theo:
Thương hiệu
Size
Khoảng giá
Xem nhanh thông tin sản phẩm
Thêm sản phẩm vào giỏ hàng
Thanh toán đơn hàng
Thanh toán QR
Xem lịch sử mua hàng
🛠 Chức Năng Quản Trị Viên (Admin)
Dashboard thống kê
Quản lý sản phẩm (CRUD)
Quản lý mã giảm giá
Quản lý nhập hàng
Quản lý người dùng
Báo cáo doanh thu
Báo cáo sản phẩm bán chạy
👨‍💼 Chức Năng Nhân Viên (Staff)
Dashboard nhân viên
Quản lý kho hàng
Xử lý đơn hàng
Quản lý khuyến mãi
Xử lý đổi hàng
Xử lý trả hàng
🧠 Kiến Trúc Hệ Thống
Mô Hình MVC Laravel
Người dùng (Browser)
        ↓
Routes (web.php)
        ↓
Controllers
        ↓
Models (Eloquent ORM)
        ↓
Cơ sở dữ liệu MySQL
        ↓
Blade Views (Giao diện)
        ↓
Hiển thị trên trình duyệt
📂 Cấu Trúc Thư Mục Dự Án
SHOES_STORE/
│
├── public/
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php
│   └── robots.txt
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       │
│       ├── admin/
│       │   ├── coupons/
│       │   │   └── index.blade.php
│       │   │
│       │   ├── imports/
│       │   │   └── index.blade.php
│       │   │
│       │   ├── products/
│       │   │   └── index.blade.php
│       │   │
│       │   ├── reports/
│       │   │   ├── best_selling.blade.php
│       │   │   └── revenue.blade.php
│       │   │
│       │   └── users/
│       │       ├── index.blade.php
│       │       └── dashboard.blade.php
│       │
│       ├── auth/
│       │
│       ├── orders/
│       │   └── invoice.blade.php
│       │
│       ├── staff/
│       │   ├── dashboard.blade.php
│       │   ├── exchanges.blade.php
│       │   ├── inventory.blade.php
│       │   ├── orders.blade.php
│       │   ├── promotion.blade.php
│       │   └── returns.blade.php
│       │
│       ├── user/
│       │   ├── cart.blade.php
│       │   ├── checkout.blade.php
│       │   ├── history.blade.php
│       │   ├── home.blade.php
│       │   ├── orders.blade.php
│       │   ├── products.blade.php
│       │   └── qr_payment.blade.php
│       │
│       └── welcome.blade.php
│
├── routes/
│   ├── auth.php
│   ├── console.php
│   └── web.php
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │
│   └── Models/
│
├── database/
│   └── migrations/
│
├── storage/
│
├── vendor/
│
├── .env
├── artisan
├── composer.json
└── README.md
📦 Ví Dụ Cấu Trúc Cơ Sở Dữ Liệu
Bảng products
Cột	Ý nghĩa
id	Mã sản phẩm
name	Tên sản phẩm
brand	Thương hiệu
size	Size giày
price	Giá sản phẩm
image	Hình ảnh
is_sale	Trạng thái giảm giá
is_featured	Sản phẩm nổi bật
🔎 Hệ Thống Lọc Sản Phẩm

Website hỗ trợ lọc sản phẩm động bằng tham số GET.

Các bộ lọc hỗ trợ
Tìm kiếm theo tên
Lọc theo thương hiệu:
Nike
Adidas
Mizuno
Lọc theo size
Lọc theo khoảng giá
Đặc điểm giao diện
Tự động submit khi thay đổi bộ lọc
Responsive với Bootstrap 5
Giữ nguyên query khi phân trang
🛒 Quy Trình Mua Hàng
Trang chủ
   ↓
Xem sản phẩm
   ↓
Tìm kiếm / Lọc sản phẩm
   ↓
Thêm vào giỏ hàng
   ↓
Thanh toán
   ↓
Thanh toán QR
   ↓
Xác nhận đơn hàng
   ↓
Lịch sử mua hàng
📊 Chức Năng Báo Cáo
Báo Cáo Doanh Thu
Doanh thu theo ngày
Doanh thu theo tháng
Tổng doanh thu
Báo Cáo Sản Phẩm Bán Chạy
Sản phẩm bán nhiều nhất
Số lượng bán
Doanh thu theo sản phẩm
⚙️ Công Nghệ Sử Dụng
Backend
PHP 8+
Laravel 10+
MySQL
Frontend
Blade Template Engine
Bootstrap 5
HTML/CSS
JavaScript
Công Cụ Phát Triển
Composer
XAMPP / Laragon
Visual Studio Code
▶️ Hướng Dẫn Cài Đặt
1. Clone dự án
git clone <link-repository>
cd SHOES_STORE
2. Cài đặt thư viện
composer install
3. Cấu hình môi trường
cp .env.example .env
php artisan key:generate
4. Cấu hình cơ sở dữ liệu

Mở file .env

DB_DATABASE=shoes_store
DB_USERNAME=root
DB_PASSWORD=
5. Chạy migration
php artisan migrate
6. Khởi động server
php artisan serve

Mở trình duyệt:

http://localhost:8000
🧪 Các Module Đã Hoàn Thành
Module	Trạng thái
Đăng nhập / Đăng xuất	✅
Quản lý sản phẩm	✅
Hệ thống lọc sản phẩm	✅
Giỏ hàng	✅
Thanh toán	✅
Thanh toán QR	✅
Quản lý đơn hàng	✅
Quản lý kho	✅
Quản lý khuyến mãi	✅
Báo cáo doanh thu	✅
Thống kê bán chạy	✅
📖 Điểm Nổi Bật Kỹ Thuật
Kiến trúc MVC của Laravel
Eloquent ORM
Blade Template Engine
Bộ lọc sản phẩm động
Giao diện responsive Bootstrap
Hệ thống phân quyền:
User
Staff
Admin
Phân trang dữ liệu
Giữ query khi lọc dữ liệu
🚧 Hạn Chế Hiện Tại
Size sản phẩm lưu dạng chuỗi chưa tối ưu
Chưa tích hợp API RESTful
Chưa tích hợp thanh toán online thực tế
Chưa đồng bộ kho theo thời gian thực
Chưa có hệ thống gợi ý sản phẩm
🔮 Hướng Phát Triển Tương Lai
Chuẩn hóa bảng size sản phẩm
Tích hợp VNPay / MoMo
Xây dựng REST API
Phát triển frontend bằng React/Vue
Thêm hệ thống AI gợi ý sản phẩm
Đồng bộ kho thời gian thực
Xuất báo cáo Excel/PDF