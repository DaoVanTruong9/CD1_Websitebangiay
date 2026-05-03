<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
        }
        .header {
            height: 60px;
            background: #0b6fc7;
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
            position: fixed;
        }
        .sidebar a, .sidebar button {
            width: 100%;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            background: none;
            color: white;
            text-align: left;
            border: none;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover, .sidebar button:hover {
            background: #444;
        }
        .sidebar a.active {
            background: #000;
            font-weight: bold;
            border-left: 4px solid #0b6fc7;
        }

        .submenu {
            padding-left: 20px;
            display: none;
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
        }

        .bg-pink { background: #e91e63; }
        .bg-blue { background: #2196f3; }
        .bg-green { background: #4caf50; }
        .bg-orange { background: #ff9800; }

        .alert {
            border-radius: 10px;
            animation: all 0.5s ease;
        }

        @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    </style>
</head>

<body>
<div class="header">ADMIN SHOP GIÀY</div>

<!-- SIDEBAR -->
<div class="sidebar">

    <a href="/admin/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
    🏠 Dashboard
</a>

<a href="/admin/products" class="{{ request()->is('products*') ? 'active' : '' }}">
    👟 Quản lý sản phẩm
</a>

<a href="/admin/imports" class="{{ request()->is('inventory*') ? 'active' : '' }}">
    📦 Quản lý nhập hàng
</a>

<a href="#" class="{{ request()->is('customers*') ? 'active' : '' }}">
    👤 Quản lý khuyến mãi
</a>

<a href="#" class="{{ request()->is('users*') ? 'active' : '' }}">
    🔐 Quản lý nhân viên
</a>

   <a href="#" onclick="toggleMenu()">📊 Báo cáo</a>
   <div class="submenu" id="submenu">
        <a href="#">- Doanh thu</a>
        <a href="#">- Sản phẩm bán chạy</a>   
    </div>

    <form method="POST" action="/logout">
    @csrf
        <button>🚪 Đăng xuất</button>
    </form>
</div>

<!-- CONTENT -->
<div class="content">

<h3>Quản lý mã khuyến mãi</h3>

<!-- TẠO MÃ -->
<form method="POST" action="/admin/coupons/store" class="mb-3">
    @csrf

    <input type="text" name="code" placeholder="Mã (SALE10)" required>
    <input type="number" name="discount" placeholder="% giảm" required>
    <input type="number" name="quantity" placeholder="Số lượng" required>
    <input type="date" name="expired_at" required>

    <button>Tạo</button>
</form>

<hr>

<!-- DANH SÁCH -->
<table border="1" cellpadding="10">
    <tr>
        <th>Mã</th>
        <th>Giảm (%)</th>
        <th>Số lượng</th>
        <th>Hết hạn</th>
        <th>Trạng thái</th>
        <th></th>
    </tr>

    @foreach($coupons as $c)
    @php
        $expired = now()->gt($c->expired_at);
    @endphp

    <tr>
        <td>{{ $c->code }}</td>
        <td>{{ $c->discount }}%</td>
        <td>{{ $c->quantity }}</td>
        <td>{{ $c->expired_at }}</td>

        <td>
            {{ $expired ? 'Hết hạn' : 'Còn hạn' }}
        </td>

        <td>
            <form method="POST" action="/admin/coupons/delete/{{ $c->id }}">
                @csrf
                <button>Xóa</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

</div>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function toggleMenu(){
    let menu = document.getElementById('submenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}
</script>

</body>
</html>