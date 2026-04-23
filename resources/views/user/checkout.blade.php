<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - Shoes Sport</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #45c8ed; }

        .navbar { background: #0c0000; }

        .checkout-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
        }

        .price {
            color: red;
            font-weight: bold;
        }

        .nav-link:hover {
            color: red !important;
            transform: translateY(-2px);
        }

        .form-control {
            border-radius: 8px;
        }

        .order-item {
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm px-3 py-2">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold text-danger" href="/">
                <i class="fa fa-shoe-prints"></i> Shoes Sport
            </a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/san-pham">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-danger" href="/checkout">Thanh toán</a>
                    </li>
                </ul>

                <!-- USER -->
                <div class="d-flex align-items-center">
                    <a href="/cart" class="me-3 text-dark">
                        <i class="fa fa-shopping-cart fs-5"></i>
                    </a>

                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
                           data-bs-toggle="dropdown">
                            <i class="fa fa-user me-2"></i>
                            <span>{{ Auth::user()->name ?? 'User' }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#">Tài khoản</a></li>
                            <li><a class="dropdown-item" href="#">Đơn hàng</a></li>
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
                </div>

            </div>
        </div>
    </nav>

    <!-- TITLE -->
    <h4 class="text-center mt-5 fw-bold">🧾 Thanh toán</h4>

    <div class="row mt-4">

        <!-- FORM -->
        <div class="col-md-7">
            <div class="checkout-card shadow-sm">

                <h5 class="fw-bold mb-3">Thông tin khách hàng</h5>

                <form action="/checkout" method="POST">
    @csrf

    <div class="mb-3">
        <label class="fw-bold">Họ và tên</label>
        <input type="text" name="customer_name" 
               class="form-control"
               placeholder="Nguyễn Văn A" required>
    </div>

    <div class="mb-3">
        <label class="fw-bold">Số điện thoại</label>
        <input type="text" name="phone" 
               class="form-control"
               placeholder="098xxxxxxx" required>
    </div>

    <div class="mb-3">
        <label class="fw-bold">Địa chỉ nhận hàng</label>
        <textarea name="address" 
                  class="form-control"
                  rows="3"
                  placeholder="Số nhà, đường, phường/xã, quận/huyện..." required></textarea>
    </div>

    <!-- MÃ GIẢM GIÁ -->
    <div class="mb-3">
        <label class="fw-bold">Mã khuyến mãi</label>
        <input type="text" name="coupon"
               class="form-control"
               placeholder="Nhập mã nếu có">
    </div>

    <!-- PHƯƠNG THỨC -->
    <div class="mb-3">
        <label class="fw-bold">Phương thức thanh toán</label>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment" value="cod" checked>
            <label class="form-check-label">Thanh toán khi nhận hàng (COD)</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment" value="bank">
                <label class="form-check-label">Thanh toán chuyển khoản (QR)</label>
        </div>
    </div>

    <button class="btn btn-success w-100 py-2 fw-bold">
        🛍️ Đặt hàng ngay
    </button>
</form>
@if($errors->any())
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
            </div>
        </div>

        <!-- ORDER SUMMARY -->
        <div class="col-md-5">
            <div class="checkout-card shadow-sm">

                <h5 class="fw-bold mb-3">Đơn hàng của bạn</h5>

                @php $total = 0; @endphp

                @foreach($cart as $item)
                    <div class="d-flex justify-content-between mb-2 order-item">
                        <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                        <span>
                            {{ number_format($item['price'] * $item['quantity']) }} đ
                        </span>
                    </div>

                    @php
                        $total += $item['price'] * $item['quantity'];
                    @endphp
                @endforeach

                <hr>

                <div class="d-flex justify-content-between fw-bold">
                    <span>Tổng tiền</span>
                    <span class="price">{{ number_format($total) }} đ</span>
                </div>

                <a href="/cart" class="btn btn-outline-secondary w-100 mt-3">
                    ← Quay lại giỏ hàng
                </a>

            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>