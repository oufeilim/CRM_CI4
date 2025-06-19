<?php

namespace App\Controllers;

use App\Models\Category_model;
use App\Models\Product_model;
use Exception;

class Ec extends BaseController
{
    private $data = [];

    public function index()
    {
        return view('ec/header').view('ec/index').view('ec/footer');
    }

    public function category()
    {
        return view('ec/header').view('ec/category').view('ec/footer');
    }

    public function fetchCategoryList() {
        try {
            $category_model = new Category_model();

            $categoryList = $category_model
                                ->where(['is_deleted' => 0])
                                ->orderBy('priority','asc')
                                ->findAll();

            if(!$categoryList) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Error occurred while fetching database',
                    'errors'    => $category_model->errors()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($categoryList);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Error occurred while fetching database',
                    'errors'    => $e->getMessage()
                ]);
        }

    }
    
    public function category_product_list($id, $slug)
    {
        return view('ec/header').view('ec/category_product_list').view('ec/footer');
    }

    public function getCategoryProductList($id, $slug) {
        try {
            $product_model = new Product_model();
            $productList = $product_model
                                ->select('product.*, product.slug as product_slug, category.category_id, category.slug as category_slug')
                                ->join('category', 'category.category_id = product.category_id', 'left')
                                ->where([
                                    'category.category_id'=> $id,
                                    'category.slug'=> $slug,
                                    'product.is_display' => 1,
                                    'product.is_deleted' => 0,
                                    ])
                                ->orderBy('product.priority','asc')
                                ->findAll();

            if(!$productList) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Error occurred while fetching database',
                    'errors'    => $product_model->errors()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($productList);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Error occurred while fetching database',
                    'errors'    => $e->getMessage()
                ]);
        }
    }

    public function product_detail($id, $slug) {

        return view('ec/header').view('ec/product_detail').view('ec/footer');
    }

    public function getProductDetail($id, $slug) {
        
        try {
            $product_model = new Product_model();
            $productData = $product_model
                                ->select('product.*, category.slug as category_slug, category.title AS category_title, category.category_id as category_id')
                                ->join('category','category.category_id = product.category_id','left')
                                ->where([
                                    'product_id' => $id,
                                    'product.is_deleted' => 0
                                    ])
                                ->first();

            if(!$productData) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Error occurred while fetching database',
                    'errors'    => $product_model->errors()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($productData);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Error occurred while fetching database',
                    'errors'    => $e->getMessage()
                ]);
        }
        
    }

}
