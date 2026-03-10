<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #eee; padding: 20px; border-radius: 10px; }
        .header { background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 2px solid #007bff; }
        .order-details { margin-top: 20px; width: 100%; border-collapse: collapse; }
        .order-details th, .order-details td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .total { font-weight: bold; font-size: 1.2em; color: #007bff; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 0.8em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Dziękujemy za zamówienie!</h2>
        </div>
        
        <p>Twoje zamówienie o numerze <strong>#{{ $order->id }}</strong> zostało przyjęte do realizacji.</p>

        <table class="order-details">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Rozmiar</th>
                    <th>Ilość</th>
                    <th>Cena</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->size ?? '-' }}</td>
                    <td>{{ $item->quantity }}x</td>
                    <td>{{ number_format($item->unit_price_gross, 2) }} {{ $order->currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Suma całkowita: {{ number_format($order->total_price, 2) }} {{ $order->currency }}</p>

        <div class="footer">
            <p>Status zamówienia: <strong>{{ $order->status }}</strong></p>
            <hr>
            <p>To jest wiadomość automatyczna, prosimy na nią nie odpowiadać.<br>
            © {{ date('Y') }} {{ config('app.name') }}. Wszystkie prawa zastrzeżone.</p>
        </div>
    </div>
</body>
</html>