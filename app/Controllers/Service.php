<?php

namespace App\Controllers;

use App\Libraries\ShippingService;

use App\Models\Service_model;
use App\Models\Service_zone_model;
use App\Models\Service_rate_model;

use Exception;

class Service extends BaseController
{
    private $data = [];

    #region Service / Service Zone CRUD
    public function service_list()
    {
        return view('header').view('service_list').view('footer');
    }

    public function fetchServiceList() {

        $shipping_addr = strtolower($this->request->getGet('shipping_addr') ?? '');
        
        $service_model = new Service_model();
        
        if($shipping_addr != '') {

            $matchedZones  = [];

            // service zone list
            $service_zone_model = new Service_zone_model();
            $serviceZoneList = $service_zone_model->where([
                'is_deleted' => 0,
            ])->findAll();

            if(!$serviceZoneList) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'    => 'Error',
                    'message'   => $service_zone_model->errors(),
                ]);
            }

            // loop it for zone
            foreach( $serviceZoneList as $serviceZone ) {
                if(strpos($shipping_addr, strtolower($serviceZone['title'])) !== false) {
                    $matchedZones[] = $serviceZone['zone'];
                }
            }

            if (empty($matchedZones)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => 'Success',
                    'message' => 'No matched services found for given address.',
                    'data'    => []
                ]);
            }

            // Get services that support any of the matched zone_to (in rate)
            $service_rate_model = new Service_rate_model();
            $validRates = $service_rate_model->select('service_id')
                ->where(['is_deleted' => 0])
                ->whereIn('zone_to', array_unique($matchedZones))
                ->groupBy('service_id')
                ->findAll();

            if (empty($validRates)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => 'Success',
                    'message' => 'No rates available for given address.',
                    'data'    => []
                ]);
            }

            $validServiceIds = array_column($validRates, 'service_id');
            $service_model->whereIn('service_id', $validServiceIds);
        }

        $serviceList = $service_model->where([
            'is_deleted' => 0,
        ])->findAll();

        if(!$serviceList) {
            return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Error', 
                'message'   => 'No data exist',
                'errors'    => $service_model->error()
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON($serviceList);

    }

    public function service_upsert($id="")
    {
        if($id == '') {
            $this->data['mode'] = 'Add';
        } else {
            $this->data['mode'] = 'Edit';
            $this->data['id'] = $id;

            $service_model = new Service_model();
            $serviceData = $service_model->where([
                'service_id' => $id,
            ])->first();
            $this->data['serviceData'] = $serviceData;
        }

        return view('header').view('service_upsert', $this->data).view('footer');
    }

    public function service_submit() {

        $mode           = $this->request->getPost('mode');
        $id             = $this->request->getPost('id');
        $title          = $this->request->getPost('title');
        $description    = $this->request->getPost('description');
        $service_type   = $this->request->getPost('service_type');
        $status         = $this->request->getPost('status');
        $base_weight    = $this->request->getPost('base_weight');

        // logo
        $logo           = $this->request->getFile('logo');
        $logo_path      = '';

        $service_model = new Service_model();

        try {
        
            if($mode == 'Add') {

                if($logo && $logo->isValid() && !$logo->hasMoved()) {
                    $originalName = $logo->getClientName();
                    $extension = $logo->getClientExtension();

                    $logoName = $title . '_' . date('YmdHis') . '.' . $extension;
                    $logo->move(WRITEPATH . 'uploads/service/logo/', $logoName);
                    $logo_path = 'uploads/service/logo/' . $logoName;
                }

                $inserted = $service_model->insert([
                    'created_date'  => date('Y-m-d H:i:s'),
                    'title'         => $title,
                    'description'   => $description,
                    'service_type'  => $service_type,
                    'status'        => $status,
                    'base_weight'   => $base_weight,
                    'logo'          => $logo_path,
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Error occurred while insert data.',
                        'errors'    => $service_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done.',
                ]);

            } else {

                $oriLogo = $service_model->select(['logo'])->where(['service_id'=> $id])->first();

                if($logo) {
                    // Modify logo properties inside condition for logo name
                    if($logo->isValid() && !$logo->hasMoved()) {
                        $originalName = $logo->getClientName();
                        $extension = $logo->getClientExtension();

                        $logoName = $title . '_' . date('YmdHis') . '.' . $extension;
                        $logo->move(WRITEPATH . 'uploads/service/logo/', $logoName);
                        $logo_path = 'uploads/service/logo/' . $logoName;
                    }
                } else {
                    $logo_path = $oriLogo;
                }

                $modified = $service_model->insert([
                    'modified_date'  => date('Y-m-d H:i:s'),
                    'title'         => $title,
                    'description'   => $description,
                    'service_type'  => $service_type,
                    'status'        => $status,
                    'base_weight'   => $base_weight,
                    'logo'          => $logo_path,
                ]);

                if(!$modified) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Error occurred while insert data.',
                        'errors'    => $service_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done.',
                ]);

            }
            
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Database error.',
                'errors'    => $e->getMessage()
            ]);
            
        }

    }

    public function service_del() {

        $targetID = $this->request->getJSON();

        try {
            
            $id = $targetID->id;
            $service_model = new Service_model();

            $deleted = $service_model->update($id, [
                'is_deleted'    => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data.',
                    'errors'    => $service_model->errors(),
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

    public function service_zone_list()
    {
        return view('header').view('service_zone_list').view('footer');
    }

    public function fetchServiceZoneList() {
        $service_zone_model = new Service_zone_model();

        $service_id = $this->request->getGet('service_id');

        $query = $service_zone_model->where(['is_deleted' => 0]);

        if(!empty($service_id)) {
            $query->where('service_id', $service_id);
        }

        $serviceZoneList = $query->findAll();

        if(!$serviceZoneList) {
            return $this->response->setStatusCode(200)->setJSON([
                'status'    => 'Error', 
                'message'   => 'No data exist',
                'errors'    => $service_zone_model->error()
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON($serviceZoneList);

    }

    public function service_zone_upsert($id="")
    {
        if($id == '') {
            $this->data['mode'] = 'Add';
        } else {
            $this->data['mode'] = 'Edit';
            $this->data['id'] = $id;

            $service_zone_model = new Service_zone_model();
            $serviceZoneData = $service_zone_model->where([
                'service_Zone_id' => $id,
            ])->first();
            $this->data['service_ZoneData'] = $serviceZoneData;
        }

        return view('header').view('service_Zone_upsert', $this->data).view('footer');
    }

    public function service_zone_submit() {

        $formData = $this->request->getJSON();

        $mode       = $formData->mode;
        $id         = $formData->id;
        $service_id = $formData->service;
        $zone       = $formData->zone;
        $title      = $formData->title;

        $service_zone_model = new Service_zone_model();

        try {
        
            if($mode == 'Add') {

                $inserted = $service_zone_model->insert([
                    'created_date'  => date('Y-m-d H:i:s'),
                    'service_id'    => $service_id,
                    'zone'          => $zone,
                    'title'         => $title,
                ]);

                if(!$inserted) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Error occurred while insert data.',
                        'errors'    => $service_zone_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done.',
                ]);

            } else {

                $modified = $service_zone_model->insert([
                    'modified_date'  => date('Y-m-d H:i:s'),
                    'service_id'     => $service_id,                 
                    'zone'           => $zone,
                    'title'          => $title,
                ]);

                if(!$modified) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status'    => 'Error',
                        'message'   => 'Error occurred while insert data.',
                        'errors'    => $service_zone_model->errors()
                    ]);
                }

                return $this->response->setStatusCode(200)->setJSON([
                    'status'    => 'Success',
                    'message'   => 'Done.',
                ]);

            }
            
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'    => 'Error',
                'message'   => 'Database error.',
                'errors'    => $e->getMessage()
            ]);
            
        }

    }

    public function service_zone_del() {

        $targetID = $this->request->getJSON();

        try {
            
            $id = $targetID->id;
            $service_zone_model = new Service_zone_model();


            $deleted = $service_zone_model->update($id, [
                'is_deleted'    => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            ]);

            if(!$deleted) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'    => 'Error',
                    'message'   => 'Failed to delete data.',
                    'errors'    => $service_zone_model->errors(),
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
    #endregion

    public function insertBatchServiceRate() {
        $data = $this->request->getJSON(true);
         if (empty($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'Error',
                'message' => 'No data received'
            ]);
        }

        $rateList = $data['data'];
        $service_rate_model = new Service_rate_model();

        $errors = [];
        foreach ($rateList as $index => $row) {
            // Remap keys
            $rowData = [
                'service_id'    => $row['service_id'],
                'zone_from'     => $row['from_zone'],
                'zone_to'       => $row['to_zone'],
                'weight'        => $row['weight'],
                'price'         => $row['price'],
                'created_date'  => date('Y-m-d H:i:s')
            ];

            if (!$service_rate_model->insert($rowData)) {
                $errors[] = [
                    'row' => $index + 1,
                    'error' => $service_rate_model->errors()
                ];
            }
        }

        if (count($errors) > 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'Error',
                'message' => 'Some rows failed to insert',
                'errors' => $errors
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status' => 'Success',
            'message' => 'Done.'
        ]);
    }

    public function fetchServiceRateList() {
        $db = \Config\Database::connect();
        $service_id = $this->request->getGet(['service_id']);

        $builder = $db->table('service_rate sr');
        $builder->select("sr.*, 
            (SELECT title FROM service_zone sz1 WHERE sz1.zone = sr.zone_from AND sz1.service_id = sr.service_id LIMIT 1) AS from_title,
            (SELECT title FROM service_zone sz2 WHERE sz2.zone = sr.zone_to AND sz2.service_id = sr.service_id LIMIT 1) AS to_title"
        );
        $builder->where('sr.is_deleted', 0);
        
        if (!empty($service_id)) {
            $builder->where('sr.service_id', $service_id);
        }

        $result = $builder->orderBy('zone_from', 'asc')->orderBy('weight', 'asc')->get()->getResult();

        if (!$result) {
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => 'Error',
                'message' => 'No data exist',
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON($result);
    }

    public function updateServiceRates() {
        $data = $this->request->getJSON()->data ?? [];
        $service_rate_model = new Service_rate_model();

         foreach ($data as $item) {
            $service_rate_model->update($item->service_rate_id, [
                'weight' => $item->weight,
                'price'  => $item->price,
                'modified_date' => date('Y-m-d H:i:s')
            ]);
        };

        return $this->response->setStatusCode(200)->setJSON([
            'status' => 'Success',
            'message' => 'Done.',
        ]);

    }

    public function get_service_price() {
        $data = $this->request->getJSON(true);
        $arr = [];

        $arr['weight'] = ceil($data['weight']);
        $arr['company_addr'] = $data['company_addr'];
        $arr['shipping_addr'] = $data['shipping_addr'];
        $arr['service_id'] = $data['service_id'];

        $shipping_fee = ShippingService::checkServiceRate($arr);

        return $this->response->setStatusCode(200)->setJSON($shipping_fee);
    }
}
