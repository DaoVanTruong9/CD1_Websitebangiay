<h2>HÓA ĐƠN</h2>

<p>Khách: {{ $order->customer_name }}</p>
<p>SĐT: {{ $order->phone }}</p>
<p>Địa chỉ: {{ $order->address }}</p>

<hr>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Sản phẩm</th>
        <th>SL</th>
        <th>Giá</th>
        <th>Tổng</th>
    </tr>

    @foreach($order->items as $item)
    <tr>
        <td>{{ $item->product->name }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ number_format($item->price) }}</td>
        <td>{{ number_format($item->price * $item->quantity) }}</td>
    </tr>
    @endforeach
</table>

<h3>Tổng: {{ number_format($order->total_price) }} đ</h3>