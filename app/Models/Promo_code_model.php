<?php

namespace APP\Models;

use CodeIgniter\Model;

class Promo_code_model extends Model {

    protected $table = "promo_code";
    protected $primaryKey = "promo_code_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'code',
        'type',
        'value',
        'max_cap',
    ];
}
?>