<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin: 0; }

        .header {
            height: 60px;
            background: #0b6fc7;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #222;
            position: fixed;
        }

        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover { background: #444; }

        .content {
            margin-left: 250px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }
    </style>
</head>

<body>

<div class="header">QUẢN LÝ ĐƠN HÀNG</div>

<div class="sidebar">
    <a href="/dashboard">🏠 Dashboard</a>
    <a href="/products">👟 Sản phẩm</a>
    <a href="/orders">📦 Đơn hàng</a>
    <a href="#">👤 Khách hàng</a>
    <a href="#">🔐 Tài khoản</a>
    <a href="#">📊 Báo cáo</a>
    <a href="/logout">🚪 Đăng xuất</a>
</div>

<div class="content">

    <h3 class="mb-4">Danh sách đơn hàng</h3>

    <div class="card p-3">
        <table class="table table-bordered">
            <thead class="table-dark">
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
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td>{{ $o->id }}</td>
                    <td>{{ $o->customer_name }}</td>
                    <td>{{ $o->phone }}</td>
                    <td>{{ $o->address }}</td>
                    <td class="text-danger fw-bold">
                        {{ number_format($o->total_price) }} đ
                    </td>
                    <td>{{ $o->created_at }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#order{{ $o->id }}">
                            Xem
                        </button>
                    </td>
                </tr>

                <!-- MODAL CHI TIẾT -->
                <div class="modal fade" id="order{{ $o->id }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5>Chi tiết đơn hàng #{{ $o->id }}</h5>
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
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($o->items as $item)
                                        <td>
                                        @if($o->status == 'pending')
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                        @elseif($o->status == 'processing')
                                            <span class="badge bg-info">Đang giao</span>
                                        @elseif($o->status == 'completed')
                                            <span class="badge bg-success">Hoàn thành</span>
                                        @else
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @endif
                                        </td>

                                        <td>
                                            <a href="/orders/status/{{ $o->id }}/processing" class="btn btn-sm btn-info">Giao</a>
                                            <a href="/orders/status/{{ $o->id }}/completed" class="btn btn-sm btn-success">Xong</a>
                                            <a href="/orders/status/{{ $o->id }}/cancelled" class="btn btn-sm btn-danger">Hủy</a>
                                        </td>
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

                @endforeach
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>