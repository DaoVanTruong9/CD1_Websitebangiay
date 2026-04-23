<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>

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

        .sidebar a, .sidebar button {
            color: white;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
        }

        .sidebar a:hover, .sidebar button:hover {
            background: #444;
        }
        .sidebar a.active {
            background: #0b6fc7;
            font-weight: bold;
            border-left: 4px solid #0b6fc7;
        }

        .submenu {
            display: none;
            padding-left: 20px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }
        
        .alert {
            border-radius: 10px;
            animation: all 0.5s ease;
        }

        @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
}
    </style>
</head>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <strong>{{ session('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<body>
<div class="header">QUẢN LÝ SẢN PHẨM</div>

<div class="sidebar">
    <a href="/admin/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
    🏠 Dashboard
</a>

<a href="/admin/products" class="{{ request()->is('products*') ? 'active' : '' }}">
    👟 Quản lý sản phẩm
</a>

<a href="/admin/inventory" class="{{ request()->is('orders*') ? 'active' : '' }}">
    📦 Quản lý nhập hàng
</a>

<a href="#" class="{{ request()->is('customers*') ? 'active' : '' }}">
    👤 Quản lý khuyến mãi
</a>

<a href="#" class="{{ request()->is('users*') ? 'active' : '' }}">
    🔐 Quản lý tài khoản
</a>

    <a href="#" onclick="toggleMenu()">📊 Báo cáo</a>
    <div class="submenu" id="submenu">
        <a href="#">- Doanh thu</a>
        <a href="#">- Sản phẩm bán chạy</a>
    </div>
    <form action="/logout" method="POST">
        @csrf
        <button>🚪 Đăng xuất</button>
    </form>
</div>

<div class="content">

    <div class="d-flex justify-content-between mb-3">
        <h3>Danh sách sản phẩm</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
            + Thêm sản phẩm
        </button>
    </div>

    <div class="card p-3">
        <table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Hình</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Size</th>
            <th>Thuộc mục</th>
            <th>Ngày</th>
            <th>Hành động</th>
        </tr>
    </thead>

    <tbody id="productTable">
    @foreach($products as $p)
        <tr id="row-{{ $p->id }}">
            <td>{{ $p->id }}</td>

            <td>
                <img src="{{ asset('images/' . $p->image) }}"
                     onerror="this.src='{{ asset('images/anh_login.png') }}'"
                     width="60">
            </td>

            <td>{{ $p->name }}</td>

            <td class="text-danger">
                {{ number_format($p->price) }} đ
            </td>

            <!-- 👟 SIZE -->
            <td>
                @if($p->size)
                    @foreach(explode(',', $p->size) as $size)
                        <span class="badge rounded-pill bg-primary px-3">
                            {{ trim($size) }}
                        </span>
                    @endforeach
                @else
                    <span class="text-muted">Không có</span>
                @endif
            </td>

            <!-- 🏷️ LOẠI HIỂN THỊ -->
            <td>
                @if($p->is_sale == 1)
                    <span class="badge bg-danger">Khuyến mãi</span>
                @elseif($p->is_featured == 1)
                    <span class="badge bg-warning text-dark">Nổi bật</span>
                @else
                    <span class="badge bg-secondary">Không hiển thị</span>
                @endif
            </td>

            <td>
                {{ $p->created_at ? $p->created_at->format('d/m/Y') : '' }}
            </td>

            <td>
                <!-- ✏️ EDIT -->
                <button class="btn btn-warning btn-sm"
                    onclick="editProduct(
                        {{ $p->id }},
                        '{{ $p->name }}',
                        '{{ $p->brand }}',
                        {{ $p->price }},
                        '{{ $p->image }}',
                        '{{ $p->size }}', // 👈 thêm
                        {{ $p->is_sale }},
                        {{ $p->is_featured }}
                    )">
                    Sửa
                </button>

                <!-- ❌ DELETE -->
                <form action="/products/delete/{{ $p->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Bạn có chắc muốn xóa không?')">
                        Xóa
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
    </div>

</div>

<!-- MODAL ADD -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <form action="/products/store" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf

            <div class="modal-body">

    <input name="name" class="form-control mb-2" placeholder="Tên sản phẩm" required>

    <input name="brand" class="form-control mb-2" placeholder="Thương hiệu">
    <div class="mb-2">
        <label>Chọn size</label><br>

        @for($i = 38; $i <= 43; $i++)
            <label class="me-2">
                <input type="checkbox" name="size[]" value="{{ $i }}"> {{ $i }}
            </label>
        @endfor
    </div>
    <input name="price" type="number" class="form-control mb-2" placeholder="Giá" required>

    <!-- nhập tên ảnh -->
    <input name="image" class="form-control mb-2" placeholder="VD: nike1.png">
    <div class="mb-2">
    <label>Loại hiển thị</label>
    <select name="display_type" class="form-control">
        <option value="none">Không hiển thị</option>
        <option value="sale">Khuyến mãi</option>
        <option value="featured">Nổi bật</option>
    </select>
</div>
</div>

            <div class="modal-footer">
                <button class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <form id="editForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf

            <div class="modal-body">

    <input id="editName" name="name" class="form-control mb-2">

    <input id="editBrand" name="brand" class="form-control mb-2">
    <div class="mb-2">
        <label>Chọn size</label><br>

        @for($i = 38; $i <= 43; $i++)
            <label class="me-2">
                <input type="checkbox" class="edit-size" name="size[]" value="{{ $i }}"> {{ $i }}
            </label>
        @endfor
    </div>
    <input id="editPrice" name="price" class="form-control mb-2">

    <!-- nhập lại tên ảnh -->
    <input id="editImage" name="image" class="form-control mb-2" placeholder="nike1.jpg">

    <!-- preview -->
    <img id="editImagePreview" style="width:100px">
    <div class="mb-2">
    <label>Loại hiển thị</label>
    <select id="editDisplay" name="display_type" class="form-control">
        <option value="none">Không hiển thị</option>
        <option value="sale">Khuyến mãi</option>
        <option value="featured">Nổi bật</option>
    </select>
</div>
</div>

            <div class="modal-footer">
                <button class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function editProduct(id, name, brand, price, image, size, is_sale, is_featured){

    document.getElementById('editName').value = name;
    document.getElementById('editBrand').value = brand;
    document.getElementById('editPrice').value = price;
    document.getElementById('editImage').value = image;

    document.getElementById('editImagePreview').src = '/images/' + image;

    // set display type
    let type = "none";
    if(is_sale == 1) type = "sale";
    if(is_featured == 1) type = "featured";

    document.getElementById('editDisplay').value = type;

    // 👉 RESET checkbox
    document.querySelectorAll('.edit-size').forEach(cb => cb.checked = false);

    // 👉 CHECK lại size
    if(size){
        let arr = size.split(',');

        document.querySelectorAll('.edit-size').forEach(cb => {
            if(arr.includes(cb.value)){
                cb.checked = true;
            }
        });
    }

    document.getElementById('editForm').action = '/products/update/' + id;

    new bootstrap.Modal(document.getElementById('editModal')).show();

    // 👉 xử lý size (nếu bạn có checkbox sau này)
    console.log("Size:", size);

    document.getElementById('editForm').action = '/products/update/' + id;
}
</script>
<script>
setTimeout(() => {
    let alert = document.querySelector('.alert');
    if(alert){
        // hiệu ứng mờ dần
        alert.classList.remove('show');

        // sau 0.5s thì xóa hẳn khỏi DOM
        setTimeout(() => {
            alert.remove();
        }, 500);
    }
}, 2500);
function toggleMenu(){
    let menu = document.getElementById('submenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}
</script>
</body>
</html>