<?php

namespace APP\Models;

use CodeIgniter\Model;

class Service_zone_model extends Model {

    protected $table = "service_zone";
    protected $primaryKey = "service_zone_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'service_id',
        'zone',
        'title',
    ];
}
?>