<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo doanh thu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        body{
            margin:0;
            background:#f5f5f5;
        }

        .header{
            height:60px;
            background:#0b6fc7;
            color:white;
            display:flex;
            align-items:center;
            padding:0 20px;
            font-weight:bold;
        }

        .sidebar{
            width:250px;
            height:100vh;
            background:#222;
            position:fixed;
        }

        .sidebar a,
        .sidebar button{
            width:100%;
            padding:12px 20px;
            display:block;
            text-decoration:none;
            color:white;
            background:none;
            border:none;
            text-align:left;
        }

        .sidebar a:hover{
            background:#444;
        }

        .sidebar a.active{
            background:#0b6fc7;
        }

        .submenu{
            padding-left:20px;
        }

        .content{
            margin-left:250px;
            padding:20px;
        }

        .card-box{
            padding:20px;
            border-radius:10px;
            color:white;
        }

        .bg-blue{
            background:#2196f3;
        }

        .bg-green{
            background:#4caf50;
        }

        .bg-orange{
            background:#ff9800;
        }

    </style>
</head>

<body>

<div class="header">
    ADMIN SHOP GIÀY
</div>

<div class="sidebar">

    <a href="/admin/dashboard">🏠 Dashboard</a>

    <a href="/admin/products">👟 Quản lý sản phẩm</a>

    <a href="/admin/imports">📦 Quản lý nhập hàng</a>

    <a href="/admin/coupons">🎁 Quản lý khuyến mãi</a>

    <a href="/admin/users">👤 Quản lý nhân viên</a>

    <a href="#">📊 Báo cáo</a>

    <div class="submenu">

        <a href="/admin/revenue" class="active">
            - Doanh thu
        </a>

        <a href="/admin/best-selling">
            - Sản phẩm bán chạy
        </a>

    </div>

</div>

<div class="content">

    <h3 class="mb-4">Báo cáo doanh thu</h3>

    <div class="mb-3">
        <a href="/admin/revenue/export" class="btn btn-success">
            📥 Xuất Excel</a>
    </div>

    <div class="card p-3 mb-4">

    <form method="GET">

        <div class="row align-items-end">

            <div class="col-md-3">

                <label class="form-label fw-bold">
                    Chọn tháng
                </label>

                <select name="month" class="form-select">

                    @for($i = 1; $i <= 12; $i++)

                        <option value="{{ $i }}"
                            {{ $month == $i ? 'selected' : '' }}>

                            Tháng {{ $i }}

                        </option>

                    @endfor

                </select>

            </div>

            <div class="col-md-2">

                <button class="btn btn-primary w-100">
                    Xem báo cáo
                </button>

            </div>

        </div>

    </form>

</div>
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card-box bg-blue">
                Tổng doanh thu
                <h3>{{ number_format($totalRevenue) }}đ</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg-green">
                Doanh thu hôm nay
                <h3>{{ number_format($todayRevenue) }}đ</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box bg-orange">
                Doanh thu tháng này
                <h3>{{ number_format($thisMonthRevenue) }}đ</h3>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0 p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="fw-bold">
            📈 Doanh thu tháng {{ $month }}
        </h5>

        <span class="badge bg-primary">
            Theo ngày
        </span>

    </div>

    <div style="height:400px;">
        <canvas id="chart"></canvas>
    </div>

</div>
<div class="card p-4 mb-4">

    <h5 class="mb-3">
        📅 Doanh thu từng ngày trong tháng {{ $month }}
    </h5>

    <table class="table table-bordered">

        <thead class="table-dark">

            <tr>
                <th>Ngày</th>
                <th>Doanh thu</th>
            </tr>

        </thead>

        <tbody>

            @forelse($dailyRevenue as $day => $total)

            <tr>

                <td>
                    {{ $day }}/{{ $month }}
                </td>

                <td>
                    {{ number_format($total) }}đ
                </td>

            </tr>

            @empty

            <tr>

                <td colspan="2" class="text-center">
                    Không có dữ liệu
                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

    <div class="card p-4">

        <h5>Đơn hàng gần đây</h5>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>

            <tbody>

                @foreach($latestOrders as $o)

                <tr>
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->customer_name }}</td>
                    <td>{{ number_format($o->total_price) }}đ</td>
                    <td>{{ $o->status }}</td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

<script>

const ctx = document.getElementById('chart').getContext('2d');

const revenueData = @json($dailyRevenue);

const labels = Object.keys(revenueData).map(
    d => 'Ngày ' + d
);

const data = Object.values(revenueData);

// ===== GRADIENT =====
const gradient = ctx.createLinearGradient(0, 0, 0, 400);

gradient.addColorStop(0, 'rgba(11,111,199,0.4)');
gradient.addColorStop(1, 'rgba(11,111,199,0.02)');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: labels,

        datasets: [{

            label: 'Doanh thu',

            data: data,

            fill: true,

            backgroundColor: gradient,

            borderColor: '#0b6fc7',

            borderWidth: 4,

            tension: 0.4,

            pointRadius: 5,

            pointHoverRadius: 8,

            pointBackgroundColor: '#0b6fc7',

        }]
    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            tooltip: {

                callbacks: {

                    label: function(context){

                        return Number(context.raw)
                            .toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        },

        scales: {

            y: {

                beginAtZero: true,

                ticks: {

                    callback: function(value){

                        return value.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});

</script>

</body>
</html>