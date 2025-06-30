<?php

namespace App\Controllers;

use App\Models\Category_model;
use App\Models\Product_model;
use App\Models\Attribute_model;
use Exception;
use PhpParser\Node\Expr;

class Attribute extends BaseController
{

    private $data = [];
    
    public function attribute_list() {
        return view('header').view('attribute_list').view('footer'); 
    }

    public function fetchParentAttributeList() {
        try {
            $attribute_model = new Attribute_model();
            $attributeList = $attribute_model->where([
                'parent_id' => 0,
                'is_deleted' => 0,
            ])->findAll();

            if(!$attributeList) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Error', 
                    'message'   => 'No data exist',
                    'errors'    => $attribute_model->error()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($attributeList);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }

    public function fetchAttributeList() {
        try {
            $attribute_model = new Attribute_model();
            $attributeList = $attribute_model->where([
                'is_deleted' => 0,
            ])->findAll();

            if(!$attributeList) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'    => 'Error', 
                    'message'   => 'No data exist',
                    'errors'    => $attribute_model->error()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($attributeList);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }

    public function attribute_upsert($id=""){
        if($id == "") {
            $this->data['mode'] = "Add";
        } else {
            $this->data['mode'] = "Edit";
            $this->data['id'] = $id;

            $attribute_model = new Attribute_model();
            $attributeData = $attribute_model->find($id);
            $this->data['attributeData'] = $attributeData;
        }

        return view('header').view('attribute_upsert', $this->data).view('footer');
    }

    public function attribute_submit() {

        $formData       = $this->request->getJSON(true);
        
        $mode           = $formData['mode'];
        $id             = $formData['id'];
        $title          = $formData['title'];
        $parent_id      = $formData['parent_id'];
        $priority       = $formData['priority'];
        $description    = $formData['description'];

        $attribute_model = new Attribute_model();

        try {
            
            if($mode == 'Add') {
                $inserted = $attribute_model->insert([
                    'created_date'  => date('Y-m-d H:i:s'),
                    'parent_id'     => $parent_id,
                    'title'         => $title,
                    'description'   => $description,
                    'priority'      => $priority,
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data',
                        'errors'    => $attribute_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done',
                ]);
            } else {
                $updated  = $attribute_model->update($id, [
                    'modified_date' => date('Y-m-d H:i:s'),
                    'parent_id'     => $parent_id,
                    'title'         => $title,
                    'description'   => $description,
                    'priority'      => $priority,
                ]);

                if(!$updated) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to update data',
                        'errors'    => $attribute_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done',
                ]);
            }


            
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }

    }

    public function attribute_del() {
        $targetID = $this->request->getJSON();

        try {

            $id = $targetID->id;
            $attribute_model = new Attribute_model();

            $deleted = $attribute_model->update($id, [
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data from database.',
                    'errors'    => $attribute_model->error(),
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
