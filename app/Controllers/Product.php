<?php

namespace App\Controllers;

use App\Models\Category_model;
use App\Models\Product_model;
use Exception;

class Product extends BaseController
{

    private $data = [];
    
    public function product_list() {
        return view('header').view('product_list').view('footer'); 
    }

    public function fetchProductList() {
        try {
            $parentId = $this->request->getGet('parent_id');

            $product_model = new Product_model();
            $category_model = new Category_model();

            if($parentId == null) {
                $productList = $product_model->getParentProductList();
            } else if ($parentId == 0) {
                $productList = $product_model->getAllProductList();
            } else {
                $productList = $product_model->getProductChildList($parentId);
            }


            if(!$productList) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Error', 
                    'message'   => 'No data exist',
                    'errors'    => $product_model->error()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($productList);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }

    public function product_upsert($id=""){
        if($id == "") {
            $this->data['mode'] = "Add";
        } else {
            $this->data['mode'] = "Edit";
            $this->data['id'] = $id;

            $product_model = new Product_model();
            $productData = $product_model->getOne($id);
            $this->data['productData'] = $productData;
        }

        return view('header').view('product_upsert', $this->data).view('footer');
    }

    function slugify(string $title): string {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }

    public function product_submit() {
        
            $mode           = $this->request->getPost('mode');
            $id             = $this->request->getPost('id');

            $name           = $this->request->getPost('prod_name');
            $slug           = $this->slugify($name);
            $category_id    = $this->request->getPost('category_id');
            $price          = $this->request->getPost('price');
            $stock_qty      = $this->request->getPost('stock_qty');
            $is_display     = $this->request->getPost('is_display');
            $priority       = $this->request->getPost('priority');
            $description    = $this->request->getPost('description');

            // image
            $image          = $this->request->getFile('image');
            $image_path     = '';

            $product_model = new Product_model();

            // check duplicate slug
            $existing = $product_model->select(['slug'])->where(['slug' => $slug])->first();

            if($mode == "Add") {

                if($existing) {
                    $count = 2;
                    $baseSlug = $slug;

                    while($product_model->where(['slug'=> $slug])->first()) {
                        $slug = $baseSlug . '-' . $count++;
                    }
                }

                // Modify image properties inside condition for image name
                if($image && $image->isValid() && !$image->hasMoved()) {
                    $originalName = $image->getClientName();
                    $extension = $image->getClientExtension();

                    $imageName = $slug . '_' . date('YmdHis') . '.' . $extension;
                    dd(WRITEPATH);
                    $image->move(WRITEPATH . 'uploads/product/', $imageName);
                    $image_path = 'uploads/product/' . $imageName;
                }

                $inserted = $product_model->insert([
                    'name'          => $name,
                    'slug'          => $slug,
                    'category_id'   => $category_id,
                    'description'   => $description,
                    'price'         => $price,
                    'stock_qty'     => $stock_qty,
                    'image_url'     => $image_path,
                    'is_display'    => $is_display,
                    'priority'      => $priority,
                    'created_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $product_model->error(),
                    ]);
                }

            } else {

                $nochanged = $product_model->select(['slug'])->where(['product_id' => $id])->first();

                // Only do when the value existed and the ori value isn't the same as modified value
                if($existing && ($existing['slug'] != $nochanged['slug'])) {
                    $count = 1;
                    $baseSlug = $slug;

                    while($product_model->where(['slug'=> $slug])->first()) {
                        $slug = $baseSlug . '-' . $count++;
                    }
                }

                $oriImage = $product_model->select(['image_url'])->where(['product_id'=> $id])->first();

                if($image) {
                    // Modify image properties inside condition for image name
                    if($image->isValid() && !$image->hasMoved()) {
                        $originalName = $image->getClientName();
                        $extension = $image->getClientExtension();

                        $imageName = $slug . '_' . date('YmdHis') . '.' . $extension;
                        $image->move(WRITEPATH . 'uploads/product/', $imageName);
                        $image_path = 'uploads/product/' . $imageName;
                    }
                } else {
                    $image_path = $oriImage;
                }

                // Update User_table
                $modified = $product_model->update($id, [
                    'name'          => $name,
                    'slug'          => $slug,
                    'category_id'   => $category_id,
                    'description'   => $description,
                    'price'         => $price,
                    'stock_qty'     => $stock_qty,
                    'image_url'     => $image_path,
                    'is_display'    => $is_display,
                    'priority'      => $priority,
                    'modified_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$modified) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to update data into database.',
                        'errors'    => $product_model->error(),
                    ]);
                }
            }

            return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Success',
                'message'   => 'Operation success.',
            ]);

        
    }

    public function product_del() {
        $targetID = $this->request->getJSON();

        try {

            $id = $targetID->id;
            $product_model = new Product_model();

            $deleted = $product_model->update($id, [
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data from database.',
                    'errors'    => $product_model->errors(),
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Success',
                'message'   => 'Operation success.',
            ]);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }

    public function product_submit_variation() {
        $formData = $this->request->getJSON(true);

        try {

            $product_id = $formData['product_id'];
            $variations = $formData['variations'];

            $product_model = new Product_model();
            $parent = $product_model->find($product_id);
            $category_id = $parent['category_id'];
            $description = $parent['description'];
            $priority = $parent['priority'];

            foreach($variations as $v) {
                $name = $v['name'];
                $slug = $this->slugify($name);

                $inserted = $product_model->insert([
                    'created_date'  => date('Y-m-d H:i:s'),
                    'name'          => $name,
                    'slug'          => $slug,
                    'category_id'   => $category_id,
                    'parent_id'     => $product_id,
                    'description'   => $description,
                    'price'         => $v['price'],
                    'stock_qty'     => $v['qty'],
                    'image_url'     => '',
                    'is_display'    => 1,
                    'priority'      => $priority
                ]);

                if (!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $product_model->error(),
                    ]);
                }
            }

            return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Success',
                'message'   => 'Operation success.',
            ]);

            
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }
}
