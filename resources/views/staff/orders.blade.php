<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Staff - Quản lý đơn hàng</title>

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
            padding-left: 20px;
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
            color: white;
            padding: 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .card-box:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>

<div class="header">NHÂN VIÊN - QUẢN LÝ ĐƠN HÀNG</div>

<div class="sidebar">

    <a href="/staff/dashboard">🏠 Dashboard</a>

    <a href="javascript:void(0)" onclick="toggleMenu()">📦 Xử lý đơn hàng</a>
    <div class="submenu active" id="submenu">
        <a href="/staff/returns">🔄 Trả hàng</a>
        <a href="/staff/exchanges">🔁 Đổi hàng</a>
        <a href="/staff/orders" style="background: #2196f3">📦 Cập nhật đơn</a>
    </div>

    <a href="/staff/inventory">📦 Kiểm tra tồn kho</a>

    <a href="/staff/promotion">🏷️ Khuyến mãi</a>

    <form method="POST" action="/logout">
        @csrf
        <button class="btn btn-danger w-100 mt-2">🚪 Đăng xuất</button>
    </form>

</div>

<div class="content">

    <h3 class="mb-4">Danh sách đơn hàng</h3>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card p-3">
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Ngày</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                    <th>Thanh toán</th>
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $o)
                <tr class="align-middle">
                    <td>{{ $o->customer_name }}</td>
                    <td>{{ $o->phone }}</td>
                    <td>{{ $o->address }}</td>

                    <td class="text-danger fw-bold">
                        {{ number_format($o->total_price) }} đ
                    </td>

                    <td>{{ $o->created_at }}</td>

                    {{-- TRẠNG THÁI --}}
                    <td class="text-center">
                        @if($o->payment_method == 'bank' && $o->payment_status == 'pending')
                            <form action="/orders/confirm-payment/{{ $o->id }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-success">
                                    Xác nhận đã thanh toán
                                </button>
                            </form>
                        @endif
                        @if($o->status == 'pending')
                            <span class="status pending">Chờ xử lý</span>

                        @elseif($o->status == 'confirmed')
                            <span class="status paid">Đã xác nhận</span>

                        @elseif($o->status == 'shipping')
                            <span class="status paid">Đang giao</span>

                        @elseif($o->status == 'completed')
                            <span class="status paid">Hoàn thành</span>

                        @else
                            <span class="status cancel">Đã huỷ</span>
                        @endif
                    </td>

                    {{-- HÀNH ĐỘNG --}}
                    <td class="text-center">
                        @if($o->status == 'confirmed')
                            <form action="/staff/orders/status/{{ $o->id }}" method="POST" style="display:inline">
                                @csrf
                                <input type="hidden" name="status" value="shipping">
                                <button class="btn btn-sm btn-info">Giao</button> 
                            </form>
                        @endif
                        
                        {{-- chỉ pending hoặc confirmed mới được hủy --}}
                        @if(in_array($o->status, ['pending', 'confirmed']))
                            <form method="POST" action="/staff/orders/status/{{ $o->id }}" style="display:inline">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xác nhận hủy đơn?')">
                                    Hủy
                                </button>
                            </form>
                        @endif

                        {{-- completed --}}
                        @if($o->status == 'completed')
                            <button class="btn btn-secondary btn-sm" disabled>
                                Đã hoàn thành
                            </button>
                        @endif

                    </td>

                    <td class="text-center">

    {{-- ĐÃ THANH TOÁN (ưu tiên hiển thị) --}}
    @if($o->payment_status == 'paid')
        <span class="badge bg-success">Đã thanh toán</span>

    {{-- COD chưa thanh toán --}}
    @elseif($o->payment_method == 'cod')
        <span class="badge bg-secondary">Thanh toán khi nhận</span>

    {{-- BANK chưa thanh toán --}}
    @elseif($o->payment_method == 'bank')
        <span class="badge bg-warning">Chờ CK</span>

    {{-- fallback (tránh trắng) --}}
    @else
        <span class="badge bg-dark">Không rõ</span>
    @endif

    {{-- NÚT DUYỆT --}}
    @if (
        ($o->payment_method == 'cod' && $o->status == 'pending') ||
        ($o->payment_method == 'bank' && $o->payment_status == 'paid' && $o->status == 'pending')
    )
        <form action="/staff/orders/confirm/{{ $o->id }}" method="POST" style="display:inline;">
            @csrf
            <button class="btn btn-success btn-sm">
                Duyệt đơn
            </button>
        </form>
    @endif

</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleMenu() {
    let menu = document.getElementById("submenu");
    menu.classList.toggle("active");
}
</script>
<script>
setTimeout(function() {
    let alert = document.querySelector('.alert');
    if (alert) {
        let bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }
}, 2500);
</script>

</body>
</html>