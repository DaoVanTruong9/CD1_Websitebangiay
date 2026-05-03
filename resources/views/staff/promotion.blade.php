<h3>Khuyến mãi</h3>

    <form method="POST" action="/staff/apply-coupon">
    @csrf

    <select name="coupon_id">
        @foreach($coupons as $c)
            <option value="{{ $c->id }}">
                {{ $c->code }} - {{ $c->discount }}%
            </option>
        @endforeach
    </select>

    <button>Áp dụng</button>
</form>

@endforeach