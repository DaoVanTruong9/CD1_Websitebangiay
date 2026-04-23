<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shoes Sport - Lịch sử mua hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #45c8ed; }

        .navbar { background: #0c0000; padding: 15px 0; }
        
        .order-card {
            background: #fff;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 10px;
            max-width: 1300px;
            margin-left: auto;
            margin-right: auto;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .pending { background: orange; color: #fff; }
        .paid { background: green; color: #fff; }

        .product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .product-item img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .card-header {
            background: #f8f9fa;
        }
        h6 {
            margin-top: 10px;
        }

        .form-check {
            margin-bottom: 5px;
        }

        hr {
            margin: 15px 0;
        }
        .list-group-item.active {
            background: #dc3545;
            border-color: #dc3545;
        }

        .list-group-item:hover {
            background: #f1f1f1;
        }
        .product-card {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            transition: 0.3s;
            height: 100%;
            cursor: pointer;
        }
        /* overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            opacity: 0;
            transition: 0.3s;
        }

        /* hiện khi hover */
        .product-card:hover .overlay {
            opacity: 1;
        }

        /* button icon */
        .overlay a {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .product-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            transition: 0.3s;
        }

        .price { color: red; font-weight: bold; }

        .nav-link:hover {
            color: red !important;
            transform: translateY(-2px);
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
                            <a class="nav-link" href="/orders/my">Đơn hàng</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active fw-bold text-danger" href="/orders/history">Lịch sử</a>
                </li>
                </ul>

                <!-- SEARCH -->
                <form class="d-flex me-3" method="GET">
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

                    {{-- User --}}
                    @if(Auth::check())
                    <div class="dropdown">

        <!-- ĐÃ LOGIN -->
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

    @else

        <!-- CHƯA LOGIN -->
        <div>
            <a href="/login" class="me-2 text-decoration-none">Đăng nhập</a>
            <a href="/register" class="text-decoration-none">Đăng ký</a>
        </div>

    @endif
</div>
                </div>

            </div>
        </div>
    </nav>

<h4 class="text-center mt-5 fw-bold">📜 Lịch sử mua hàng</h4>

<div class="mt-4">

@forelse($orders as $order)

<div class="order-card shadow-sm mb-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between mb-2">
        <div>
            <strong>Đơn #{{ $order->id }}</strong><br>
            <small>{{ $order->created_at }}</small>
        </div>

        <div>
            @if($order->status == 'completed')
                <span class="status paid">Hoàn thành</span>
            @elseif($order->status == 'shipping')
                <span class="status paid">Đang giao</span>
            @else
                <span class="status pending">Khác</span>
            @endif
        </div>
    </div>

    <hr>

    <!-- ITEMS -->
    @foreach($order->items as $item)

    <div class="product-item">

        <div class="d-flex align-items-center">
            <img src="{{ asset('images/' . ($item->product->image ?? '')) }}">
            <div>
                <div class="fw-bold">
                    {{ $item->product->name ?? 'Sản phẩm' }}
                </div>
                <small>x{{ $item->quantity }}</small>
            </div>
        </div>

        <button type="button"
                class="btn btn-warning btn-sm review-btn"
                data-bs-toggle="modal"
                data-bs-target="#reviewModal"
                data-product="{{ $item->product_id }}"
                data-order="{{ $order->id }}">
            ⭐ Đánh giá
        </button>

    </div>

    @endforeach

    <hr>

    <div class="text-end fw-bold text-danger">
        Tổng: {{ number_format($order->total_price) }} đ
    </div>

</div>

@empty
<div class="text-center p-5 bg-white rounded shadow-sm">
    <h5>Chưa có lịch sử mua hàng</h5>
</div>
@endforelse

</div>

</div>

<!-- MODAL (CHỈ 1 CÁI DUY NHẤT) -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="/review/store" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Đánh giá sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="product_id" id="review-product">
                    <input type="hidden" name="order_id" id="review-order">

                    <label>Số sao</label>
                    <select name="rating" class="form-control mb-2">
                        <option value="5">⭐⭐⭐⭐⭐</option>
                        <option value="4">⭐⭐⭐⭐</option>
                        <option value="3">⭐⭐⭐</option>
                        <option value="2">⭐⭐</option>
                        <option value="1">⭐</option>
                    </select>

                    <textarea name="comment" class="form-control" placeholder="Nhận xét..."></textarea>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success w-100">Gửi đánh giá</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const reviewModal = document.getElementById('reviewModal');

reviewModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const productId = button.getAttribute('data-product');
    const orderId = button.getAttribute('data-order');

    document.getElementById('review-product').value = productId;
    document.getElementById('review-order').value = orderId;
});
</script>

</body>
</html>