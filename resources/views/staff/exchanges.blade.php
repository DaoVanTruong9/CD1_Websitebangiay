<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trả hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin:0; }

        .header{
            height:60px;
            background:#179bf9;
            color:white;
            display:flex;
            align-items:center;
            padding:0 20px;
            font-weight:bold;
        }

        .sidebar{
            width:250px;
            height:100vh;
            background:#222;
            position:fixed;
        }
        
        .submenu.active {
            max-height: 200px;
        }
        .sidebar a{
            color:white;
            display:block;
            padding:12px 20px;
            text-decoration:none;
        }

        .sidebar a:hover{
            background:#444;
        }

        .submenu{
            padding-left: 20px;
            max-height:0;
            overflow:hidden;
            background:#333;
            transition:0.3s;
        }

        .submenu.active{
            max-height:200px;
        }

        .content{
            margin-left:250px;
            padding:20px;
            background:#f5f5f5;
            min-height:100vh;
        }

        .card-box{
            background:white;
            border-radius:10px;
            padding:20px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        img{
            width:70px;
            height:70px;
            object-fit:cover;
            border-radius:8px;
        }
    </style>
</head>

<body>

<div class="header">
    NHÂN VIÊN - XỬ LÝ ĐỔI HÀNG
</div>

<div class="sidebar">

    <a href="/staff/dashboard">🏠 Dashboard</a>

    <a href="javascript:void(0)" onclick="toggleMenu()">📦 Xử lý đơn hàng</a>
        
        <div class="submenu active" id="submenu">
            <a href="/staff/returns">🔄 Trả hàng</a>
            <a href="/staff/exchanges" style="background: #2196f3">🔁 Đổi hàng</a>

            <a href="/staff/orders">📦 Cập nhật đơn</a>
        </div>

    <a href="/staff/inventory">📦 Kiểm tra tồn kho</a>

    <a href="/staff/promotion">🏷️ Khuyến mãi</a>

    <form action="/logout" method="POST" class="p-2">
        @csrf
        <button class="btn btn-danger w-100">
            🚪 Đăng xuất
        </button>
    </form>

</div>

<div class="content">

    <h3 class="mb-4">Danh sách yêu cầu đổi hàng</h3>

    @forelse($returns as $r)

    <div class="card-box mb-3">

        <div class="d-flex justify-content-between">

            <div>
                <h5 class="fw-bold">
                    Đơn #{{ $r->order_id }}
                </h5>

                <small class="text-muted">
                    {{ $r->created_at }}
                </small>
            </div>

            <div>
                @if($r->status == 'pending')
                    <span class="badge bg-warning">
                        Chờ xử lý
                    </span>
                @elseif($r->status == 'approved')
                    <span class="badge bg-success">
                        Đã duyệt
                    </span>
                @else
                    <span class="badge bg-danger">
                        Đã từ chối
                    </span>
                @endif
            </div>

        </div>

        <hr>

        <div class="d-flex align-items-center gap-3">

            @if($r->product)
                <img src="{{ asset('images/' . $r->product->image) }}">
            @else
                <img src="{{ asset('images/no-image.png') }}">
            @endif

            <div>
                <div class="fw-bold">
                    {{ $r->product->name }}
                </div>

                <small>
                    Khách hàng:
                    {{ $r->user->name }}
                </small>
            </div>

        </div>

        <hr>

        <p>
            <strong>Lý do:</strong>
            {{ $r->reason }}
        </p>

        @if($r->status == 'pending')

        <div class="d-flex gap-2">

            <form method="POST"
                  action="/staff/returns/process/{{ $r->id }}">
                @csrf

                <input type="hidden"
                       name="status"
                       value="approved">

                <button class="btn btn-success">
                    ✅ Duyệt đổi hàng
                </button>
            </form>

            <form method="POST"
                  action="/staff/returns/process/{{ $r->id }}">
                @csrf

                <input type="hidden"
                       name="status"
                       value="rejected">

                <button class="btn btn-danger">
                    ❌ Từ chối
                </button>
            </form>

        </div>

        @endif

    </div>

    @empty

    <div class="alert alert-info">
        Chưa có yêu cầu đổi hàng
    </div>

    @endforelse

</div>

<script>
function toggleMenu(){
    document.getElementById('submenu')
            .classList.toggle('active');
}
</script>

</body>
</html>