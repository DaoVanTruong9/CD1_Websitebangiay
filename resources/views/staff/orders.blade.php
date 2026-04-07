<h3>Đơn hàng</h3>

@foreach($orders as $o)
<div>
    #{{ $o->id }} - {{ $o->status }}

    <a href="/staff/orders/status/{{ $o->id }}/confirmed">Xác nhận</a>
    <a href="/staff/orders/status/{{ $o->id }}/delivered">Giao</a>
</div>
@endforeach