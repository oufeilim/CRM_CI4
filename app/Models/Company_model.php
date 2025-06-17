<?php

namespace APP\Models;

use CodeIgniter\Model;

class Company_model extends Model {

    protected $table = "company";
    protected $primaryKey = "company_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'name',
        'description',
        'address',
        'email',
        'phonenum',
        'brn',
        'status',
        'modified_date'
    ];
}
?>