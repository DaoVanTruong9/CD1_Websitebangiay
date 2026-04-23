<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng - Shoes Sport</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #45c8ed; }

        .navbar { background: #0c0000; }

        .order-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: bold;
        }

        .pending { background: orange; color: #fff; }
        .paid { background: green; color: #fff; }
        .cancel { background: red; color: #fff; }


        .nav-link {
            font-weight: 500;
        }

        /* CATEGORY */
        .category {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .category div {
            text-align: center;
            cursor: pointer;
        }
        .category a.active {
            color: #0b6fc7;
            font-weight: bold;
        }
        .category a:hover {
            transform: scale(1.05);
            transition: 0.2s;
        }
    
        .nav-link {
            transition: 0.3s;
        }

        .nav-link:hover {
            color: red !important;
            transform: translateY(-2px);
        }

        .dropdown-menu a:hover {
            background: #f8f9fa;
        }

        input.form-control:focus {
            box-shadow: none;
            border-color: red;
        }
        .flying-img {
    position: fixed;
    z-index: 9999;
    width: 100px;
    height: 100px;
    object-fit: cover;
    pointer-events: none;
    transition: all 0.8s cubic-bezier(.4,-0.3,1,.68);
    border-radius: 10px;
}
.dropdown-menu {
    border-radius: 10px;
}

#mini-cart-body img {
    border: 1px solid #eee;
}
.cart-wrapper {
    position: relative;
}
.mini-cart {
    position: absolute;
    top: 90%;   
    right: 0;

    width: 350px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);

    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: 0.25s;

    z-index: 999;
}
    .mini-cart::before {
    content: "";
    position: absolute;
    top: -10px;
    left: 0;
    width: 100%;
    height: 10px;
}
.cart-wrapper:hover .mini-cart {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.order-wrapper:hover .mini-cart {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.cart-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.cart-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
}

.cart-item .info {
    flex: 1;
    margin-left: 10px;
}

.qty-btn {
    border: none;
    background: #eee;
    padding: 2px 8px;
    cursor: pointer;
}

.remove-btn {
    color: red;
    cursor: pointer;
    font-size: 14px;
}
    </style>
</head>

<body>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm px-3 py-2">
    <div class="container-fluid">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold text-danger" href="/">
            <i class="fa fa-shoe-prints"></i> Shoes Sport
        </a>

        <!-- MENU -->
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                <li class="nav-item">
                    <a class="nav-link " href="/">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/san-pham">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-danger" href="/orders">Đơn hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/orders/history">Lịch sử</a>
                </li>
            </ul>

            <!-- SEARCH -->
            <form class="d-flex me-3" method="GET" action="/">
                <input class="form-control me-2"
                       type="search"
                       name="search"
                       placeholder="Tìm giày..."
                       value="{{ request('search') }}">
                <button class="btn btn-outline-danger">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <!-- USER -->
                <div class="cart-wrapper position-relative me-3">
                    <!-- ICON -->
                    <div id="cart-navbar" class="position-relative">
                        <a href="/cart" class="position-relative me-3 text-dark">
                        <i class="fa fa-shopping-cart fs-5"></i>
                            <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
                            </span>
                        </a>
                    </div>

                    <!-- MINI CART -->
                    <div id="mini-cart" class="mini-cart shadow">
                        <div id="mini-cart-body"></div>
                    </div>

                </div>


                <!-- USER -->
                @if(Auth::check())
                <div class="dropdown">
                    <!-- ĐÃ LOGIN -->
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <i class="fa fa-user me-2"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow">

                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-user-circle me-2"></i> Tài khoản
                                </a>
                            </li>

                            <!-- ADMIN -->
                            @if(Auth::user()->role == 'admin')
                                <li>
                                    <a class="dropdown-item text-primary" href="/admin/dashboard">
                                        <i class="fa fa-cog me-2"></i> Quản trị
                                    </a>
                                </li>
                            @endif

                            <!-- STAFF -->
                            @if(Auth::user()->role == 'staff')
                              <li>
                                    <a class="dropdown-item text-warning" href="/staff/dashboard">
                                        <i class="fa fa-briefcase me-2"></i> Nhân viên
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="fa fa-sign-out-alt me-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    @else

                    <!-- CHƯA LOGIN -->
                    <div class="d-flex align-items-center">
                        <a href="/login" class="me-2 text-decoration-none fw-bold">
                            <i class="fa fa-sign-in-alt"></i> Đăng nhập
                        </a>
                        <span>/</span>
                        <a href="/register" class="ms-2 text-decoration-none fw-bold">
                            Đăng ký</a>
                    </div>

                @endif

            </div>
        </div>
    </div>
</nav>


    <!-- TITLE -->
    <h4 class="text-center mt-5 fw-bold">📦 Đơn hàng của bạn</h4>

    <!-- LIST -->
    <div class="mt-4">

        @forelse($orders as $order)
        <div class="order-card shadow-sm">

            <!-- HEADER -->
            <div class="d-flex justify-content-between mb-2">
                <div>
                    <strong>Mã đơn:</strong> #{{ $order->id }} <br>
                    <small>{{ $order->created_at }}</small>
                </div>

                <!-- STATUS -->
                <div>
                    @if($order->status == 'pending')
                        <span class="status pending">Chờ xử lý</span>
                    @elseif($order->status == 'confirmed')
                        <span class="status paid">Đã xác nhận</span>
                    @elseif($order->status == 'shipping')
                        <span class="status paid">Đang giao</span>
                    @elseif($order->status == 'completed')
                        <span class="status paid">Hoàn thành</span>
                    @else
                        <span class="status cancel">Đã huỷ</span>
                    @endif
                </div>
            </div>

            <hr>

            <!-- CUSTOMER -->
            <div class="mb-2">
                <strong>👤 Khách hàng:</strong> {{ $order->customer_name }} <br>
                <strong>📞 SĐT:</strong> {{ $order->phone }} <br>
                <strong>📍 Địa chỉ:</strong> {{ $order->address }}
            </div>

            <hr>

            <!-- ITEMS -->
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between">
                <span>
                    {{ $item->product->name ?? 'Sản phẩm' }} 
                    x{{ $item->quantity }}
                </span>
                <span>
                    {{ number_format($item->price * $item->quantity) }} đ
                </span>
            </div>
            @endforeach

            <hr>

            <!-- TOTAL -->
            <div class="d-flex justify-content-between fw-bold">
                <span>Tổng tiền</span>
                <span class="price">
                    {{ number_format($order->total_price) }} đ
                </span>
            </div>

            <!-- ACTION -->
            <div class="text-end mt-3">
                <a href="/orders/invoice/{{ $order->id }}" 
                   class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-file-pdf"></i> Xuất hóa đơn
                </a>
                @if($order->status == 'shipping')
                    <form action="/orders/received/{{ $order->id }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm">Đã nhận hàng
                        </button>
                    </form>
                @endif
            </div>

        </div>

        @empty
        <div class="text-center p-5 bg-white rounded shadow-sm">
            <h5>Chưa có đơn hàng nào</h5>
            <a href="/san-pham" class="btn btn-danger mt-3">
                Mua ngay
            </a>
        </div>
        @endforelse

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>