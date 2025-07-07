<?php

namespace App\Controllers;

use App\Models\Sales_order_model;
use App\Models\Sales_order_detail_model;

use App\Libraries\ShippingService;
use App\Libraries\Mpdf;

use Exception;

class Sales_order extends BaseController
{
    private $data = [];

    private $wallet;

    private $balance;

    public function __construct() {
        $this->wallet = service('wallet');
        $this->balance = $this->wallet->checkBalance('3');
        $this->data['balance'] = $this->balance;
    }

    public function sales_order_list()
    {
        return view('header').view('sales_order_list').view('footer');
    }

    public function fetchSalesOrderList()
    {
        try {
            $sales_order_model = new Sales_order_model();
            $salesOrderList = $sales_order_model
                                    ->select('sales_order_id, serial_number, order_date, order_status, user_name, final_amount, payment_status')
                                    ->where(['is_deleted' => 0])
                                    ->findAll();

            if(!$salesOrderList) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'    => 'Error', 
                    'message'   => 'No data exist',
                    'errors'    => $sales_order_model->error()
                ]);
            }

            return $this->response->setStatusCode(200)->setJSON($salesOrderList);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Server error occurred',
                'errors'    => $e->getMessage(),
            ]);
        }
    }

    public function sales_order_upsert($id = "")
    {
        if($id == "") {
            $this->data['mode'] = "Add";
        } else {
            $this->data['mode'] = "Edit";
            $this->data['id'] = $id;

            $sales_order_model = new Sales_order_model();
            $sales_order_detail_model = new Sales_order_detail_model();

            $sales_order = $sales_order_model->where([
                'sales_order_id' => $id,
                'is_deleted' => 0,
            ])->first();
            
            if($sales_order) {
                $sales_order_detail = $sales_order_detail_model->where([
                    'sales_order_id' => $id,
                    'is_deleted' => 0,
                ])->findAll();

                $this->data['salesOrderData'] = $sales_order;
                $this->data['salesOrderDetailData'] = $sales_order_detail;
            }
        }

        return view('header').view('sales_order_upsert', $this->data).view('footer');
    }

    public function sales_order_submit() {
        $formData = $this->request->getJSON();

        try {
            
            // sales order
            $mode               = $formData->mode;
            $order_date         = $formData->order_date;
            $total_amount       = $formData->total_amount;
            $discount_amount    = $formData->discount_amount;
            $total_weight       = $formData->total_weight;
            $service_id         = $formData->service_id;
            $shipping_fee       = $formData->shipping_fee;
            $final_amount       = $formData->final_amount;
            $user_id            = $formData->user_id;
            $user_name          = $formData->user_name;
            $user_email         = $formData->user_email;
            $user_contact       = $formData->user_contact;
            $user_address       = $formData->user_address;
            $company_addr       = $formData->company_addr ?? '';
            
            if($mode != "consumerAdd") {
                $order_status       = $formData->order_status;
                $payment_status     = $formData->payment_status;
                $serial_number      = $formData->serial_number;
                $id                 = $formData->id;
                $admin_remark       = $formData->admin_remark;
            } else {
                $order_status       = 0;
                $payment_status     = 0;
                $serial_number      = 0;
                $id                 = 0;
                $admin_remark       = '';
            }

            $payment_date       = $formData->payment_date;
            $payment_method     = $formData->payment_method;
        
            $sales_order_detail = $formData->sales_order_detail;

            // Another checking for the shipping fee
            if($service_id != '0' && $mode == 'consumerAdd') {
                $arr = [];

                $arr['weight'] = ceil($total_weight);
                $arr['company_addr'] = $company_addr;
                $arr['shipping_addr'] = $user_address;
                $arr['service_id'] = $service_id;

                $checkFinalAmount = 0;
                $service_rate_price = ShippingService::checkServiceRate($arr);

                $checkFinalAmount = ($total_amount - $discount_amount) + $service_rate_price;

                if(number_format($checkFinalAmount, 2, '.' , '') != number_format($final_amount, 2, '.', '')) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Wrong amount: '.$checkFinalAmount.' -> '.$final_amount,
                    ]);
                }

            }

            if($mode == "Add" || $mode == "consumerAdd") {
                // Another checking for compare balance
                if($payment_method == '2') {
                    $balance = $this->data['balance'];
                    if($final_amount > $balance) {
                        return $this->response->setStatusCode(400)->setJSON([
                                'status'    => 'Error',
                                'message'   => 'Insufficient balance. Balance (RM '.$balance.') < Amount (RM '.$final_amount.')',
                            ]);
                    } else {
                        $payment_status     = 1;
                    }
                }

                // Insert sales order to get sales_order_id
                $sales_order_model = new Sales_order_model();

                // generate a serial_number
                $serial_num = 'SN-' . date('Ymd-His') . '-' . uniqid() . '-' . bin2hex(random_bytes(2));

                $sales_order_id = $sales_order_model->insert([
                    'created_date'      => date('Y-m-d H:i:s'),
                    'serial_number'     => $serial_num,
                    'order_date'        => $order_date,
                    'total_amount'      => number_format($total_amount, 2, '.' , ''),
                    'discount_amount'   => number_format($discount_amount, 2, '.' , ''),
                    'total_weight'      => number_format($total_weight, 2, '.' , ''),
                    'service_id'        => $service_id,
                    'shipping_fee'      => number_format($shipping_fee, 2, '.' , ''),
                    'final_amount'      => number_format($final_amount, 2, '.' , ''),
                    'user_id'           => $user_id,
                    'user_name'         => $user_name,
                    'user_email'        => $user_email,
                    'user_address'      => $user_address,
                    'user_contact'      => $user_contact,
                    'payment_date'      => $payment_date,
                    'payment_method'    => $payment_method,
                    'admin_remark'      => $admin_remark
                ]);

                if(!$sales_order_id) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $sales_order_model->errors()
                    ]);
                }

                // Sales order detail
                // associative arrays
                $sales_order_detail_model = new Sales_order_detail_model();
                $sales_order_detail_array = [];

                foreach($sales_order_detail as $product_item) {
                    $sales_order_detail_array[] = [
                        'created_date'      => date('Y-m-d H:i:s'),
                        'sales_order_id'    => $sales_order_id,
                        'product_id'        => $product_item->product_id,
                        'unit_price'        => number_format($product_item->product_price, 2, '.' , ''),
                        'qty'               => $product_item->product_qty,
                        'weight'            => $product_item->product_weight,
                        'total_amount'      => number_format($product_item->total, 2, '.' , ''),
                        'product_name'      => $product_item->product_name,
                        'product_image_url' => $product_item->product_image_url
                    ];
                }

                $inserted = $sales_order_detail_model->insertBatch($sales_order_detail_array);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to insert data into database.',
                        'errors'    => $sales_order_detail_model->errors()
                    ]);
                }

                // deduct balance if method = e-walet
                if ($mode == 'consumerAdd' && $payment_method == '2') {
                    $balArr = [
                        'id'            => $user_id,
                        'amount'        => $final_amount,
                        'title'         => 'Sales Order for User_id ('.$user_id.')',
                        'description'   => null,
                        'ref_table'     => 'sales_order',
                        'ref_id'        => $inserted,
                        'attachment'    => null,
                        'remark'        => null,
                    ];

                    $this->wallet->modifyBalance('P', $balArr);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Data inserted.',
                    'serial_num' => $serial_num,
                ]);
            } else {
                $sales_order_model        = new Sales_order_model();
                $sales_order_detail_model = new Sales_order_detail_model();
                $builder                  = $sales_order_detail_model->builder();

                $oriData                  = $sales_order_model->where([
                                                'sales_order_id'    => $id,
                                                'serial_number'     => $serial_number,
                                                'is_deleted'        => 0,
                                            ])->first();

                $sales_order = $sales_order_model->update($id, [
                    'modified_date'      => date('Y-m-d H:i:s'),
                    'serial_number'     => $serial_number,
                    'order_date'        => $order_date,
                    'total_amount'      => number_format($total_amount, 2, '.' , ''),
                    'discount_amount'   => number_format($discount_amount, 2, '.' , ''),
                    'total_weight'      => number_format($total_weight, 2, '.' , ''),
                    'service_id'        => $service_id,
                    'shipping_fee'      => number_format($shipping_fee, 2, '.' , ''),
                    'final_amount'      => number_format($final_amount, 2, '.' , ''),
                    'order_status'      => $order_status,
                    'user_id'           => $user_id,
                    'user_name'         => $user_name,
                    'user_email'        => $user_email,
                    'user_address'      => $user_address,
                    'user_contact'      => $user_contact,
                    'payment_status'    => $payment_status,
                    'payment_date'      => $payment_date,
                    'payment_method'    => $payment_method,
                    'admin_remark'      => $admin_remark
                ]);

                if(!$sales_order) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Failed to update data into database.',
                        'errors'    => $sales_order_model->errors()
                    ]);
                }
                
                // sales order detail update
            
                $incoming = [];
                foreach ($sales_order_detail as $item) {
                    $incoming[$item->product_id] = $item;
                }
                $incoming_ids = array_keys($incoming);

                $existing = $sales_order_detail_model
                                ->where('sales_order_id', $id)
                                ->findAll();

                // init (active = <isdeleted = 0>, inactive = <isdeleted = 1>)
                $active   = [];
                $inactive = [];

                // categorize
                foreach ($existing as $row) {
                    if ($row['is_deleted']) {
                        $inactive[$row['product_id']] = $row;
                    } else {
                        $active[$row['product_id']] = $row;
                    }
                }

                // get keys for compare
                $active_ids   = array_keys($active);
                $inactive_ids = array_keys($inactive);

                // compare with keys
                $toDelete     = array_diff($active_ids, $incoming_ids);
                $toReactivate = array_intersect($inactive_ids, $incoming_ids);
                $toInsert     = array_diff($incoming_ids, $active_ids, $inactive_ids);
                $toMaybeUpd   = array_intersect($active_ids, $incoming_ids);

                // Soft-delete
                if ($toDelete) {
                    $builder->where('sales_order_id', $id)
                            ->whereIn('product_id', $toDelete)
                            ->set(['is_deleted' => 1, 'modified_date' => date('Y-m-d H:i:s')])
                            ->update();

                    $error = $sales_order_detail_model->error();
                    if ($error['code'] !== 0) {
                        return $this->response->setStatusCode(500)->setJSON([
                            'status' => 'Error',
                            'message' => 'Failed to update sales order detail (toDelete).',
                            'errors' => $error
                        ]);
                    }
                }

                // Re-activate existed data and update it
                if ($toReactivate) {
                    foreach ($toReactivate as $pid) {
                        $item = $incoming[$pid];
                        $builder->where('sales_order_id', $id)
                                ->where('product_id', $pid)
                                ->set([
                                    'unit_price'      => number_format($item->product_price, 2, '.', ''),
                                    'qty'             => $item->product_qty,
                                    'weight'          => $item->product_weight,
                                    'total_amount'    => number_format($item->total, 2, '.', ''),
                                    'product_name'    => $item->product_name,
                                    'product_image_url'   => $item->product_image_url,
                                    'is_deleted'      => 0,
                                    'modified_date'   => date('Y-m-d H:i:s')
                                ])
                                ->update();

                        $error = $sales_order_detail_model->error();
                        if ($error['code'] !== 0) {
                            return $this->response->setStatusCode(500)->setJSON([
                                'status' => 'Error',
                                'message' => 'Failed to update sales order detail (toReactivate).',
                                'errors' => $error
                            ]);
                        }
                    }
                }

                // Actived, new value update
                foreach ($toMaybeUpd as $pid) {
                    $item = $incoming[$pid];
                    $existing_row = $active[$pid];

                    $new_price = number_format($item->product_price, 2, '.', '');
                    $old_price = number_format($existing_row['unit_price'], 2, '.', '');

                    $new_qty = (int)$item->product_qty;
                    $old_qty = (int)$existing_row['qty'];

                    if ($new_price !== $old_price || $new_qty !== $old_qty) {
                        $builder->where('sales_order_id', $id)
                                ->where('product_id', $pid)
                                ->set([
                                    'unit_price'     => $new_price,
                                    'qty'            => $new_qty,
                                    'weight'         => $item->product_weight,
                                    'total_amount'   => number_format($item->total, 2, '.', ''),
                                    'product_name'   => $item->product_name,
                                    'product_image_url'  => $item->product_image_url,
                                    'modified_date'  => date('Y-m-d H:i:s')
                                ])
                                ->update();
                        
                        $error = $sales_order_detail_model->error();
                        if ($error['code'] !== 0) {
                            return $this->response->setStatusCode(500)->setJSON([
                                'status' => 'Error',
                                'message' => 'Failed to update sales order detail (toMaybeUpd).',
                                'errors' => $error
                            ]);
                        }
                    }
                }

                // If got new items added, insert
                if ($toInsert) {
                    $rows = [];
                    foreach ($toInsert as $pid) {
                        $item = $incoming[$pid];
                        $rows[] = [
                            'sales_order_id'  => $id,
                            'product_id'      => $pid,
                            'unit_price'      => number_format($item->product_price, 2, '.', ''),
                            'qty'             => $item->product_qty,
                            'weight'          => $item->product_weight,
                            'total_amount'    => number_format($item->total, 2, '.', ''),
                            'product_name'    => $item->product_name,
                            'product_image_url'   => $item->product_image_url,
                            'created_date'    => date('Y-m-d H:i:s'),
                        ];
                    }
                    $sales_order_detail_model->insertBatch($rows);

                    $error = $sales_order_detail_model->error();
                    if ($error['code'] !== 0) {
                        return $this->response->setStatusCode(500)->setJSON([
                            'status' => 'Error',
                            'message' => 'Failed to update sales order detail (toInsert).',
                            'errors' => $error
                        ]);
                    }
                }

                // e-wallet
                if($payment_method == '2') {
                    if($oriData['payment_status'] == '1') {
                        $added = true;
                    } else {
                        $added = false;
                    }

                    $data = [
                        'id'            => $user_id,
                        'amount'        => $final_amount,
                        'title'         => 'Sales Order for User_id ('.$user_id.')',
                        'description'   => null,
                        'ref_table'     => 'sales_order',
                        'ref_id'        => $id,
                        'attachment'    => null,
                        'remark'        => null,
                    ];

                    if ($oriData['user_id'] == $user_id) {
                        switch($payment_status) {
                            case '1':   // Approved
                                // if original data is not approved && submitted is approved
                                if( !$added ) {
                                    $this->wallet->modifyBalance('P', $data);
                                }

                            break;
                            case '0': case '2':  // Pending : Rejected
                                // if original data is approved && submitted is pending/rejected
                                if ( $added ) {
                                    $this->wallet->deductBalance($user_id, $id);
                                }
                            break;
                        }
                    } 
                    
                    if ($oriData['user_id'] != $user_id) {
                        if( $added ) {
                            // Old user
                            $this->wallet->deductBalance($oriData['user_id'], $id);

                            // New user
                            $this->wallet->modifyBalance('P', $data);
                        }

                        if ( !$added && $payment_status == '1' ) {
                            $this->wallet->modifyBalance('P', $data);
                        }
                    }

                    // if pending/failed to paid
                    if ($oriData['payment_status'] != '1' && $payment_method == '1') {
                        
                        $this->wallet->modifyBalance('P', $data);
                    }
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status' => 'Success',
                    'message'=> 'Done.',
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

    public function sales_order_del() {
        $targetID = $this->request->getJSON();
        $targetID = $targetID->id;

        try {
            
            $sales_order_model = new Sales_order_model();

            $deleted = $sales_order_model->update($targetID, [
                'is_deleted' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data from database.',
                    'errors'    => $sales_order_model->error(),
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

    public function sales_order_invoice($id) {
        $pdf = new Mpdf([
            'allow_url_fopen' => true,
        ]);

        $body = $pdf->outputInvoice($id, 'view');

        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setBody($body);
    }
}
