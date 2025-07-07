<?php

namespace App\Controllers;

use App\Models\Wallet_log_model;
use Exception;

class Wallet_log extends BaseController
{
    private $data = [];

    public function wallet_log_list() {
        return view('header').view('wallet_log_list').view('footer');
    }

    public function fetchWalletLogList() {
        $wallet_log_model = new Wallet_log_model();
        $walletLogList = $wallet_log_model->where([
            'is_deleted'  => 0,
        ])->findAll();

        if(empty($walletLogList)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'    => 'Error',
                'message'   => 'No data exist'
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON($walletLogList);
    }

    public function wallet_log_upsert($id = "") {
        if($id == "") {
            $this->data['mode'] = 'Add';
        } else {
            $this->data['mode'] = 'Edit';
            $this->data['id']   = $id;

            $wallet_log_model = new Wallet_log_model();
            $walletLogData = $wallet_log_model->where([
                'is_deleted'    => 0,
                'wallet_log_id' => $id
            ])->first();
            $this->data['walletLogData'] = $walletLogData;
        }

        return view('header').view('wallet_log_upsert', $this->data).view('footer');
    }

    public function wallet_log_submit() {
        
        $mode            = $this->request->getPost('mode');
        $id              = $this->request->getPost('id');

        $user_id         = $this->request->getPost('user_id');
        $amount          = $this->request->getPost('amount');
        $balance         = $this->request->getPost('balance');
        $title           = $this->request->getPost('title');
        $description     = $this->request->getPost('desc');
        $ref_table       = $this->request->getPost('ref_table');
        $ref_id          = $this->request->getPost('ref_id');
        $remark          = $this->request->getPost('remark');

        // attachment
        $attachment      = $this->request->getFile('attachment');
        $attachment_path = '';

        $wallet_log_model = new Wallet_log_model();

        if($mode == 'Add') {

            if($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
                $originalName = $attachment->getClientName();
                $extension = $attachment->getClientExtension();

                $attachmentName = $user_id . '_' . $ref_table . '_' . $ref_id . date('YmdHis') . '.' . $extension;
                $attachment->move(WRITEPATH . 'uploads/wallet_log/', $attachmentName);
                $attachment_path = 'uploads/wallet_log/' . $attachmentName;
            }

            $inserted = $wallet_log_model->insert([
                'created_date'  => date('Y-m-d H:i:s'),
                'user_id'       => $user_id,
                'amount'        => $amount,
                'balance'       => $balance,
                'title'         => $title,
                'description'   => $description,
                'ref_table'     => $ref_table,
                'ref_id'        => $ref_id,
                'attachment'    => $attachment_path,
                'remark'        => $remark,
            ]);

            if(!$inserted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to insert data',
                    'errors'    => $wallet_log_model->errors(),
                ]);
            }

        } else {

            $oriAttachment = $wallet_log_model->select(['attachment'])->where(['wallet_log_id'=> $id])->first();

            if($attachment) {
                if($attachment->isValid() && !$attachment->hasMoved()) {
                    $originalName = $attachment->getClientName();
                    $extension = $attachment->getClientExtension();

                    $attachmentName = $user_id . '_' . $ref_table . '_' . $ref_id . date('YmdHis') . '.' . $extension;
                    $attachment->move(WRITEPATH . 'uploads/wallet_log/', $attachmentName);
                    $attachment_path = 'uploads/wallet_log/' . $attachmentName;
                }
            } else {
                $image_path = $oriAttachment;
            }

            $modified = $wallet_log_model->update($id, [
                'modified_date' => date('Y-m-d H:i:s'),
                'user_id'       => $user_id,
                'amount'        => $amount,
                'balance'       => $balance,
                'title'         => $title,
                'description'   => $description,
                'ref_table'     => $ref_table,
                'ref_id'        => $ref_id,
                'attachment'    => $attachment_path,
                'remark'        => $remark,
            ]);

            if (!$modified) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to update data',
                    'errors'    => $wallet_log_model->errors(),
                ]);
            }
        }

        return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Success',
                'message'   => 'Done.',
            ]);
    }

    public function wallet_log_del() {
        $targetID = $this->request->getJSON();

        $id = $targetID->id;
        $wallet_log_model = new Wallet_log_model();

        $deleted = $wallet_log_model->update($id, [
            'is_deleted' => 1,
            'modified_date' => date('Y-m-d H:i:s'),
        ]);

        if(!$deleted) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'    => 'Error',
                'message'   => 'Failed to delete data from database.',
                'errors'    => $wallet_log_model->error(),
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'    => 'Success',
            'message'   => 'Operation success.',
        ]);
    }
}
?>