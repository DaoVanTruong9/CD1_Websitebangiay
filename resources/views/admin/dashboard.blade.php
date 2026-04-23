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

   <a href="#" onclick="toggleMenu()" 
   class="{{ request()->is('report*') ? 'active' : '' }}">
   📊 Báo cáo
</a>

<div class="submenu" id="submenu"
     style="{{ request()->is('report*') ? 'display:block' : 'display:none' }}">

    <a href="/report/revenue" 
       class="{{ request()->is('report/revenue') ? 'active' : '' }}">
        - Doanh thu
    </a>

    <a href="/report/top-product" 
       class="{{ request()->is('report/top-product') ? 'active' : '' }}">
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

    <h3 class="mb-4">Dashboard</h3>

    <!-- Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-box bg-pink">
                Đơn hàng
                <h3></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-blue">
                Sản phẩm
                <h3></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-green">
                Khách hàng
                <h3></h3>
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