<h3>Trả hàng</h3>

@foreach($orders as $o)
<div>
    Đơn #{{ $o->id }}

    <form method="POST" action="/staff/returns/process/{{ $o->id }}">
        @csrf
        <button>Xử lý trả</button>
    </form>
</div>
@endforeach