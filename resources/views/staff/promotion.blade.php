<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khuyến mãi</title>

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

        .submenu {
            max-height: 0;
            overflow: hidden;
            background: #333;
            transition: max-height 0.3s ease;
        }

        .submenu.active {
            max-height: 200px;
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

        .content {
            margin-left: 250px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .card-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    NHÂN VIÊN - ÁP DỤNG KHUYẾN MÃI
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

    <a href="/staff/inventory">📦 Kiểm tra tồn kho</a>

    <a href="/staff/promotion" style="background:#444;">🏷️ Khuyến mãi</a>

    <form method="POST" action="/logout">
        @csrf
        <button class="btn btn-danger w-100 mt-2">🚪 Đăng xuất</button>
    </form>

</div>

<!-- CONTENT -->
<div class="content">

    <h3 class="mb-4">Áp dụng mã khuyến mãi</h3>

    <div class="card-box">

        <!-- thông báo -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="/staff/apply-coupon">
            @csrf

            <div class="mb-3">
                <label class="form-label">Chọn mã khuyến mãi</label>
                <select name="coupon_id" class="form-select" required>
                    <option value="">-- Chọn mã --</option>
                    @foreach($coupons as $c)
                        <option value="{{ $c->id }}">
                            {{ $c->code }} - {{ $c->discount }}%
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-warning w-100">
                🏷️ Áp dụng mã
            </button>
        </form>

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