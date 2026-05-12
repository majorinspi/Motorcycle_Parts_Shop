<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #f4f4f4;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2, h4 {
            text-align: center;
            margin: 5px 0;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            text-align: left;
            padding: 5px 0;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .btn-back {
            display: block;
            width: 100%;
            padding: 10px;
            background: #6c757d;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            border-radius: 5px;
        }
        
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <h2> Franz Cacz MOTORCYCLE PARTS SHOP</h2>
    <h4>Official Receipt</h4>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; margin: 5px 0;">Date: <?= esc($receipt['date']) ?></p>
    <p style="font-size: 14px; margin: 5px 0;">Customer: <?= esc($customer_name) ?></p>
    <p style="font-size: 14px; margin: 5px 0;">Payment Method: <?= esc($receipt['payment_method']) ?></p>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($receipt['cart'] as $item): ?>
            <tr>
                <td><?= esc($item['name']) ?></td>
                <td class="text-right"><?= $item['quantity'] ?></td>
                <td class="text-right">₱<?= number_format($item['price'], 2) ?></td>
                <td class="text-right">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <th>Total Due:</th>
            <th class="text-right">₱<?= number_format($receipt['total'], 2) ?></th>
        </tr>
        <tr>
            <td>Amount Paid:</td>
            <td class="text-right">₱<?= number_format($receipt['paid'], 2) ?></td>
        </tr>
        <tr>
            <td>Change:</td>
            <td class="text-right">₱<?= number_format($receipt['change'], 2) ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <p class="text-center" style="font-size: 14px;">Thank you for Buying!</p>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            <svg style="width:16px;height:16px;margin-right:5px;vertical-align:middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Receipt
        </button>
        <a href="<?= base_url('pos') ?>" class="btn-back">New Transaction</a>
    </div>
</div>

<script>
    // Automatically trigger print dialog when page loads
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    }
</script>

</body>
</html>
