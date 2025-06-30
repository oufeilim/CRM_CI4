<?php

namespace APP\Models;

use CodeIgniter\Model;

class Attribute_model extends Model {

    protected $table = "attribute";
    protected $primaryKey = "attribute_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'modified_date',
        'parent_id',
        'title',
        'description',
        'priority',
    ];
}
?>