<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container text-center mt-5">

    <h3>Quét mã QR để thanh toán</h3>

    <p>Mã đơn: <b>#{{ $order->id }}</b></p>
    <p>Số tiền: <b class="text-danger">{{ number_format($order->total_price) }} đ</b></p>

    <img src="{{ $qrUrl }}" class="img-fluid my-4" style="max-width:300px">

    <p><b>Nội dung chuyển khoản:</b> DH{{ $order->id }}</p>

    <p class="text-danger">
        Sau khi chuyển khoản, vui lòng chờ xác nhận
    </p>

   <form action="/orders/mark-paid/{{ $order->id }}" method="POST">
    @csrf
        <button class="btn btn-primary mt-3">
            Tôi đã thanh toán
        </button>
    </form>
</div>

</body>
</html>