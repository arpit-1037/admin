<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
    
<body>
    <h2>Order Placed Successfully</h2>

    <p>Thank you for your order.</p>
    

    <p>
        <strong>Order ID:</strong> {{ $order->id }}<br>
        <strong>Total:</strong> ₹{{ number_format($order->total, 2) }}<br>
        <strong>Payment:</strong> Cash on Delivery
    </p>

    <p>We will notify you when your order is shipped.</p>

</body>

</html>