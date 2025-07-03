<?php

namespace App\Controllers;

use App\Models\Category_model;
use App\Models\Product_model;
use App\Models\Cart_model;
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
                                    'product.parent_id' => 0,
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

    public function cart() {
        return view('ec/header').view('ec/cart').view('ec/footer');
    }

    public function addItemIntoCart() {
        $formData = $this->request->getJSON();

        try {
            
            $user_id            = $formData->user_id;
            $product_id         = $formData->product_id;
            $product_name       = $formData->product_name;
            $product_qty        = $formData->product_qty;
            $product_weight     = $formData->product_weight;
            $product_price      = $formData->product_price;
            $product_image_url  = $formData->product_image_url;

            $cart_model = new Cart_model();

            // check if existed
            $existed = $cart_model->where([
                'user_id' => 3,
                'product_id' => $product_id,
                'is_deleted' => 0
            ])->first();

            if(!$existed) {
                $insertCart = $cart_model->insert([
                    'created_date' => date('Y-m-d H:i:s'),
                    'user_id' => $user_id,
                    'product_id'=> $product_id,
                    'product_name'=> $product_name,
                    'product_qty'=> $product_qty,
                    'product_weight' => $product_weight,
                    'product_price'=> $product_price,
                    'product_image_url'=> $product_image_url
                ]);

                if(!$insertCart) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to add item into cart.',
                        'errors'    => $cart_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done.',
                ]);
            } else {
                $updated_qty = $existed['product_qty'] + $product_qty;

                $updatedCart = $cart_model->update([
                    'cart_id' => $existed['cart_id']
                ], [
                    'modified_date' => date('Y-m-d H:i:s'),
                    'product_name'=> $product_name,
                    'product_qty'=> $updated_qty,
                    'product_weight' => $product_weight,
                    'product_price'=> $product_price,
                    'product_image_url'=> $product_image_url
                ]);

                if(!$updatedCart) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to add item into cart.',
                        'errors'    => $cart_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done.',
                ]);
            }

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Error occurred while insert data.: ' . $e->getMessage(),
            ]);
        }
    }

    public function fetchCartList() {
        try {
            $cart_model = new Cart_model();
            $cartList = $cart_model->where([
                'user_id' => 3,
                'is_deleted' => 0,
            ])->findAll();

            return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Success',
                'message'   => 'Done.',
                'data'      => $cartList
            ]);

        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Error occurred while fetching data.',
            ]);
        }
    }

    public function updateCartItemQty() {
        $data = $this->request->getJSON(true);

        $id  = $data['id'];
        $qty = (int) ($data['qty'] ?? 0);

        if($qty < 1) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'Error',
                'message' => 'Quantity must be 1',
            ]);
        }

        $cart_model = new Cart_model();

        $updated = $cart_model->update($id, ['product_qty' => $qty]);
        
        return $this->response->setJSON(['success' => true]);
    }

    public function checkout() {
        return view('ec/header').view('ec/checkout').view('ec/footer');
    }
    
    public function checkout_success() {
        $sn = $this->request->getGet('serial_num');

        return view('ec/header').view('ec/checkout_success', ['sn' => $sn]).view('ec/footer');
    }

    public function deleteCartItem() {
        $data = $this->request->getJSON(true);
        $id = $data['id'] ?? null;
        $cart_model = new Cart_model();

        if(!$id) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'Error',
                'message' => 'Cart item ID required.',
            ]);
        }

        $deleted = $cart_model->update($id, [
            'is_deleted' => 1,
            'modified_date' => date('Y-m-d H:i:s')
        ]);

        if ($deleted) {
            return $this->response->setJSON(['status' => 'Success']);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'Error',
                'message' => 'Failed to delete cart item.',
            ]);
        }
    }

    public function deleteCartItems()
    {
        $data = $this->request->getJSON(true);
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'Error',
                'message' => 'No cart item IDs provided.'
            ]);
        }

        $cart_model = new Cart_model();

        $updated = $cart_model
            ->whereIn('cart_id', $ids)
            ->set([
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ])
            ->update();

        if ($updated) {
            return $this->response->setJSON(['status' => 'Success']);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'Error',
                'message' => 'Failed to delete cart items.'
            ]);
        }
    }

}
