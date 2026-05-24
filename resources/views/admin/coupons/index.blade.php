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
            background: #0b6fc7;
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

    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
    🏠 Dashboard
</a>

<a href="/admin/products" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
    👟 Quản lý sản phẩm
</a>

<a href="/admin/imports" class="{{ request()->is('admin/import*') ? 'active' : '' }}">
    📦 Quản lý nhập hàng
</a>

<a href="/admin/coupons" class="{{ request()->is('admin/coupons*') ? 'active' : '' }}">
    👤 Quản lý khuyến mãi
</a>

<a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
    🔐 Quản lý nhân viên
</a>

   <a href="#" onclick="toggleMenu()">📊 Báo cáo</a>
   <div class="submenu" id="submenu">
        <a href="/admin/revenue" class="{{ request()->is('admin/revenue') ? 'active' : '' }}">
            - Doanh thu
        </a>

        <a href="/admin/best-selling" class="{{ request()->is('admin/best-selling') ? 'active' : '' }}">
            - Sản phẩm bán chạy
        </a>  
    </div>

    <form method="POST" action="/logout">
    @csrf
        <button>🚪 Đăng xuất</button>
    </form>
</div>

<!-- CONTENT -->
<div class="content">

<h3>Quản lý mã khuyến mãi</h3>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

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
<table class="table table-bordered bg-white">
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

    <form method="POST"
          action="/admin/coupons/update/{{ $c->id }}">
        @csrf

        <td>
            <strong>{{ $c->code }}</strong>
        </td>

        <td style="width:120px;">
            <input type="number"
                   name="discount"
                   value="{{ $c->discount }}"
                   class="form-control">
        </td>

        <td style="width:120px;">
            <input type="number"
                   name="quantity"
                   value="{{ $c->quantity }}"
                   class="form-control">
        </td>

        <td style="width:180px;">
            <input type="date"
                   name="expired_at"
                   value="{{ \Carbon\Carbon::parse($c->expired_at)->format('Y-m-d') }}"
                   class="form-control">
        </td>

        <td>
            @if($expired)
                <span class="badge bg-danger">Hết hạn</span>
            @elseif($c->status == 'active')
                <span class="badge bg-success">Đang hoạt động</span>
            @else 
                <span class="badge bg-secondary">Chưa kích hoạt</span>
            @endif
        </td>

        <td class="d-flex gap-2">

            <button class="btn btn-primary btn-sm">
                💾 Sửa
            </button>

    </form>

            <form method="POST"
                  action="/admin/coupons/delete/{{ $c->id }}">
                @csrf

                <button class="btn btn-danger btn-sm">
                    🗑 Xóa
                </button>
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

<script>
setTimeout(function() {
    let alert = document.querySelector('.alert');
    if (alert) {
        let bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }
}, 2500);
</script>

</body>
</html>