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
            background: #f5f5f5;
        }

        /* NAVBAR */
        .navbar {
            background: #fff;
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

        /* PRODUCT */
        .product-card {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            transition: 0.3s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            transform: scale(1.05);
            transition: 0.3s;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .price {
            color: red;
            font-weight: bold;
        }

        .badge-sale {
            position: relative;
            top: 10px;
            left: 10px;
            background: red;
            color: #fff;
            font-size: 12px;
            padding: 3px 6px;
            border-radius: 5px;
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
                    <a class="nav-link" href="#">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Lịch sử</a>
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

            <!-- ICON + USER -->
            <div class="d-flex align-items-center">

                <i class="fa fa-shopping-cart me-3 fs-5"></i>

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

    <!-- BANNER -->
    <div id="bannerCarousel" class="carousel slide banner-slide mt-3" data-bs-ride="carousel">
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
    <div class="category shadow-sm">
        <div><i class="fa fa-layer-group"></i><br>Bộ sưu tập</div>
        <div><i class="fa fa-shoe-prints"></i><br>Nike</div>
        <div><i class="fa fa-female"></i><br>Adidas</div>
        <div><i class="fa fa-running"></i><br>Mizuno</div>
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
                <div class="product-card position-relative shadow-sm">

                    <span class="badge-sale">SALE</span>

                    <img src="{{ asset('images/' .$p->image) }}"
                         class="img-fluid"
                         onerror="this.src='{{ asset('images/anh_login.png') }}'">

                    <div class="mt-2">
                        <small>{{ $p->name }}</small>
                        <div class="price">{{ number_format($p->price) }} đ</div>
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
            <div class="product-card position-relative shadow-sm">

                <img src="{{ asset('images/' .$p->image) }}"
                     class="img-fluid"
                     onerror="this.src='{{ asset('images/anh_login.png') }}'">

                <div class="mt-2">
                    <small>{{ $p->name }}</small>
                    <div class="price">{{ number_format($p->price) }} đ</div>
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
                <img src="{{ asset('images/news1.png') }}" class="img-fluid">
                <div class="p-3">
                    <h6>Xu hướng giày thể thao 2026</h6>
                    <small>Giày sneaker đang trở lại mạnh mẽ...</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ asset('images/news2.png') }}" class="img-fluid">
                <div class="p-3">
                    <h6>Cách chọn giày phù hợp</h6>
                    <small>Chọn giày theo dáng chân cực quan trọng...</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ asset('images/news3.png') }}" class="img-fluid">
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

</body>
</html>