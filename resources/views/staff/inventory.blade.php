<h3>Tồn kho</h3>

@foreach($products as $p)
<div>
    {{ $p->name }} - SL: {{ $p->quantity }}
</div>
@endforeach