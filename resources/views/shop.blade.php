<!-- @extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">👟 Cửa hàng giày</h2>

    <div class="row">
        @foreach($products as $p)
        <div class="col-md-3">
            <div class="card mb-3 shadow">
                <img src="{{ $p->image }}" class="card-img-top">
                <div class="card-body">
                    <h5>{{ $p->name }}</h5>
                    <p>{{ number_format($p->price) }} VNĐ</p>
                    <a href="#" class="btn btn-primary">Mua</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection -->