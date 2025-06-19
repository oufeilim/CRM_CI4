<?php

namespace App\Controllers;

use App\Models\User_model;
use App\Models\Company_user_model;
use Exception;

class User extends BaseController
{

    private $data = [];
    
    public function user_list() {
        return view('header').view('user_list').view('footer'); 
    }

    public function fetchUserList() {
        try {
            $user_model = new User_model();
            $userList = $user_model->where(['is_deleted' => 0])->findAll();

            if(!$userList) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'    => 'Error', 
                    'message'   => 'No data exist',
                    'errors'    => $user_model->error()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($userList);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }

    public function user_upsert($id=""){
        if($id == "") {
            $this->data['mode'] = "Add";
        } else {
            $this->data['mode'] = "Edit";
            $this->data['id'] = $id;

            $user_model = new User_model();
            $userData = $user_model->find($id);
            $this->data['userData'] = $userData;

            $cu_model = new Company_user_model();
            $cuArray = [];
            $cuData = $cu_model->where([
                'user_id' => $id,
                'is_deleted' => 0,
            ])->findAll();

            foreach($cuData as $v) {
                $cuArray[] = $v['company_id'];
            }

            $this->data['cuData'] = $cuArray;
        }

        return view('header').view('user_upsert', $this->data).view('footer');
    }

    public function user_submit() {
        $formData = $this->request->getJSON();

        try {
            $mode           = $formData->mode;
            $id             = $formData->id;

            $name           = $formData->name;
            $email          = $formData->email;
            $phonenum       = $formData->phonenum;
            $address        = $formData->address;

            $cu             = $formData->company_user;

            $user_model = new User_model();
            $cu_model = new Company_user_model();

            $cu_array       = [];

            if($mode == "Add") {

                $inserted = $user_model->insert([
                    'name'          => $name,
                    'email'         => $email,
                    'phonenum'      => $phonenum,
                    'address'       => $address,
                    'created_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $user_model->error(),
                    ]);
                }

                // after insert get id (auto increment required)
                // make array for batch insert (customer_user table)
                foreach( $cu as $v ) {
                    $cu_array[] = [
                        'user_id' => $inserted,
                        'company_id' => $v,
                        'created_date' => date('Y-m-d H:i:s')
                    ];
                }

                $inserted2 = $cu_model->insertBatch($cu_array);

                if(!$inserted2) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $user_model->error(),
                    ]);
                }
            } else {
                $existed_active_cu = [];
                $existed_inactive_cu = [];

                // need to do check on existed data from customer_user table
                $existed = $cu_model->where(['user_id' => $id])->findAll();
                if(!empty($existed)) {     
                    foreach($existed as $v) {
                        if($v['is_deleted'] == '1') {
                            $existed_inactive_cu[] = $v['company_id'];
                        } else {
                            $existed_active_cu[] = $v['company_id'];
                        }
                    }
                }

                $toDelete         = array_diff($existed_active_cu, $cu);
                $toReactivate     = array_intersect($existed_inactive_cu, $cu);
                $toInsert         = array_diff($cu, $existed_active_cu, $existed_inactive_cu);

                $builder = $cu_model->builder();

                // Update User_table
                $modified = $user_model->update($id, [
                    'name'          => $name,
                    'email'         => $email,
                    'phonenum'      => $phonenum,
                    'address'       => $address,
                    'modified_date'  => date('Y-m-d H:i:s')
                ]);

                if(!$modified) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to update data into database.',
                        'errors'    => $user_model->error(),
                    ]);
                }

                // Soft-delete existed company first
                if($toDelete) {
                    $builder->where('user_id', $id)
                            ->whereIn('company_id', $toDelete)
                            ->set(['is_deleted' => 1, 'modified_date' => date('Y-m-d H:i:s')])
                            ->update();

                    $error = $cu_model->error();

                    if($error['code'] !== 0) {
                        return $this->response->setStatusCode(500)->setJSON([
                            'status' => 'Error',
                            'message' => 'Failed to update data into database',
                            'errors' => $error,
                        ]);
                    }
                }

                // Reactivate existed company if exist in array
                if($toReactivate) {
                    $builder->where('user_id', $id)
                            ->whereIn('company_id', $toReactivate)
                            ->set(['is_deleted' => 0, 'modified_date' => date('Y-m-d H:i:s')])
                            ->update();

                    $error = $cu_model->error();

                    if($error['code'] !== 0) {
                        return $this->response->setStatusCode(500)->setJSON([
                            'status' => 'Error',
                            'message' => 'Failed to update data into database',
                            'errors' => $error,
                        ]);
                    }
                }

                // Insert new company
                if($toInsert) {
                    foreach($toInsert as $v) {
                        $rows[] = [
                            'user_id' => $id,
                            'company_id' => $v,
                            'created_date' => date('Y-m-d H:i:s'),
                        ];
                    }

                    $cu_model->insertBatch($rows);

                    $error = $cu_model->error();

                    if($error['code'] !== 0) {
                        return $this->response->setStatusCode(500)->setJSON([
                            'status' => 'Error',
                            'message' => 'Failed to update data into database',
                            'errors' => $error,
                        ]);
                    }
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

    public function user_del() {
        $targetID = $this->request->getJSON();

        try {

            $id = $targetID->id;
            $user_model = new User_model();

            $deleted = $user_model->update($id, [
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data from database.',
                    'errors'    => $user_model->error(),
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
