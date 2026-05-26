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

<a href="/admin/imports" class="{{ request()->is('admin/imports*') ? 'active' : '' }}">
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

    <h3 class="mb-4">Danh sách nhân viên</h3>

    <!-- TẠO NHÂN VIÊN -->
    <div class="card p-3 mb-4 card-box shadow-sm">
        <h5 class="mb-3">➕ Tạo nhân viên</h5>

        <form method="POST" action="/admin/users/store">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Tên nhân viên" required>
                </div>

                <div class="col-md-4">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="col-md-4">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                </div>
            </div>

            <button class="btn btn-primary mt-3">Tạo nhân viên</button>
        </form>
    </div>

    <!-- DANH SÁCH -->
    <div class="card p-3 shadow-sm">
        <h5 class="mb-3">👥 Nhân viên hiện có</h5>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Trạng thái</th>
                    <th width="250">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>

                    <td>
                        @if($u->status == 'active')
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Đã khóa</span>
                        @endif
                    </td>

                    <td>

                        <!-- KHÓA / MỞ -->
                        <form method="POST" action="/admin/users/toggle/{{ $u->id }}" class="d-inline">
                            @csrf
                            <button class="btn btn-warning btn-sm">
                                {{ $u->status == 'active' ? 'Khóa' : 'Mở khóa' }}
                            </button>
                        </form>

                        <!-- RESET PASSWORD -->
                        <form method="POST" action="/admin/users/reset/{{ $u->id }}" class="d-inline">
                            @csrf
                            <button class="btn btn-info btn-sm">Reset mật khẩu</button>
                        </form>

                    </td>
                </tr>
                @endforeach

                <!-- DEMO nếu chưa có DB -->
                @if(empty($users) || count($users) == 0)
                <tr>
                    <td>1</td>
                    <td>Nhân viên</td>
                    <td>nhanvien@gmail.com</td>
                    <td><span class="badge bg-success">Hoạt động</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm">Khóa</button>
                        <button class="btn btn-info btn-sm">Reset mật khẩu</button>
                    </td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>

</div>

<script>
function toggleMenu(){
    let menu = document.getElementById('submenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}
</script>

</body>
</html>