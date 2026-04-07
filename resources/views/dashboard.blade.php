<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- @if(Auth::user()->role == 'admin')
        <a href="/products">👟 Quản lý sản phẩm</a>
        <a href="/orders">📦 Quản lý đơn hàng</a>
    @endif

    @if(Auth::user()->role == 'staff')
        <a href="/orders">📦 Xử lý đơn hàng</a>
    @endif

    @if(Auth::user()->role == 'user')
        <a href="/">🛒 Mua hàng</a>
    @endif -->
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
        }

        /* Header */
        .header {
            height: 60px;
            background: #0b6fc7;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
            font-weight: bold;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #222;
            color: white;
            position: fixed;
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

        .submenu {
            padding-left: 20px;
            display: none;
        }

        /* Content */
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
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    ADMIN SHOP GIÀY
</div>

<!-- SIDEBAR -->
<div class="sidebar">

    <a href="/dashboard">🏠 Dashboard</a>

    <a href="/products">👟 Quản lý sản phẩm</a>

    <a href="/orders">📦 Quản lý đơn hàng</a>

    <a href="#">👤 Quản lý khách hàng</a>

    <a href="#">🔐 Quản lý tài khoản</a>

    <a href="#" onclick="toggleMenu()">📊 Báo cáo</a>
    <div class="submenu" id="submenu">
        <a href="#">- Doanh thu</a>
        <a href="#">- Sản phẩm bán chạy</a>
    </div>

    <form method="POST" action="/logout">
    @csrf
    <button class="btn btn-danger w-100 mt-2">🚪 Đăng xuất</button>
    </form>

</div>

<!-- CONTENT -->
<div class="content">

    <h3 class="mb-4">Dashboard</h3>

    <!-- Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-box bg-pink">
                Đơn hàng
                <h3>125</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-blue">
                Sản phẩm
                <h3>257</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-green">
                Khách hàng
                <h3>243</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-orange">
                Doanh thu
                <h3>12,000,000đ</h3>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card p-3">
        <h5>Biểu đồ doanh thu</h5>
        <canvas id="chart"></canvas>
    </div>

</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function toggleMenu(){
    let menu = document.getElementById('submenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['T1','T2','T3','T4','T5','T6'],
        datasets: [{
            label: 'Doanh thu',
            data: [10,20,15,30,25,40],
            borderWidth: 2
        }]
    }
});
</script>

</body>
</html>