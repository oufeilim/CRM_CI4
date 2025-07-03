<?php

namespace APP\Models;

use CodeIgniter\Model;

class Service_rate_model extends Model {

    protected $table = "service_rate";
    protected $primaryKey = "service_rate_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'service_id',
        'zone_from',
        'zone_to',
        'weight',
        'type',
        'price',
    ];
}
?>