<?php

namespace APP\Models;

use CodeIgniter\Model;

class Service_model extends Model {

    protected $table = "service";
    protected $primaryKey = "service_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'title',
        'description',
        'logo',
        'service_type',
        'status',
        'base_weight'
    ];
}
?>