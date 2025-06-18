<?php

namespace App\Controllers;

use App\Models\Category_model;
use App\Models\Product_model;
use Exception;

class Category extends BaseController
{

    private $data = [];
    
    public function product_list() {
        return view('header').view('product_list').view('footer'); 
    }

    public function fetchProductList() {
        try {
            $category_model = new Product_model();
            $productList = $category_model->where(['is_deleted' => 0])->findAll();

            if(!$productList) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'    => 'Error', 
                    'message'   => 'No data exist',
                    'errors'    => $category_model->error()
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

    public function category_upsert($id=""){
        if($id == "") {
            $this->data['mode'] = "Add";
        } else {
            $this->data['mode'] = "Edit";
            $this->data['id'] = $id;

            $category_model = new Category_model();
            $categoryData = $category_model->find($id);
            $this->data['categoryData'] = $categoryData;
        }

        return view('header').view('category_upsert', $this->data).view('footer');
    }

    function slugify(string $title): string {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }

    public function category_submit() {
        $formData = $this->request->getJSON();

        try {
            $mode           = $formData->mode;
            $id             = $formData->id;

            $title          = $formData->title;
            $description    = $formData->description;
            $priority       = $formData->priority;

            $slug           = $this->slugify($title);

            $category_model = new Category_model();

            // check duplicate slug
            $existing = $category_model->select(['slug'])->where(['slug' => $slug])->first();

            if($mode == "Add") {

                if($existing) {
                    $count = 2;
                    $baseSlug = $slug;

                    while($category_model->where(['slug'=> $slug])->first()) {
                        $slug = $baseSlug . '-' . $count++;
                    }
                }

                $inserted = $category_model->insert([
                    'title'         => $title,
                    'slug'          => $slug,
                    'description'   => $description,
                    'priority'      => $priority,
                    'created_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $category_model->error(),
                    ]);
                }

            } else {

                $nochanged = $category_model->select(['slug'])->where(['category_id' => $id])->first();

                // Only do when the value existed and the ori value isn't the same as modified value
                if($existing && ($existing['slug'] != $nochanged['slug'])) {
                    $count = 1;
                    $baseSlug = $slug;

                    while($category_model->where(['slug'=> $slug])->first()) {
                        $slug = $baseSlug . '-' . $count++;
                    }
                }

                // Update User_table
                $modified = $category_model->update($id, [
                    'title'          => $title,
                    'slug'           => $slug,
                    'description'    => $description,
                    'priority'       => $priority,
                    'modified_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$modified) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to update data into database.',
                        'errors'    => $category_model->error(),
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

    public function category_del() {
        $targetID = $this->request->getJSON();

        try {

            $id = $targetID->id;
            $category_model = new Category_model();

            $deleted = $category_model->update($id, [
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data from database.',
                    'errors'    => $category_model->error(),
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
}
