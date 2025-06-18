<?php

namespace APP\Models;

use CodeIgniter\Model;

class Category_model extends Model {

    protected $table = "category";
    protected $primaryKey = "category_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'title',
        'slug',
        'description',
        'priority',
        'modified_date'
    ];
}
?>