<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm bán chạy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .sidebar a{
            width:100%;
            padding:12px 20px;
            display:block;
            color:white;
            text-decoration:none;
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

        <a href="/admin/revenue">
            - Doanh thu
        </a>

        <a href="/admin/best-selling" class="active">
            - Sản phẩm bán chạy
        </a>

    </div>

</div>

<div class="content">

    <h3 class="mb-4">Top sản phẩm bán chạy</h3>

    <div class="mb-3">
        <a href="/admin/best-selling/export" class="btn btn-success">
            📥 Xuất Excel</a>
    </div>

    <div class="card p-4">

        <table class="table table-bordered">

            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Đã bán</th>
                </tr>
            </thead>

            <tbody>

                @foreach($topProducts as $key => $item)

                <tr>

                    <td>{{ $key + 1 }}</td>

                    <td>
                        {{ $item->product->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $item->total_sold }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        <div class="mt-3">
            {{ $topProducts->links() }}
        </div>

    </div>

</div>

</body>
</html>