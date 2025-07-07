<?php

namespace APP\Models;

use CodeIgniter\Model;

class Topup_request_model extends Model {

    protected $table = "topup_request";
    protected $primaryKey = "topup_request_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'serial_no',
        'date',
        'user_id',
        'name',
        'email',
        'mobile',
        'amount',
        'status',
        'payment_method',
        'payment_date',
        'payment_status',
        'attachment',
        'admin_remark',
    ];
}
?>