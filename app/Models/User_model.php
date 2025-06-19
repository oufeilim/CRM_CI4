<?php

namespace APP\Models;

use CodeIgniter\Model;

class User_model extends Model {

    protected $table = "user";
    protected $primaryKey = "user_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'name',
        'email',
        'phonenum',
        'address',
        'modified_date'
    ];
}
?>