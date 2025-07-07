<?php

namespace APP\Models;

use CodeIgniter\Model;

class Wallet_log_model extends Model {

    protected $table = "wallet_log";
    protected $primaryKey = "wallet_log_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'user_id',
        'amount',
        'balance',
        'title',
        'description',
        'ref_table',
        'ref_id',
        'attachment',
        'remark',
    ];
}
?>