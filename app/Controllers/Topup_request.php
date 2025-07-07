<?php

namespace App\Controllers;

use App\Models\Topup_request_model;
use Exception;

class Topup_request extends BaseController
{
    private $data = [];

    public function topup_request_list() {
        return view('header').view('topup_request_list').view('footer');
    }

    public function fetchTopupRequestList() {
        $topup_request_model = new Topup_request_model();
        $topupRequestList = $topup_request_model->where([
            'is_deleted'  => 0,
        ])->orderBy('created_date', 'desc')->findAll();

        if(empty($topupRequestList)) {
            return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Error',
                'message'   => 'No data exist'
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON($topupRequestList);
    }

    public function topup_request_upsert($id = "") {
        if($id == "") {
            $this->data['mode'] = 'Add';
        } else {
            $this->data['mode'] = 'Edit';
            $this->data['id']   = $id;

            $topup_request_model = new Topup_request_model();
            $topupRequestData = $topup_request_model->where([
                'is_deleted'    => 0,
                'topup_request_id' => $id
            ])->first();
            $this->data['topupRequestData'] = $topupRequestData;
        }

        return view('header').view('topup_request_upsert', $this->data).view('footer');
    }

    public function topup_request_submit() {
        
        $mode            = $this->request->getPost('mode');
        $id              = $this->request->getPost('id');        
        
        $user_id         = $this->request->getPost('user_id');
        $user_name       = $this->request->getPost('name');
        $user_email      = $this->request->getPost('email');
        $user_mobile     = $this->request->getPost('mobile');
        $amount          = $this->request->getPost('amount');
        $payment_method  = $this->request->getPost('payment_method');
        $serial_no       = $mode != 'consumerAdd' ? $this->request->getPost('serial_no') : '';
        $date            = $mode != 'consumerAdd' ? $this->request->getPost('date') : date('Y-m-d H:i:s');
        $status          = $mode != 'consumerAdd' ? $this->request->getPost('status') : '0';
        $payment_date    = $mode != 'consumerAdd' ? $this->request->getPost('payment_date') : date('Y-m-d H:i:s');
        $payment_status  = $mode != 'consumerAdd' ? $this->request->getPost('payment_status') : '0';
        $admin_remark    = $mode != 'consumerAdd' ? $this->request->getPost('admin_remark') : '';

        // attachment
        $attachment      = $this->request->getFile('attachment');
        $attachment_path = '';

        $topup_request_model = new Topup_request_model();

        if($mode == 'Add' || $mode == 'consumerAdd') {

            $serial_no = 'TR-' . date('Ymd-His') . '-' . uniqid() . '-' . bin2hex(random_bytes(2));

            if($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
                $originalName = $attachment->getClientName();
                $extension = $attachment->getClientExtension();

                $attachmentName = $serial_no . '_' . date('YmdHis') . '.' . $extension;
                $attachment->move(WRITEPATH . 'uploads/topup_request/', $attachmentName);
                $attachment_path = 'uploads/topup_request/' . $attachmentName;
            }

            $inserted = $topup_request_model->insert([
                'created_date'   => date('Y-m-d H:i:s'),
                'serial_no'      => $serial_no,
                'date'           => $date,
                'user_id'        => $user_id,
                'name'           => $user_name,
                'email'          => $user_email,
                'mobile'         => $user_mobile,
                'amount'         => $amount,
                'status'         => $status,
                'payment_method' => $payment_method,
                'payment_date'   => $payment_date,
                'payment_status' => $payment_status,
                'attachment'     => $attachment_path,
                'admin_remark'   => $admin_remark,
            ]);

            if(!$inserted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to insert data',
                    'errors'    => $topup_request_model->errors(),
                ]);
            }

            // check if status approved
            if($status == '1') {
                $wallet = service('wallet');

                $data = [
                    'id'            => $user_id,
                    'amount'        => $amount,
                    'title'         => 'Topup Request',
                    'description'   => 'Topup Request from User ('.$user_name.')',
                    'ref_table'     => 'topup_request',
                    'ref_id'        => $inserted,
                    'attachment'    => $attachment_path ?? null,
                    'remark'        => null,
                ];

                $wallet->modifyBalance('A', $data);
            }

        } else {

            $oriData = $topup_request_model->select(['attachment', 'status', 'user_id'])->where(['topup_request_id'=> $id])->first();

            if($oriData['status'] == '1') {
                $added = true;
            } else {
                $added = false;
            }

            if($attachment) {
                if($attachment->isValid() && !$attachment->hasMoved()) {
                    $originalName = $attachment->getClientName();
                    $extension = $attachment->getClientExtension();

                    $attachmentName = $serial_no . '_' . date('YmdHis') . '.' . $extension;
                    $attachment->move(WRITEPATH . 'uploads/wallet_log/', $attachmentName);
                    $attachment_path = 'uploads/wallet_log/' . $attachmentName;
                }
            } else {
                $attachment_path = $oriData['attachment'];
            }

            $modified = $topup_request_model->update($id, [
                'modified_date'   => date('Y-m-d H:i:s'),
                'serial_no'      => $serial_no,
                'date'           => $date,
                'user_id'        => $user_id,
                'name'           => $user_name,
                'email'          => $user_email,
                'mobile'         => $user_mobile,
                'amount'         => $amount,
                'status'         => $status,
                'payment_method' => $payment_method,
                'payment_date'   => $payment_date,
                'payment_status' => $payment_status,
                'attachment'     => $attachment_path,
                'admin_remark'   => $admin_remark,
            ]);

            if (!$modified) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to update data',
                    'errors'    => $topup_request_model->errors(),
                ]);
            }

            $data = [
                'id'            => $user_id,
                'amount'        => $amount,
                'title'         => 'Topup Request',
                'description'   => 'Topup Request from User ('.$user_name.')',
                'ref_table'     => 'topup_request',
                'ref_id'        => $id,
                'attachment'    => $attachment_path ?? null,
                'remark'        => null,
            ];

            $wallet = service('wallet');

            if ($oriData['user_id'] == $user_id) {
                switch($status) {
                    case '1':   // Approved
                        // if original data is not approved && submitted is approved
                        if( !$added ) {
                            $wallet->modifyBalance('A', $data);
                        }

                    break;
                    case '0': case '2':  // Pending : Rejected
                        // if original data is approved && submitted is pending/rejected
                        if ( $added ) {
                            $wallet->deductBalance($user_id, $id);
                        }
                    break;
                }
            } 
            
            if ($oriData['user_id'] != $user_id) {
                if( $added ) {
                    // Old user
                    $wallet->deductBalance($oriData['user_id'], $id);

                    // New user
                    $wallet->modifyBalance('A', $data);
                }

                if ( !$added && $status == '1' ) {
                    $wallet->modifyBalance('A', $data);
                }
            }

        }

        return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Success',
                'message'   => 'Done.',
            ]);
    }

    public function topup_request_del() {
        $targetID = $this->request->getJSON();

        $id = $targetID->id;
        $topup_request_model = new Topup_request_model();

        $deleted = $topup_request_model->update($id, [
            'is_deleted' => 1,
            'modified_date' => date('Y-m-d H:i:s'),
        ]);

        if(!$deleted) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'    => 'Error',
                'message'   => 'Failed to delete data from database.',
                'errors'    => $topup_request_model->error(),
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'    => 'Success',
            'message'   => 'Operation success.',
        ]);
    }
}
?>