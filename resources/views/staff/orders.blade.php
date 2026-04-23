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
    <div class="submenu" id="submenu">
        <a href="/staff/returns">🔄 Trả hàng</a>
        <a href="/staff/returns">🔁 Đổi hàng</a>
        <a href="/staff/orders">📦 Cập nhật đơn</a>
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
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card p-3">
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Ngày</th>
                    <th>Chi tiết</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                    <th>Thanh toán</th>
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $o)
                <tr class="align-middle">
                    <td>{{ $o->id }}</td>
                    <td>{{ $o->customer_name }}</td>
                    <td>{{ $o->phone }}</td>
                    <td>{{ $o->address }}</td>

                    <td class="text-danger fw-bold">
                        {{ number_format($o->total_price) }} đ
                    </td>

                    <td>{{ $o->created_at }}</td>

                    {{-- XEM CHI TIẾT --}}
                    <td class="text-center">
                        <button class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#order{{ $o->id }}">
                            Xem
                        </button>
                    </td>

                    {{-- TRẠNG THÁI --}}
                    <td class="text-center">
                        @if($o->payment_method == 'bank' && $o->payment_status == 'pending')
                            <a href="/orders/confirm-payment/{{ $o->id }}"
                                class="btn btn-sm btn-success">Xác nhận đã thanh toán</a>
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
                        @if($o->status != 'completed')
                            <form action="/staff/orders/status/{{ $o->id }}" method="POST" style="display:inline">
                                @csrf
                                <input type ="hidden" name="status" value="shipping">
                               <button class="btn btn-sm btn-info">Giao</button> 
                            </form>
                            <a href="/orders/status/{{ $o->id }}/completed"
                               class="btn btn-sm btn-success">Xong</a>

                            <a href="/orders/status/{{ $o->id }}/cancelled"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Xác nhận hủy đơn?')">
                               Hủy
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                Đã hoàn thành
                            </button>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($o->payment_method == 'cod')
                            <span class="badge bg-secondary">COD</span>
                        @else
                        @if($o->payment_status == 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-warning">Chờ CK</span>
                        @endif
                        @endif
                        @if($o->payment_status == 'paid' && $o->status == 'pending')
                            <form action="/staff/orders/confirm/{{ $o->id }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm">
                                    Duyệt đơn
                                </button>
                            </form>
                            <form method="POST" action="/staff/orders/status/{{ $o->id }}">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-danger btn-sm">Hủy</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
                {{-- MODAL CHI TIẾT --}}
                <div class="modal fade" id="order{{ $o->id }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Chi tiết đơn hàng #{{ $o->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <p><b>Khách:</b> {{ $o->customer_name }}</p>
                                <p><b>SĐT:</b> {{ $o->phone }}</p>
                                <p><b>Địa chỉ:</b> {{ $o->address }}</p>

                                <hr>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Giá</th>
                                            <th>Tổng</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($o->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price) }} đ</td>
                                            <td>
                                                {{ number_format($item->price * $item->quantity) }} đ
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <h5 class="text-end text-danger">
                                    Tổng: {{ number_format($o->total_price) }} đ
                                </h5>

                            </div>

                        </div>
                    </div>
                </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleMenu() {
    let menu = document.getElementById("submenu");
    menu.classList.toggle("active");
}
</script>
</body>
</html>