<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin: 0; }

        .header {
            height: 60px;
            background: #179bf9;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
            font-weight: bold;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #222;
            color: white;
            position: fixed;
        }
        .submenu {
        padding-left: 20px;
        }

        .submenu {
        max-height: 0;
        overflow: hidden;
        background: #333;
        transition: max-height 0.3s ease;
        }

        .submenu.active {
        max-height: 200px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #444;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .card-box {
            color: white;
            padding: 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .card-box:hover {
            transform: scale(1.05);
        }

        .bg-red { background: #dc3545; }
        .bg-blue { background: #007bff; }
        .bg-yellow { background: #ffc107; color: black; }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    NHÂN VIÊN - QUẢN LÝ SHOP
</div>

<!-- SIDEBAR -->
<div class="sidebar">

    <a href="/staff/dashboard">🏠 Dashboard</a>

    <a href="javascript:void(0)" onclick="toggleMenu()">📦 Xử lý đơn hàng</a>
    <div class="submenu" id="submenu">
        <a href="/staff/returns">🔄 Trả hàng</a>
        <a href="/staff/returns">🔁 Đổi hàng</a>
        <a href="/staff/orders">📦 Cập nhật đơn</a>
    </div>

    <a href="/staff/inventory">📦 Kiểm tra tồn kho</a>

    <a href="/staff/promotion">🏷️ Khuyến mãi</a>

    <form method="POST" action="/logout">
        @csrf
        <button class="btn btn-danger w-100 mt-2">🚪 Đăng xuất</button>
    </form>

</div>

<!-- CONTENT -->
<div class="content">

    <h3 class="mb-4">Dashboard Nhân viên</h3>

    <div class="row">

        <!-- Trả hàng -->
        <div class="col-md-4">
            <div class="card-box bg-red" onclick="location.href='/staff/returns'">
                🔄 Trả / Đổi hàng
                <h4>Xử lý yêu cầu</h4>
            </div>
        </div>

        <!-- Tồn kho -->
        <div class="col-md-4">
            <div class="card-box bg-blue" onclick="location.href='/staff/inventory'">
                📦 Kiểm tra tồn kho
                <h4>Xem số lượng</h4>
            </div>
        </div>

        <!-- Khuyến mãi -->
        <div class="col-md-4">
            <div class="card-box bg-yellow" onclick="location.href='/staff/promotion'">
                🏷️ Áp dụng khuyến mãi
                <h4>Giảm giá sản phẩm</h4>
            </div>
        </div>

    </div>

</div>
<script>
function toggleMenu() {
    let menu = document.getElementById("submenu");
    menu.classList.toggle("active");
}
</script>
</body>
</html>