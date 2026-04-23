<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shoes Sport</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #45c8ed;
        }

        /* NAVBAR */
        .navbar {
            background: #0c0000;
            padding: 15px 0;
        }

        .nav-link {
            font-weight: 500;
        }

        /* BANNER */
        .banner-slide {
            border-radius: 15px;
            overflow: hidden;
        }

        .banner {
            background: linear-gradient(90deg, #f8a5c2, #fbc2eb);
            min-height: 250px;
            position: relative;
            display: flex;
            align-items: center;
            padding: 50px;
        }

        .banner-content {
            z-index: 2;
            max-width: 50%;
        }

        .banner img {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%) rotate(-10deg);
            width: 260px;
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
        /* PRODUCT */
        .product-card {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            transition: 0.3s;
            height: 100%;
            cursor: pointer;
        }

        .product-card .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);

            opacity: 0;
            transition: 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card:hover .overlay {
            opacity: 1;
        }
        .product-card img {
            transform: scale(1.05);
            transition: 0.3s;
            width: 100%;
            display: block;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }
        .product-card .badge {
            z-index: 10;
        }
        .price {
            color: red;
            font-weight: bold;
        }

        .badge-sale {
            position: absolute;
            top: 10px;
            left: 10px;
            background: red;
            color: #fff;
            font-size: 12px;
            padding: 3px 6px;
            border-radius: 5px;
            z-index: 10;
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
                    <a class="nav-link active fw-bold text-danger" href="/">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/san-pham">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/orders/my">Đơn hàng</a>
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

    <!-- BANNER -->
    <div id="bannerCarousel" class="carousel slide banner-slide mt-3" data-bs-ride="carousel" data-bs-interval="2500">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <div class="banner">
                    <div class="banner-content">
                        <h4>Giảm giá lớn</h4>
                        <h1 class="fw-bold">SALE 50%</h1>
                    </div>
                    <img src="{{ asset('images/anh_banner.png') }}">
                </div>
            </div>

            <div class="carousel-item">
                <div class="banner" style="background: linear-gradient(90deg, #74ebd5, #ACB6E5);">
                    <div class="banner-content">
                        <h4>Mua ngay</h4>
                        <h1 class="fw-bold">HOT 2026</h1>
                    </div>
                    <img src="{{ asset('images/anh_banner.png') }}">
                </div>
            </div>

        </div>
    </div>

    <!-- CATEGORY -->
    <div class="category shadow-sm d-flex justify-content-around text-center py-3">
    <a href="/san-pham" class="text-decoration-none text-dark">
        <i class="fa fa-layer-group"></i><br>
        <span>Bộ sưu tập</span>
    </a>

    <a href="/san-pham?brand[]=Nike" class="text-decoration-none text-dark">
        <i class="fa fa-shoe-prints"></i><br>
        <span>Nike</span>
    </a>

    <a href="/san-pham?brand[]=Adidas" class="text-decoration-none text-dark">
        <i class="fa fa-female"></i><br>
        <span>Adidas</span>
    </a>

    <a href="/san-pham?brand[]=Mizuno" class="text-decoration-none text-dark">
        <i class="fa fa-running"></i><br>
        <span>Mizuno</span>
    </a>
    </div>
    <!-- PRODUCT -->
    <div class="mt-5">
        <h4 class="text-center mt-5 fw-bold">
    @if(request('search'))
        Kết quả tìm: "{{ request('search') }}"
    @else
        Sản phẩm khuyến mãi
    @endif
        </h4>
    <div class="row mt-4">
        @foreach($products as $p)
        <div class="col mb-4" style="flex: 0 0 20%; max-width: 20%;">
            <div class="product-card position-relative shadow-sm overflow-hidden">
                @if($p->is_sale==1)
                    <span class="badge bg-danger position-absolute m-2">SALE</span>
                @endif

                <!-- IMAGE -->
                <div class="position-relative">
                    <img src="{{ asset('images/' .$p->image) }}"
                        class="img-fluid"
                        style="height:200px; object-fit:cover;"onerror="this.src='{{ asset('images/anh_login.png') }}'">

                    <!-- HOVER -->
                    <div class="overlay d-flex justify-content-center align-items-center">

                    <!-- Modal button -->
                    <button class="btn btn-light mx-1"
                        data-bs-toggle="modal"
                        data-bs-target="#productModal{{ $p->id }}">
                    <i class="fa fa-eye"></i>
                    </button>

                <!-- THÊM GIỎ -->
                <a href="/cart/add/{{ $p->id }}" class="btn btn-danger mx-1">
                    <i class="fa fa-shopping-cart"></i>
                </a>

            </div>
        </div>
        <!-- INFO -->
        <div class="mt-2 text-center">
            <small>{{ $p->name }}</small>
            <div class="price text-danger fw-bold">
                {{ number_format($p->price) }} đ
            </div>
        </div>

    </div>

</div>
<!-- ================= MODAL ================= -->
<div class="modal fade" id="productModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">{{ $p->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <div class="row">

                    <!-- IMAGE -->
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('images/' .$p->image) }}"
                             class="img-fluid rounded"
                             style="max-height:300px; object-fit:cover;">
                    </div>

                    <!-- INFO -->
                    <div class="col-md-6">

                        <p><b>Mã SP:</b> SP{{ $p->id }}</p>

                        <p>
                            <b>Tình trạng:</b>
                            @if($p->stock > 0)
                                <span class="text-success">Còn hàng</span>
                            @else
                                <span class="text-danger">Hết hàng</span>
                            @endif
                        </p>

                        <p class="text-danger fs-5 fw-bold">
                            {{ number_format($p->price) }} đ
                        </p>

                        <!-- COLOR -->
                        <div class="mb-2">
                            <label><b>Màu:</b></label>
                            <select class="form-select">
                                <option>Đen</option>
                                <option>Trắng</option>
                                <option>Xanh</option>
                            </select>
                        </div>

                        <!-- SIZE -->
                        <div class="mb-2">
                            <label><b>Size:</b></label>
                            <select class="form-select">
                        @if($p->size)
                                    @foreach(explode(',', $p->size) as $s)
                                        <option>{{ trim($s) }}</option>
                                    @endforeach
                                @else
                                    <option> Không có size </option>
                                @endif
                            </select>
                        </div>

                        <!-- QUANTITY -->
                        <div class="mb-3">
                            <label><b>Số lượng:</b></label>
                            <input type="number" value="1" min="1" class="form-control">
                        </div>

                        <!-- ADD CART -->
                        <a href="/cart/add/{{ $p->id }}" class="btn btn-danger w-100">
                            <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach

        </div>
    </div>

    <div class="mt-5">
    @if(!request('search') && $featured->isNotEmpty())
    <h4 class="text-center fw-bold mt-5">Sản phẩm nổi bật</h4>

    <div class="row mt-4">

        @foreach($featured as $p)
        <div class="col mb-4" style="flex: 0 0 20%; max-width: 20%;">
            <div class="product-card position-relative shadow-sm overflow-hidden">
                <div class="position-relative">
                    <img src="{{ asset('images/' .$p->image) }}" class="img-fluid"
                        style="height:200px; object-fit:cover;"onerror="this.src='{{ asset('images/anh_login.png') }}'">
            <!-- HOVER -->
            <div class="overlay d-flex justify-content-center align-items-center">

                <!-- MODAL BUTTON -->
                <button class="btn btn-light mx-1"
                        data-bs-toggle="modal"
                        data-bs-target="#productModal{{ $p->id }}">
                    <i class="fa fa-eye"></i>
                </button>

                <!-- CART -->
                <button class="btn btn-danger mx-1" data-id="{{ $p->id}}">
                    <i class="fa fa-shopping-cart"></i>
                </a>

            </div>
        </div>

        <!-- INFO -->
        <div class="mt-2 text-center">
            <small>{{ $p->name }}</small>
            <div class="price text-danger fw-bold">
                {{ number_format($p->price) }} đ
            </div>
        </div>

    </div>

</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="productModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">{{ $p->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <div class="row">

                    <!-- IMAGE -->
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('images/' .$p->image) }}"
                             class="img-fluid rounded"
                             style="max-height:300px; object-fit:cover;">
                    </div>

                    <!-- INFO -->
                    <div class="col-md-6">

                        <p><b>Mã SP:</b> SP{{ $p->id }}</p>

                        <p>
                            <b>Tình trạng:</b>
                            @if($p->stock > 0)
                                <span class="text-success">Còn hàng</span>
                            @else
                                <span class="text-danger">Hết hàng</span>
                            @endif
                        </p>

                        <p class="text-danger fs-5 fw-bold">
                            {{ number_format($p->price) }} đ
                        </p>

                        <!-- COLOR -->
                        <div class="mb-2">
                            <label><b>Màu:</b></label>
                            <select class="form-select">
                                <option>Đen</option>
                                <option>Trắng</option>
                                <option>Xanh</option>
                            </select>
                        </div>

                        <!-- SIZE -->
                        <div class="mb-2">
                            <label><b>Size:</b></label>
                            <select class="form-select" id="sizeSelect{{ $p->id}}">
                                <option>38</option>
                                <option>39</option>
                                <option>40</option>
                                <option>41</option>
                                <option>42</option>
                            </select>
                        </div>

                        <!-- QUANTITY -->
                        <div class="mb-3">
                            <label><b>Số lượng:</b></label>
                            <input type="number" id="qty{{ $p->id }}"value="1" min="1" class="form-control">
                        </div>

                        <!-- ADD CART -->
                        <button class="btn btn-danger mx-1 add-to-cart" data-id="{{ $p->id }}">
                            <i class="fa fa-shopping-cart">Thêm vào giỏ hàng</i> 
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach
    </div>
@endif
</div>
<div class="mt-5">
    <h4 class="text-center fw-bold">Chọn theo Vị trí - Phong cách</h4>

    <div class="row mt-4 text-center">

        @php
        $categories = [
            ['name' => 'Tiền Đạo', 'img' => 'anh_vitri.png'],
            ['name' => 'Tiền Vệ', 'img' => 'anh_vitri2.png'],
            ['name' => 'Hậu vệ', 'img' => 'anh_vitri3.png'],
            ['name' => 'Tốc Độ', 'img' => 'anh_vitri4.png'],
            ['name' => 'Kỹ thuật', 'img' => 'anh_vitri5.png'],
            ['name' => 'Kiểm soát', 'img' => 'anh_vitri6.png'],
        ];
        @endphp

        @foreach($categories as $c)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="{{ asset('images/' . $c['img']) }}"
                     class="img-fluid"
                     style="height:200px; object-fit:cover;">

                <div class="p-2 fw-bold">{{ $c['name'] }}</div>
            </div>
        </div>
        @endforeach

    </div>
</div>
<div class="mt-5 mb-5">
    <h4 class="text-center fw-bold">Tin tức</h4>

    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ asset('images/anh_tintuc1.png') }}" class="img-fluid">
                <div class="p-3">
                    <h6>Xu hướng giày thể thao 2026</h6>
                    <small>Giày sneaker đang trở lại mạnh mẽ...</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ asset('images/anh_tintuc2.png') }}" class="img-fluid">
                <div class="p-3">
                    <h6>Cách chọn giày phù hợp</h6>
                    <small>Chọn giày theo dáng chân cực quan trọng...</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ asset('images/anh_tintuc3.png') }}" class="img-fluid">
                <div class="p-3">
                    <h6>Bảo quản giày đúng cách</h6>
                    <small>Giữ giày luôn mới như ngày đầu...</small>
                </div>
            </div>
        </div>

    </div>
