<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kiểm tra tồn kho</title>

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

        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #444;
        }

        .sidebar a.active {
            background: #000;
            font-weight: bold;
            border-left: 4px solid #179bf9;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            background: #333;
            transition: max-height 0.3s ease;
            padding-left: 20px;
        }

        .submenu.active {
            max-height: 200px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .card-box {
            background: white;
            border-radius: 10px;
            padding: 15px;
        }

        .badge-low { background: red; }
        .badge-ok { background: green; }
        .badge-out { background: gray; }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    NHÂN VIÊN - KIỂM TRA TỒN KHO
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

    <a href="/staff/inventory" class="active">📦 Kiểm tra tồn kho</a>

    <a href="/staff/promotion">🏷️ Khuyến mãi</a>

    <form method="POST" action="/logout">
        @csrf
        <button class="btn btn-danger w-100 mt-2">🚪 Đăng xuất</button>
    </form>

</div>

<!-- CONTENT -->
<div class="content">

    <h3 class="mb-4">📦 Tồn kho sản phẩm</h3>

    <div class="card-box shadow-sm">

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Tồn kho</th>
                    <th>Đã bán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $p)
                @php
                    $qty = $p->inventory->quantity ?? 0;
                    $sold = $p->inventory->sold_quantity ?? 0;

                    if ($qty == 0) {
                        $status = 'Hết hàng';
                        $class = 'badge-out';
                    } elseif ($qty < 5) {
                        $status = 'Sắp hết';
                        $class = 'badge-low';
                    } else {
                        $status = 'Còn hàng';
                        $class = 'badge-ok';
                    }
                @endphp

                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $qty }}</td>
                    <td>{{ $sold }}</td>
                    <td>
                        <span class="badge {{ $class }}">
                            {{ $status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

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