<h3>Khuyến mãi</h3>

@foreach($products as $p)
<div>
    {{ $p->name }} - {{ $p->price }}

    <form method="POST" action="/staff/promotion/apply/{{ $p->id }}">
        @csrf
        <button>Giảm 10%</button>
    </form>
</div>
@endforeach