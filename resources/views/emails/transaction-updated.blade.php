<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #4f46e5; color: #fff; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; text-transform: uppercase; font-size: 12px; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Crank Bike Shop</h1>
        </div>
        <div class="content">
            <p>Hello {{ $transaction->user->name }},</p>
            <p>Your order status has been updated.</p>
            
            <div style="margin: 20px 0; padding: 15px; background: #f9fafb; border-radius: 8px;">
                <p style="margin: 0;"><strong>Order Reference:</strong> {{ $transaction->reference_number }}</p>
                <p style="margin: 5px 0;"><strong>Current Status:</strong> 
                    <span class="status-badge status-{{ $transaction->status }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </p>
                <p style="margin: 0;"><strong>Total Amount:</strong> ₱{{ number_format($transaction->total_amount, 2) }}</p>
            </div>

            <p>We've attached your official PDF receipt to this email for your records.</p>
            <p>If you have any questions, feel free to contact our support team.</p>
            
            <p>Keep riding,<br><strong>The Crank Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Crank Bike Shop. All rights reserved.
        </div>
    </div>
</body>
</html>
