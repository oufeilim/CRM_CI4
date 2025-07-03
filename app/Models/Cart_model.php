<?php

namespace APP\Models;

use CodeIgniter\Model;

class Cart_model extends Model {

    protected $table = "cart";
    protected $primaryKey = "cart_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'user_id',
        'product_id',
        'product_name',
        'product_qty',
        'product_weight',
        'product_price',
        'product_image_url',
    ];
}
?>