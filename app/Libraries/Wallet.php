<?php
namespace App\Libraries;

use App\Models\Wallet_log_model;
use App\Models\Topup_request_model;
use Exception;

class Wallet
{
    protected $model;

    public function __construct() {
        $this->model = new Wallet_log_model();
    }

    public function checkBalance($id) {
        $walletLogData = $this->model
        ->select('sum(amount) AS total_balance')
        ->where([
            'is_deleted' => 0,
            'user_id' => $id,
        ])->first();

        $totalBalance = $walletLogData['total_balance'] ?? 0;

        return $totalBalance;
    }

    public function modifyBalance($action, $data = []) {
        if(empty($data)) return false;

        $id             = $data['id'];
        $amount         = $data['amount'];
        $title          = $data['title'] ?? null;
        $description    = $data['description'] ?? null;
        $ref_table      = $data['ref_table'] ?? null;
        $ref_id         = $data['ref_id'] ?? 0;
        $attachment     = $data['attachment'] ?? null;
        $remark         = $data['remark'] ?? null;

        // A = add, P = payment

        $currentBalance = $this->checkBalance( $id);

        switch($action) {
            case 'A': case 'a':
                $addedBalance = $currentBalance + $amount;

                $balance = $this->model->insert([
                    'created_date'  => date('Y-m-d H:i:s'),
                    'is_deleted'    => 0,
                    'user_id'       => $id,
                    'amount'        => $amount,
                    'balance'       => $addedBalance,
                    'title'         => $title,
                    'description'   => $description,
                    'ref_table'     => $ref_table,
                    'ref_id'        => $ref_id,
                    'attachment'    => $attachment,
                    'remark'        => $remark,
                ]);

                if(!$balance) {
                    throw new Exception('Failed to modify balance.');
                }
            break;
            case 'P': case 'p':
                $deductAmount = -$amount;
                $afterBalance = $currentBalance + $deductAmount;

                if($afterBalance < 0) {
                    throw new Exception('Your balance is not enough. Missing RM' . abs($afterBalance));
                }

                $balance = $this->model->insert([
                    'created_date'  => date('Y-m-d H:i:s'),
                    'is_deleted'    => 0,
                    'user_id'       => $id,
                    'amount'        => $deductAmount,
                    'balance'       => $afterBalance,
                    'title'         => $title,
                    'desciprtion'   => $description,
                    'ref_table'     => $ref_table,
                    'ref_id'        => $ref_id,
                    'attachment'    => $attachment,
                    'remark'        => $remark,
                ]);

                if(!$balance) {
                    throw new Exception('Failed to modify balance.');
                }
            break;
            default:
                throw new Exception('Unknown Error Occurred.');
        }

    }

    public function recalcBalance($id, $ref_id) {
        $targetList = $this->model->where([
                'user_id' => $id,
                'ref_id' => $ref_id,
                'is_deleted' => 0,
            ])->findAll();

        if(!empty($targetList)) {
            foreach($targetList as $k => $v) {
                $balance = $this->model->select('sum(amount)')
                                        ->where([
                                            'is_deleted' => 0,
                                            'ref_id' => $v['ref_id'],
                                            'user_id' => $id,
                                        ])->first();
                $this->model->update([
                    'ref_id' => $v['ref_id'],
                ], [
                    'modified_date' => date('Y-m-d H:i:s'),
                    'balance'       => $balance,
                ]);
            }
        }

    }

    public function deductBalance($id, $ref_id) {
        
        $userWalletLogData = $this->model->where([
            'is_deleted'    => 0,
            'user_id'       => $id,
            'ref_id'        => $ref_id,
        ])->first();

        if(!$userWalletLogData) {
            throw new Exception('Error occurred.');
        }

        $this->model->update([
            'wallet_log_id' => $userWalletLogData['wallet_log_id'],
            'ref_id'        => $ref_id,
            'user_id'       => $id,
        ], [
            'modified_date' => date('Y-m-d H:i:s'),
            'is_deleted'    => 1,
        ]);
    }
}
?>