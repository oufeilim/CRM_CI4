<?php

namespace App\Controllers;

use App\Models\Company_model;
use Exception;

class Home extends BaseController
{
    private $data = [];

    public function dashboard()
    {
        return view('header').view('dashboard').view('footer'); 
    }

    public function fetchCompanyList() {
        try {
            $company_model = new Company_model();
            $companyList = $company_model->where(['is_deleted' => 0])->findAll();

            if(!$companyList) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'    => 'Error', 
                    'message'   => 'No data exist',
                    'errors'    => $company_model->error()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($companyList);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }

    public function company_upsert($id=""){
        if($id == "") {
            $this->data['mode'] = "Add";
        } else {
            $this->data['mode'] = "Edit";
            $this->data['id'] = $id;

            $company_model = new Company_model();
            $companyData = $company_model->find($id);
            $this->data['companyData'] = $companyData;
        }

        return view('header').view('company_upsert', $this->data).view('footer');
    }

    public function company_submit() {
        $formData = $this->request->getJSON();

        try {
            $mode           = $formData->mode;
            $id             = $formData->id;

            $name           = $formData->name;
            $description    = $formData->description;
            $address        = $formData->address;
            $email          = $formData->email;
            $phonenum       = $formData->phonenum;
            $brn            = $formData->brn;
            $status         = $formData->status;

            $company_model = new Company_model();

            if($mode == "Add") {
                $inserted = $company_model->insert([
                    'name'          => $name,
                    'description'   => $description,
                    'address'       => $address,
                    'email'         => $email,
                    'phonenum'      => $phonenum,
                    'brn'           => $brn,
                    'status'        => $status,
                    'created_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $company_model->error(),
                    ]);
                }
            } else {
                $modified = $company_model->update($id, [
                    'name'          => $name,
                    'description'   => $description,
                    'address'       => $address,
                    'email'         => $email,
                    'phonenum'      => $phonenum,
                    'brn'           => $brn,
                    'status'        => $status,
                    'modified_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$modified) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to update data into database.',
                        'errors'    => $company_model->error(),
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
    
    public function company_del() {
        $targetID = $this->request->getJSON();

        try {

            $id = $targetID->id;
            $company_model = new Company_model();

            $deleted = $company_model->update($id, [
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data from database.',
                    'errors'    => $company_model->error(),
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
