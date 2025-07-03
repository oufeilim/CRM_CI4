<?php

namespace APP\Models;

use CodeIgniter\Model;

class Sales_order_model extends Model {

    protected $table = "sales_order";
    protected $primaryKey = "sales_order_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'serial_number',
        'order_date',
        'total_amount',
        'discount_amount',
        'final_amount',
        'total_weight',
        'service_id',
        'shipping_fee',
        'order_status',
        'user_id',
        'user_name',
        'user_email',
        'user_address',
        'user_contact',
        'payment_status',
        'payment_date',
        'payment_method',
        'admin_remark',
    ];
}
?>