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

        .content {
            margin-left: 250px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }
    </style>
</head>

<body>

<div class="header">QUẢN LÝ SẢN PHẨM</div>

<div class="sidebar">
    <a href="/dashboard">🏠 Dashboard</a>
    <a href="/products">👟 Sản phẩm</a>
    <a href="/orders">📦 Đơn hàng</a>

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
                    <td>
                        @if($p->is_sale == 1)
                            <span class="badge bg-danger">Khuyến mãi</span>
                        @elseif($p->is_featured == 1)
                            <span class="badge bg-warning text-dark">Nổi bật</span>
                        @else
                            <span class="badge bg-secondary">Không hiển thị</span>
                        @endif</td>
                        
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>

                    <td>
                        <button class="btn btn-warning btn-sm"
                            onclick="editProduct(
                                {{ $p->id }},
                                '{{ $p->name }}',
                                '{{ $p->brand }}',
                                {{ $p->price }},
                                '{{ $p->image }}',
                                {{ $p->is_sale }},
                                {{ $p->is_featured }})">Sửa</button>

                        <button class="btn btn-danger btn-sm"
                                onclick="deleteProduct({{ $p->id }})">Xóa
                        </button>
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

    <input name="price" type="number" class="form-control mb-2" placeholder="Giá" required>

    <!-- nhập tên ảnh -->
    <input name="image" class="form-control mb-2" placeholder="VD: nike1.jpg">
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
function editProduct(id, name, brand, price, image, is_sale, is_featured){

    document.getElementById('editName').value = name;
    document.getElementById('editBrand').value = brand;
    document.getElementById('editPrice').value = price;
    document.getElementById('editImage').value = image;

    document.getElementById('editImagePreview').src = '/images/' + image;

    // set dropdown
    let type = "none";
    if(is_sale == 1) type = "sale";
    if(is_featured == 1) type = "featured";

    document.getElementById('editDisplay').value = type;

    document.getElementById('editForm').action = '/products/update/' + id;

    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>

</body>
</html>