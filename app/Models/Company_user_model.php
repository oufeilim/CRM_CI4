<?php

namespace APP\Models;

use CodeIgniter\Model;

class Company_user_model extends Model {

    protected $table = "company_user";
    protected $primaryKey = "cu_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'company_id',
        'user_id',
        'modified_date'
    ];
}
?>