<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - Shoes Sport</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #45c8ed; }

        .navbar { background: #0c0000; }

        .cart-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
        }

        .price {
            color: red;
            font-weight: bold;
        }

        .nav-link:hover {
            color: red !important;
            transform: translateY(-2px);
        }
        .cart-item {
    background: #fff;
    border-radius: 10px;
    padding: 10px;
    transition: 0.3s;
}

.cart-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.cart-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.qty-box {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.qty-box button {
    border: none;
    background: #f8f8f8;
    width: 30px;
}

.qty-box span {
    width: 30px;
    text-align: center;
}

.checkout-box {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    position: sticky;
    top: 20px;
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
                        <a class="nav-link active fw-bold text-danger" href="/cart">Giỏ hàng</a>
                    </li>
                </ul>

                <!-- USER -->
                <div class="d-flex align-items-center">
                    <i class="fa fa-shopping-cart me-3 fs-5 text-danger"></i>

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
    <h4 class="text-center mt-5 fw-bold">🛒 Giỏ hàng của bạn</h4>

    <div class="mt-4 cart-card shadow-sm">

        @if(!empty($cart))

        <div class="row">

    <!-- LEFT: LIST -->
    <div class="col-md-8">

    @php $total = 0; @endphp

    @foreach($cart as $key => $item)

    <div class="cart-item d-flex align-items-center mb-3">

        <!-- IMAGE -->
        <img src="{{ asset('images/' .$item['image']) }}" class="cart-img">

        <!-- INFO -->
        <div class="ms-3 flex-grow-1">
            <h6 class="mb-1">{{ $item['name'] }}</h6>
            <small class="text-muted">Size: {{ $item['size'] }}</small>

            <div class="price mt-1">
                {{ number_format($item['price']) }} đ
            </div>
        </div>

        <!-- QUANTITY -->
        <div class="qty-box">
            <button onclick="updateQty('{{ $key }}', -1)">-</button>
            <span id="qty-{{ $key }}">{{ $item['quantity'] }}</span>
            <button onclick="updateQty('{{ $key }}', 1)">+</button>
        </div>

        <!-- TOTAL -->
        <div class="price ms-3" id="total-{{ $key }}">
            {{ number_format($item['price'] * $item['quantity']) }} đ
        </div>

        <!-- DELETE -->
        <button class="btn btn-sm btn-danger ms-3"
                onclick="removeItem('{{ $key }}')">
            <i class="fa fa-trash"></i>
        </button>

    </div>

    @php
        $total += $item['price'] * $item['quantity'];
    @endphp

    @endforeach

</div>
    <!-- RIGHT -->
<div class="col-md-4">

    <div class="checkout-box">

        <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>

        <div class="d-flex justify-content-between mb-2">
            <span>Tạm tính</span>
            <span id="cart-total">{{ number_format($total) }} đ</span>
        </div>

        <hr>

        <div class="d-flex justify-content-between fw-bold mb-3">
            <span>Tổng</span>
            <span class="text-danger" id="cart-total">{{ number_format($total) }} đ</span>
        </div>

        <a href="/checkout" class="btn btn-success w-100">
            <button class="btn btn-success w-100">Thanh toán</button>
        </a>

    </div>

</div>
</div>
        <!-- TOTAL -->
            <a href="/san-pham" class="btn btn-outline-secondary">
                ← Tiếp tục mua
            </a>

            <h5 class="fw-bold">
                Tổng tiền: <span class="text-danger">
                    {{ number_format($total) }} đ
                </span>
            </h5>
        </div>

        @else

        <!-- EMPTY -->
        <div class="text-center p-5">
            <h5>🛒 Giỏ hàng trống</h5>
            <a href="/san-pham" class="btn btn-danger mt-3">
                Mua ngay
            </a>
        </div>

        @endif

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function updateQty(id, change) {

    fetch('/cart/update-qty', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key: id, change:change }) // ✅ đúng key
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {

            document.getElementById('qty-' + id).innerText = data.qty;
            document.getElementById('total-' + id).innerText = data.item_total;
            document.getElementById('cart-total').innerText = data.cart_total;

            // update icon cart nếu có
            const cartCount = document.getElementById('cart-count');
            if (cartCount && data.cart_count) {
                cartCount.innerText = data.cart_count;
            }

        } else {
            location.reload(); // fallback nếu item bị xoá
        }

    });
}
function removeItem(id) {

    fetch('/cart/remove-mini', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key: id })
    })
    .then(() => location.reload());

}
</script>
</body>
</html>