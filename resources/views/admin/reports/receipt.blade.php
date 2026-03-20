<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $transaction->reference_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #333; }
        .info { width: 100%; margin-bottom: 20px; }
        .info td { vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table th { background-color: #f8f9fa; }
        .totals { float: right; width: 30%; }
        .totals td { padding: 5px; }
        .footer { clear: both; margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CRANK BIKE SHOP</h1>
        <p>123 Bike Trail, Cycle City, CC 12345<br>Phone: (555) 123-4567 | Email: hello@crank.com</p>
    </div>

    <table class="info">
        <tr>
            <td style="width: 50%;">
                <strong>Billed To:</strong><br>
                {{ $transaction->user->name }}<br>
                {{ $transaction->user->email }}
            </td>
            <td style="text-align: right;">
                <strong>Reference:</strong> {{ $transaction->reference_number }}<br>
                <strong>Date:</strong> {{ $transaction->created_at->format('M d, Y') }}<br>
                <strong>Status:</strong> {{ ucfirst($transaction->status) }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>₱{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="text-align: right;">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table style="width: 100%;">
            <tr>
                <td><strong>Total:</strong></td>
                <td style="text-align: right;"><strong>₱{{ number_format($transaction->total_amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for choosing Crank Bike Shop! Enjoy your ride.</p>
        <p>Return Policy: Items can be returned within 30 days with a valid receipt.</p>
    </div>
</body>
</html>
