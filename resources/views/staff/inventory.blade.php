<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý nhập hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body { margin: 0; }

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

        .sidebar a:hover, .sidebar button:hover {
            background: #444;
        }

        .sidebar a.active {
            background: #000;
            font-weight: bold;
            border-left: 4px solid #0b6fc7;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .card-box {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
        }
    </style>
</head>

<body>

<div class="header">Nhân viên SHOP GIÀY</div>

<!-- SIDEBAR -->
<div class="sidebar">

    <a href="/staff/dashboard">🏠 Dashboard</a>

    <a href="/staff/products">👟 Quản lý sản phẩm</a>

    <a href="/staff/inventory" class="active">📦 Quản lý nhập hàng</a>

    <a href="/staff/promotions">🎁 Quản lý khuyến mãi</a>

    <a href="/staff/staff">👤 Quản lý nhân viên</a>

    <a href="/staff/reports">📊 Báo cáo</a>

    <form method="POST" action="/logout">
        @csrf
        <button>🚪 Đăng xuất</button>
    </form>

</div>

<!-- CONTENT -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📦 Tồn kho sản phẩm</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Tồn kho</th>
                    <th>Đã bán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->inventory->quantity ?? 0 }}</td>
                    <td>{{ $p->inventory->sold_quantity ?? 0 }}</td>
                    <td>{{ $p->inventory->status ?? 'N/A' }}</td>
                </tr>
                 @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>