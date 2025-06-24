<?php

namespace App\Libraries;

use Mpdf\Mpdf as pdf;

use App\Models\Sales_order_model;
use App\Models\Sales_order_detail_model;

class Mpdf {

    protected $mpdf;

    public function __construct($config = [])
    {
        // Optional: Allow custom config
        $this->mpdf = new pdf($config);
    }

    public function setHeaderFooter($header = '', $footer = '')
    {
        $this->mpdf->SetHTMLHeader($header);
        $this->mpdf->SetHTMLFooter($footer);
    }

    public function setTitle($title = "") {
        $this->mpdf->SetTitle($title);
    }

    public function writeHTML($html)
    {
        $this->mpdf->WriteHTML($html);
    }

    public function output($filename = 'document.pdf', $mode = 'view')
    {
        $destination = match ($mode) {
            'download' => \Mpdf\Output\Destination::DOWNLOAD,
            'view'     => \Mpdf\Output\Destination::INLINE,
            'file'     => \Mpdf\Output\Destination::FILE,
            default    => \Mpdf\Output\Destination::INLINE,
        };

        if($mode == 'file') {
            $path = FCPATH . 'invoice/'  . $filename;
            return $this->mpdf->Output($path, $destination);
        } else {
            return $this->mpdf->Output($filename, $destination);
        }
    }

    public function outputInvoice($id='', $mode = 'view') {
        $logoUrl = 'https://placecats.com/millie/300/300.jpeg';

        $so_model = new Sales_order_model();
        $sod_model = new Sales_order_detail_model();

        $so = $so_model->where([
            'sales_order_id' => $id
        ])->first();

        if(!empty($so)) {
            $serial_number = $so['serial_number'];

            $invoice_title= '
                <div class="invoice-title">INVOICE</div>
                <strong>Invoice #: </strong> #'.$serial_number.'<br>
                <strong>Order Date: </strong> '.date('d-M-Y', strtotime($so['order_date'])).'<br>
                <strong>Payment Date: </strong> '.date('d-M-Y', strtotime($so['payment_date'])).'
            ';

            $so_list_left = '
                <strong>Billed To:</strong><br>
                '.$so['user_name'].'<br>
                '.$so['user_address'].'<br>
                Email: '.$so['user_email'].'<br>
                Phone: '.$so['user_contact'].'
            ';

            $so_list_right = '
                <strong>Order Status:</strong><br>
                '.($so['order_status'] == 0 ? 'Pending' : ($so['order_status'] == 1 ? 'Success' : 'Cancel')).'<br>
                <strong>Payment Type:</strong><br>
                '.($so['payment_method'] == 0 ? 'Bank Transfer' : ($so['payment_method'] == 1 ? 'Payment Gateway' : 'E-wallet')).'<br>
                <strong>Payment Status:</strong><br>
                '.($so['payment_status'] == 0 ? 'Pending' : ($so['payment_status'] == 1 ? 'Paid' : 'Failed')) .'<br>
            ';

            $price = '
                <tr>
                    <td class="label" style="width: 75%;">Subtotal:</td>
                    <td class="amount">$ '.$so['total_amount'].'</td>
                </tr>
                <tr>
                    <td class="label">Discount:</td>
                    <td class="amount">-$ '.$so['discount_amount'].'</td>
                </tr>
                <tr>
                    <td class="label">Total:</td>
                    <td class="amount">$ '.$so['final_amount'].'</td>
                </tr>
            ';

            $sod = $sod_model->where([
                'sales_order_id' => $id,
                'is_deleted' => 0,
            ])->findAll();
        } else {
            $invoice_title = '<strong>Empty</strong>';
            $so_list_left = '<strong>Empty</strong>';
            $so_list_right = '<strong>Empty</strong>';
            $serial_number = 'err-'.rand(0,99999);

            $sod = [];
        }

        if(!empty($sod)) {
            $sod_table = '';

            foreach($sod as $v) {
                $sod_table .= '
                <tr>
                    <td>'.$v['product_name'].'</td>
                    <td>$ '.$v['unit_price'].'</td>
                    <td>'.$v['qty'].'</td>
                    <td>$ '.$v['total_amount'].'</td>
                </tr>
                ';
            }
        } else {
            $sod_table = '<tr><td colspan="4">empty</td></tr>';
        }

        $title = 'Invoice-'.$serial_number.'('.date('YmdHis').')';
        $this->mpdf->setTitle($title);

        $this->mpdf->writeHTML('
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
        ');

        $this->mpdf->SetHTMLFooter('
            <div class="footer">
                Thank you for your business!<br>
                This is a computer-generated invoice.
            </div>
        ');

        $this->mpdf->writeHTML('
            <div class="header">
                <div class="header-left">
                    <img src="' . $logoUrl . '" style="width: 80px;"><br>
                    <div class="company-name">Abc Company Sdn Bhd</div>
                    Lot 88, Jalan Example, 43000 Kajang, Selangor<br>
                    Tel: 012-3456789 | Email: abc@xxxxxxx.com
                </div>
                <div class="header-right">
                    '.$invoice_title.'
                </div>
                <div class="clear"></div>
            </div>
            <div class="section">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 48%; vertical-align: top;">
                            '.$so_list_left.'
                        </td>
                        <td style="width: 4%;"></td> <!-- Spacer -->
                        <td style="width: 48%; vertical-align: top; text-align: right;">
                            '.$so_list_right.'
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50%">Item</th>
                            <th style="width:15%">Unit Price</th>
                            <th style="width:10%">Qty</th>
                            <th style="width:25%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        '.$sod_table.'
                    </tbody>
                </table>
            </div>

            <div class="section">
                <table class="totals" style="width:100%;">
                    '.$price.'
                </table>
            </div>
        ');

        $filename = $title.'.pdf';
        return $this->output($filename, $mode);

    }
}
?>