<?php

namespace App\Controllers;

use App\Models\Promo_code_model;
use Exception;

use function PHPSTORM_META\expectedArguments;

class Promo_code extends BaseController
{

    private $data = [];
    
    public function promo_code_list() {
        return view('header').view('promo_code_list').view('footer'); 
    }

    public function fetchPromoCodeList() {
        try {
            $promo_code_model = new Promo_code_model();
            $promoCodeData = $promo_code_model->where([
                'is_deleted' => 0
            ])->findAll();

            if(!$promoCodeData) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'Error',
                    'message' => 'No data found',
                    'errors' => $promo_code_model->errors()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($promoCodeData);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'Error',
                'message' => 'Error occurred while fetching database',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function promo_code_upsert($id="") {
        if($id == "") {
            $this->data['mode'] = "Add";
        } else {
            $this->data['mode'] = "Edit";
            $this->data['id'] = $id;

            $promo_code_model = new Promo_code_model();
            $promoCodeData = $promo_code_model->where([
                'promo_code_id' => $id,
                'is_deleted' => 0,
            ])->first();
            $this->data['promoCodeData'] = $promoCodeData;
        }

        return view('header').view('promo_code_upsert', $this->data).view('footer');
    }

    public function promo_code_submit() {
        $formData = $this->request->getJSON();

        $mode               = $formData->mode;
        $id                 = $formData->id;
        $promo_code_name    = $formData->promo_code_name;
        $promo_code_type    = $formData->promo_code_type;
        $promo_code_value   = $formData->promo_code_value;
        $promo_code_maxcap  = $formData->promo_code_maxcap;

        $promo_code_model = new Promo_code_model();

        try {
            if($mode == "Add") {
                $inserted = $promo_code_model->insert([
                    'created_date' => date('Y-m-d H:i:s'),
                    'code'  => $promo_code_name,
                    'type'  => $promo_code_type,
                    'value' => $promo_code_value,
                    'max_cap'   => $promo_code_maxcap,
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'Error',
                        'message' => 'Failed to insert data.',
                        'errors' => $promo_code_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status' => 'Success',
                    'message' => 'Data insert successfully'
                ]);
            } else {
                $updated = $promo_code_model->update($id, [
                    'modified_date' => date('Y-m-d H:i:s'),
                    'code'  => $promo_code_name,
                    'type'=> $promo_code_type,
                    'value'=> $promo_code_value,
                    'max_cap'=> $promo_code_maxcap
                ]);

                if(!$updated) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'Error',
                        'message' => 'Failed to update data.',
                        'errors' => $promo_code_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status' => 'Success',
                    'message' => 'Data update successfully'
                ]);
            }
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'Error',
                'message' => 'Error occurred while insert promo code.',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function promo_code_del() {
        try {
            
            $id = $this->request->getJSON(true);
            $id = $id['id'];

            $promo_code_model = new Promo_code_model();

            $deleted = $promo_code_model->update($id, [
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data from database.',
                    'errors'    => $promo_code_model->error(),
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