</div>


</div>
@if($products->isEmpty() && $featured->isEmpty())
    <div class="text-center mt-4">
        <h5>Không tìm thấy sản phẩm 😢</h5>
    </div>
@endif
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function () {

        const productId = this.dataset.id;

        // ❗ nếu click ngoài card → không có modal
        let size = 40;
        let quantity = 1;

        const modal = this.closest('.modal');

        if (modal) {
            size = modal.querySelector('#sizeSelect' + productId)?.value || 40;
            quantity = modal.querySelector('#qty' + productId)?.value || 1;
        }

        // 👉 animation luôn chạy
        const productCard = document.querySelector(`#product-${productId}`);
        const img = productCard?.querySelector('.product-img');

        if (img) flyToCart(img);

        // 👉 gọi API
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                size: size,
                quantity: quantity
            })
        })
        .then(res => res.json())
        .then(data => {

            if (data.success) {
                document.getElementById('cart-count').innerText = data.cart_count;
                loadMiniCart();
            } else {
                alert(data.message);
            }

        })
        .catch(err => {
            alert("Vui lòng đăng nhập");
        });

    });
});

function flyToCart(imgElement) {

    const cart = document.getElementById('cart-navbar');

    const imgRect = imgElement.getBoundingClientRect();
    const cartRect = cart.getBoundingClientRect();

    const flyingImg = imgElement.cloneNode(true);
    flyingImg.classList.add('flying-img');

    document.body.appendChild(flyingImg);

    // vị trí ban đầu
    flyingImg.style.left = imgRect.left + 'px';
    flyingImg.style.top = imgRect.top + 'px';

    // animation
    setTimeout(() => {
        flyingImg.style.left = cartRect.left + 'px';
        flyingImg.style.top = cartRect.top + 'px';
        flyingImg.style.width = '30px';
        flyingImg.style.height = '30px';
        flyingImg.style.opacity = '0.5';
    }, 10);

    setTimeout(() => {
        flyingImg.remove();
    }, 800);
}
// load khi hover
document.querySelector('.cart-wrapper').addEventListener('mouseenter', loadMiniCart);

function loadMiniCart() {
    fetch('/cart/mini')
        .then(res => res.text())
        .then(html => {
            document.getElementById('mini-cart-body').innerHTML = html;
        });
}

// update số lượng
function updateQty(key, change) {
    fetch('/cart/update-qty', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key, change })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('cart-count').innerText = data.cart_count;
        loadMiniCart();
    });
}

// xoá
function removeItem(key) {
    fetch('/cart/remove-mini', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('cart-count').innerText = data.cart_count;
        loadMiniCart();
    });
}

document.querySelector('.order-wrapper').addEventListener('mouseenter', loadMiniOrder);

function loadMiniOrder() {
    fetch('/orders-mini')
        .then(res => res.text())
        .then(html => {
            document.getElementById('mini-order-body').innerHTML = html;
        });
}
</script>
</body>
</html>