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

        .card-box{
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card-box:hover{
    transform: translateY(-5px);
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

    <h3 class="mb-4">Dashboard</h3>

    <!-- ===== TỔNG ===== -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-box bg-pink text-white p-3">
                Đơn hàng
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-blue text-white p-3">
                Sản phẩm
                <h3>{{ $totalProducts }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-green text-white p-3">
                Khách hàng
                <h3>{{ $totalCustomers }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-orange text-white p-3">
                Doanh thu
                <h3>{{ number_format($revenue) }}đ</h3>
            </div>
        </div>
    </div>

    <!-- ===== HÔM NAY ===== -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card-box bg-dark text-white p-3">
                Đơn hôm nay
                <h3>{{ $todayOrders }}</h3>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-box bg-success text-white p-3">
                Doanh thu hôm nay
                <h3>{{ number_format($todayRevenue) }}đ</h3>
            </div>
        </div>
    </div>

    <!-- ===== CHART ===== -->
    <div class="card shadow-sm border-0 p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">
            📈 Biểu đồ doanh thu
        </h5>

        <span class="badge bg-primary">
            Theo tháng
        </span>
    </div>

    <div style="height: 400px;">
        <canvas id="chart"></canvas>
    </div>

    </div>

    <div class="row mb-4">

    <!-- DONUT -->
    <div class="col-md-6">

        <div class="card shadow-sm border-0 p-4 h-100">

            <h5 class="fw-bold mb-4">
                📦 Trạng thái đơn hàng
            </h5>

            <div style="height:300px;">
                <canvas id="statusChart"></canvas>
            </div>

        </div>

    </div>

    <!-- INVENTORY -->
    <div class="col-md-6">

        <div class="card shadow-sm border-0 p-4 h-100">

            <div class="d-flex justify-content-between">

                <h5 class="fw-bold">
                    ⚠️ Sắp hết hàng
                </h5>

                <span class="badge bg-danger">
                    {{ $lowStockProducts->count() }}
                </span>

            </div>

            <hr>

            @foreach($lowStockProducts as $item)

            <div class="mb-3">

                <div class="d-flex justify-content-between">

                    <strong>
                        {{ $item->product->name ?? 'N/A' }}
                    </strong>

                    <span class="text-danger">
                        {{ $item->quantity }}
                    </span>

                </div>

                <div class="progress mt-2" style="height:8px;">

                    <div class="progress-bar bg-danger"
                        style="width: {{ min($item->quantity * 10, 100) }}%">
                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>

    <!-- ===== TOP PRODUCT ===== -->
    <div class="card p-3 mb-4">
        <h5>Top sản phẩm bán chạy</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đã bán</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->total_sold }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card shadow-sm border-0 p-4 mb-4">

    <h5 class="fw-bold mb-4">
        👟 Top sản phẩm bán chạy
    </h5>

    <div style="height:400px;">
        <canvas id="productChart"></canvas>
    </div>

</div>

    <!-- ===== ĐƠN GẦN ĐÂY ===== -->
    <div class="card p-3">
        <h5>Đơn hàng gần đây</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách</th>
                    <th>Tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($latestOrders as $o)
                <tr>
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->customer_name }}</td>
                    <td>{{ number_format($o->total_price) }}đ</td>
                    <td>
                    @if($o->status == 'pending')
                        <span class="badge bg-warning">Chờ xử lý</span>

                    @elseif($o->status == 'confirmed')
                        <span class="badge bg-info">Đã xác nhận</span>

                    @elseif($o->status == 'shipping')
                        <span class="badge bg-primary">Đang giao</span>

                    @elseif($o->status == 'completed')
                        <span class="badge bg-success">Hoàn thành</span>

                    @else
                        <span class="badge bg-danger">Đã hủy</span>
                    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('chart').getContext('2d');

const revenueData = @json($monthlyRevenue);

const labels = Object.keys(revenueData).map(m => 'Tháng ' + m);

const data = Object.values(revenueData);

// ===== GRADIENT =====
const gradient = ctx.createLinearGradient(0, 0, 0, 400);

gradient.addColorStop(0, 'rgba(11,111,199,0.4)');
gradient.addColorStop(1, 'rgba(11,111,199,0.02)');

// ===== CHART =====
new Chart(ctx, {

    type: 'line',

    data: {

        labels: labels,

        datasets: [{

            label: 'Doanh thu (VNĐ)',

            data: data,

            fill: true,

            backgroundColor: gradient,

            borderColor: '#0b6fc7',

            borderWidth: 4,

            tension: 0.4,

            pointBackgroundColor: '#0b6fc7',

            pointBorderColor: '#fff',

            pointRadius: 6,

            pointHoverRadius: 9,

            pointBorderWidth: 3,

        }]
    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        interaction: {
            intersect: false,
            mode: 'index',
        },

        plugins: {

            legend: {
                display: true,
                labels: {
                    color: '#333',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },

            tooltip: {

                backgroundColor: '#222',

                titleColor: '#fff',

                bodyColor: '#fff',

                padding: 12,

                cornerRadius: 10,

                callbacks: {

                    label: function(context) {

                        return ' ' + Number(context.raw)
                            .toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        },

        scales: {

            y: {

                beginAtZero: true,

                ticks: {

                    color: '#666',

                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + 'đ';
                    }
                },

                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },

            x: {

                ticks: {
                    color: '#666'
                },

                grid: {
                    display: false
                }
            }
        },

        animation: {

            duration: 2000,

            easing: 'easeInOutQuart'
        }
    }
});

// ===== MENU =====
function toggleMenu(){

    let menu = document.getElementById('submenu');

    menu.style.display =
        menu.style.display === 'block'
        ? 'none'
        : 'block';
}

</script>

<script>

// ===== STATUS CHART =====
const statusCtx = document
    .getElementById('statusChart');

const statusData = @json($orderStatus);

new Chart(statusCtx, {

    type: 'doughnut',

    data: {

        labels: Object.keys(statusData),

        datasets: [{

            data: Object.values(statusData),

            backgroundColor: [
                '#2196f3',
                '#4caf50',
                '#ff9800',
                '#f44336',
                '#9c27b0'
            ],

            borderWidth: 0
        }]
    },

    options: {

        responsive: true,

        plugins: {

            legend: {
                position: 'bottom'
            }
        }
    }
});

</script>
<script>

// ===== TOP PRODUCT =====
const productCtx =
    document.getElementById('productChart');

const topProducts = @json($topProducts);

new Chart(productCtx, {

    type: 'bar',

    data: {

        labels: topProducts.map(
            p => p.product?.name ?? 'N/A'
        ),

        datasets: [{

            label: 'Đã bán',

            data: topProducts.map(
                p => p.total_sold
            ),

            backgroundColor: '#0b6fc7',

            borderRadius: 10,
        }]
    },

    options: {

        indexAxis: 'y',

        responsive: true,

        plugins: {

            legend: {
                display: false
            }
        }
    }
});

</script>
</body>
</html>