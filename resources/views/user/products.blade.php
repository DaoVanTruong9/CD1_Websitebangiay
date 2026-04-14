<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shoes Sport - Sản phẩm</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #45c8ed; }

        .navbar { background: #0c0000; padding: 15px 0; }

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

        .badge-sale {
            position: absolute;
            top: 10px;
            left: 10px;
            background: red;
            color: #fff;
            font-size: 12px;
            padding: 3px 6px;
            border-radius: 5px;
        }

        .nav-link:hover {
            color: red !important;
            transform: translateY(-2px);
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
                        <a class="nav-link active fw-bold text-danger" href="/san-pham">Sản phẩm</a>
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

    <!-- TITLE -->
    <h4 class="text-center mt-5 fw-bold">
        @if(request('search'))
            Kết quả tìm: "{{ request('search') }}"
        @else
            Tất cả sản phẩm
        @endif
    </h4>
<div class="container-fluid mt-2"> {{-- giảm khoảng cách với header --}}
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3">

            <!-- BREADCRUMB -->
            <div class="mb-2">
                <a href="/" class="text-decoration-none">Trang chủ</a> 
                / <span class="fw-bold">Sản phẩm</span>
            </div>

            <!-- SIDEBAR CARD -->
            <div class="card shadow-sm">

                <div class="card-header fw-bold text-center bg-dark text-white">
                    Bộ lọc sản phẩm
                </div>

                <div class="p-3">
                    <form id="filterForm" method="GET" action="{{ url('/san-pham') }}">

    <!-- DANH MỤC -->
    <h6 class="fw-bold">Danh mục</h6>
    @foreach(['Nike','Adidas','Mizuno'] as $brand)
        <div class="form-check">
            <input class="form-check-input filter-input"
                   type="checkbox"
                   name="brand[]"
                   value="{{ $brand }}"
                   {{ in_array($brand, request('brand', [])) ? 'checked' : '' }}>
            <label class="form-check-label">
                {{ $brand }}
            </label>
        </div>
    @endforeach

    <hr>

    <!-- GIÁ -->
    <h6 class="fw-bold">Khoảng giá</h6>

    <input type="number" name="min_price"
           class="form-control mb-2 filter-input"
           placeholder="Từ"
           value="{{ request('min_price') }}">

    <input type="number" name="max_price"
           class="form-control mb-2 filter-input"
           placeholder="Đến"
           value="{{ request('max_price') }}">

    <hr>

    <!-- SIZE -->
    <h6 class="fw-bold">Size</h6>

    @foreach([38,39,40,41,42] as $size)
        <div class="form-check">
            <input class="form-check-input filter-input"
                   type="checkbox"
                   name="size[]"
                   value="{{ $size }}"
                   {{ in_array((string)$size, request('size', [])) ? 'checked' : '' }}>
            <label class="form-check-label">
                {{ $size }}
            </label>
        </div>
    @endforeach

</form>

                </div>
            </div>
        </div>


        <!-- PRODUCT LIST -->
        <div class="col-md-9">

            <div class="row g-3">

                @forelse($products as $p)
                    <div class="col-lg-3 col-md-4 col-6">
    <div class="card product-card h-100 shadow-sm text-center position-relative overflow-hidden">

        @if($p->is_sale)
            <span class="badge bg-danger position-absolute m-2">
                SALE
            </span>
        @endif

        <!-- IMAGE -->
        <div class="position-relative">
            <img src="{{ asset('images/' .$p->image) }}"
                 class="card-img-top"
                 style="height:200px; object-fit:cover;"onerror="this.src='{{ asset('images/anh_login.png') }}'">

            <!-- HOVER ICON -->
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

                @empty
                    <div class="text-center w-100">
                        <h5>Không có sản phẩm</h5>
                    </div>
                @endforelse

            </div>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const inputs = document.querySelectorAll('.filter-input');
    const form = document.getElementById('filterForm');

    inputs.forEach(input => {
        input.addEventListener('change', () => {
            form.submit();
        });
    });

    // auto submit khi nhập giá (delay 500ms)
    let timeout;
    document.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(input => {
        input.addEventListener('keyup', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                form.submit();
            }, 500);
        });
    });
</script>
</body>
</html>