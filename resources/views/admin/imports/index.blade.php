<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý nhập hàng</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
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

<!-- HEADER -->
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

    <a href="#" class="">
        🎁 Quản lý khuyến mãi
    </a>

    <a href="#" class="">
        👤 Quản lý nhân viên
    </a>

    <a href="/admin/reports" class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📦 Quản lý nhập hàng</h3>

        <!-- BUTTON -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
            + Nhập hàng
        </button>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="card-box">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá nhập</th>
                    <th>Tổng</th>
                    <th>Ngày</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($imports as $import)
                <tr>
                    <td>#{{ $import->id }}</td>
                    <td>{{ $import->product->name }}</td>
                    <td>{{ $import->quantity }}</td>
                    <td>{{ number_format($import->cost_price) }} đ</td>
                    <td>{{ number_format($import->total_cost) }} đ</td>
                    <td>{{ $import->created_at }}</td>
                    <td>
                        <a href="/admin/imports/delete/{{ $import->id }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Xóa phiếu nhập này?')">
                           Xóa
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="/admin/imports/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Nhập hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- PRODUCT -->
                    <label>Sản phẩm</label>
                    <select name="product_id" class="form-control mb-2">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>

                    <!-- QUANTITY -->
                    <label>Số lượng</label>
                    <input type="number" name="quantity" class="form-control mb-2" required>

                    <!-- COST -->
                    <label>Giá nhập</label>
                    <input type="number" name="cost_price" class="form-control mb-2" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success w-100">Lưu phiếu nhập</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>