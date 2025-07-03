<?php

namespace APP\Models;

use CodeIgniter\Model;

class Sales_order_detail_model extends Model {

    protected $table = "sales_order_detail";
    protected $primaryKey = "sales_order_detail_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'sales_order_id',
        'product_id',
        'unit_price',
        'qty',
        'weight',
        'total_amount',
        'product_name',
        'product_image_url'
    ];
}
?>