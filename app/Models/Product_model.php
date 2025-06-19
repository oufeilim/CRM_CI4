<?php

namespace APP\Models;

use CodeIgniter\Model;

class Product_model extends Model {

    protected $table = "product";
    protected $primaryKey = "product_id";
    protected $allowedFields = [
        'is_deleted',
        'created_date',
        'name',
        'slug',
        'category_id',
        'description',
        'price',
        'stock_qty',
        'image_url',
        'is_display',
        'priority',
        'modified_date'
    ];

    public function getOne($id) {
        return $this->find($id);
    }

    public function getAllProductList() {
        return $this->select('product.*, category.title AS category_title')
                    ->join('category','category.category_id = product.category_id','left')
                    ->where(['product.is_deleted' => 0])
                    ->findAll();
    }
}
?>