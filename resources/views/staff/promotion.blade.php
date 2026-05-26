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
        <form method="POST" action="/staff/promotion/apply">
            @csrf

            <div class="mb-3">
                <label class="form-label">Chọn mã khuyến mãi</label>

                <select name="coupon_id" class="form-select"required>
                    <option value="">-- Chọn mã --</option>
                    @foreach($coupons as $c)
                        <option value="{{ $c->id }}"> {{ $c->code }} - {{ $c->discount }}%</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-warning w-100">🏷️ Áp dụng mã</button>
        </form>
        <hr class="my-4">

<h5 class="mb-3">🎁 Mã khuyến mãi đang kích hoạt</h5>

<table class="table table-bordered table-hover bg-white">
    <thead class="table-warning">
        <tr>
            <th>Mã</th>
            <th>Giảm giá</th>
            <th>Số lượng</th>
            <th>Hết hạn</th>
            <th>Trạng thái</th>
        </tr>
    </thead>

    <tbody>

    @forelse($coupons as $c)

        @php
            $expired = now()->gt($c->expired_at);
        @endphp

        @if(!$expired && $c->status == 'active')

        <tr>
            <td>
                <strong class="text-primary">
                    {{ $c->code }}
                </strong>
            </td>

            <td>
                {{ $c->discount }}%
            </td>

            <td>
                {{ $c->quantity }}
            </td>

            <td>
                {{ \Carbon\Carbon::parse($c->expired_at)->format('d/m/Y') }}
            </td>

            <td>
                <span class="badge bg-success">
                    Đang hoạt động
                </span>
            </td>
        </tr>

        @endif

    @empty

        <tr>
            <td colspan="5" class="text-center text-muted">
                Không có mã khuyến mãi nào
            </td>
        </tr>

    @endforelse

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

<script>
function applyCoupon(){

    let code =
        document.getElementById('coupon_code').value;

    fetch('/apply-coupon', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            code: code
        })

    })
    .then(res => res.json())
    .then(data => {

        let box =
            document.getElementById('coupon-message');

        if(data.success){

            box.innerHTML = `
                <div class="alert alert-success">
                    Áp dụng mã ${data.code}
                    thành công (-${data.discount}%)
                </div>
            `;
            location.reload();
        }else{
            box.innerHTML = `
                <div class="alert alert-danger">
                    ${data.message}
                </div>
            `;
        }

    });

}

</script>

</body>
</html>