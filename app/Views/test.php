<style>
    body { font-family: sans-serif; font-size: 11pt; color: #333; }
    .header, .footer { width: 100%; }
    .header-left { float: left; width: 60%; }
    .header-right { float: right; width: 35%; text-align: right; }
    .clear { clear: both; }

    .company-name { font-size: 18pt; font-weight: bold; color: #0a4d8c; }
    .invoice-title { font-size: 14pt; font-weight: bold; }

    .section { margin-top: 20px; }
    .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table th, .table td { border: 1px solid #ccc; padding: 8px; font-size: 10pt; }
    .table th { background-color: #0a4d8c; color: #fff; }

    .totals td { border: none; }
    .totals .label { text-align: right; }
    .totals .amount { text-align: right; font-weight: bold; }

    .footer { font-size: 9pt; text-align: center; color: #777; margin-top: 40px; }
</style>

<div class="header">
    <div class="header-left">
        <img src="<?= base_url('images/logo.png') ?>" style="width: 80px;"><br>
        <div class="company-name">My Company Sdn Bhd</div>
        Lot 88, Jalan Example, 43000 Kajang, Selangor<br>
        Tel: 03-12345678 | Email: info@mycompany.com
    </div>
    <div class="header-right">
        <div class="invoice-title">INVOICE</div>
        <strong>Invoice #: </strong> #INV-00123<br>
        <strong>Date: </strong> 24 June 2025<br>
        <strong>Due: </strong> 01 July 2025
    </div>
    <div class="clear"></div>
</div>

<div class="section">
    <strong>Billed To:</strong><br>
    John Doe<br>
    123, Jalan ABC,<br>
    50000 Kuala Lumpur<br>
    Phone: 012-3456789
</div>

<div class="section">
    <table class="table">
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:45%">Item Description</th>
                <th style="width:15%">Unit Price</th>
                <th style="width:10%">Qty</th>
                <th style="width:25%">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Website Design</td>
                <td>RM 1,500.00</td>
                <td>1</td>
                <td>RM 1,500.00</td>
            </tr>
            <tr>
                <td>Hosting Package (1 year)</td>
                <td>RM 300.00</td>
                <td>1</td>
                <td>RM 300.00</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="section">
    <table class="totals" style="width:100%;">
        <tr>
            <td class="label" style="width: 75%;">Subtotal:</td>
            <td class="amount">RM 1,800.00</td>
        </tr>
        <tr>
            <td class="label">Tax (6% SST):</td>
            <td class="amount">RM 108.00</td>
        </tr>
        <tr>
            <td class="label">Total:</td>
            <td class="amount">RM 1,908.00</td>
        </tr>
    </table>
</div>

<div class="footer">
    Thank you for your business!<br>
    This is a computer-generated invoice.
</div>