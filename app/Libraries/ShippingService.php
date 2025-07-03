<?php

namespace App\Libraries;

use App\Controllers\Service;
use App\Models\Service_model;
use App\Models\Service_rate_model;
use App\Models\Service_zone_model;

class ShippingService
{
    public static function checkServiceRate($data = []) {
        // weight
        $weight = Number_format($data['weight'] ?? 0, 2, '.', '');

        // company addr
        $company_addr = strtolower($data['company_addr'] ?? '');
        $company_zone = '';

        // shipping to addr
        $shipping_addr = strtolower($data['shipping_addr'] ?? '');
        $shipping_zone = '';

        // service id
        $service_id = $data['service_id'] ?? 0;

        // service zone list
        $service_zone_model = new Service_zone_model();
        $serviceZoneList = $service_zone_model->where([
            'is_deleted' => 0,
            'service_id' => $service_id,
        ])->findAll();

        // loop it for zone
        foreach( $serviceZoneList as $serviceZone ) {
            if(strpos($company_addr, strtolower($serviceZone['title'])) !== false) {
                $company_zone = $serviceZone['zone'];
            }

            if(strpos($shipping_addr, strtolower($serviceZone['title'])) !== false) {
                $shipping_zone = $serviceZone['zone'];
            }
        }

        // service rate
        $service_rate_model = new Service_rate_model();
        $service_rate = $service_rate_model->where([
            'is_deleted' => 0,
            'service_id' => $service_id,
            'zone_from'  => $company_zone,
            'zone_to'    => $shipping_zone,
        ])
            ->orderBy('weight', 'asc')
            ->findAll();

        $finalPrice = null;

        foreach ($service_rate as $rate) {
            if ($weight <= Number_format($rate['weight'], 2, '.', '')) {
                $finalPrice = Number_format($rate['price'], 2, '.', '');
                break; // match found
            }
        }

        if ($finalPrice === null && !empty($service_rate)) {
            $finalPrice = Number_format(end($service_rate)['price'], 2, '.', '');
        }

        return Number_format($finalPrice, 2, '.', '');
    }

    public static function checkServiceRateV2($data = []) {
        // model
        $service_model = new Service_model();
        $service_rate_model = new Service_rate_model();

        // predefined
        $rateList = [];

        // weight
        $weight = $data['weight'] ?? 0;
        
        // company addr
        $company_addr = strtolower($data['company_addr'] ?? '');
        $company_zone = '';

        // shipping to addr
        $shipping_addr = strtolower($data['shipping_addr'] ?? '');
        $shipping_zone = '';

        // foreach by service
        $serviceList = $service_model->where([
            'is_deleted' => 0,
            'status'     => 1,
        ])->findAll();

        if(!$serviceList) {
            return 'Error';
        }

        foreach($serviceList as $service) {
            // get service zone
            $company_zone = self::getServiceZone($service['service_id'], $company_addr);
            $shipping_zone = self::getServiceZone($service['service_id'], $shipping_addr);

            $base_weight = $service['base_weight'] ?? '';

            // check type
            if($base_weight != '' && $weight <= $base_weight) {
                $type = 0;

                $service_rate = $service_rate_model->where([
                    'is_deleted' => 0,
                    'service_id' => $service['service_id'],
                    'zone_from'  => $company_zone,
                    'zone_to'    => $shipping_zone,
                    'type'       => $type,
                    'weight >='  => $weight,
                ])->orderBy('weight', 'asc')->limit(1)->first();

                if(!empty($service_rate)) {
                    $arr[] = [
                        'service_id'    => $service['service_id'],
                        'service_title' => $service['title'],
                        'rate_price'    => $service_rate['price']
                    ];
                } else {
                    // $arr[] = [
                    //     'service_id'    => $service['service_id'],
                    //     'service_title' => $service['title'],
                    //     'rate_price'    => 'none',
                    // ];
                    continue;
                }
            } else if ($base_weight != '' && $weight > $base_weight) {
                // split normal and multiplier
                $exceedWeight = number_format($weight,2,'.','') - number_format($base_weight, 2, '.', '0');

                $normal = $service_rate_model->where([
                    'is_deleted' => 0,
                    'service_id' => $service['service_id'],
                    'zone_from'  => $company_zone,
                    'zone_to'    => $shipping_zone,
                    'type'       => 0,
                    'weight <='  => $weight
                ])->orderBy('weight', 'desc')->limit(1)->first();

                $multiplier = $service_rate_model->where([
                    'is_deleted' => 0,
                    'service_id' => $service['service_id'],
                    'zone_from'  => $company_zone,
                    'zone_to'    => $shipping_zone,
                    'type'       => 1,
                    'weight >='  => $weight
                ])->orderBy('weight', 'asc')->limit(1)->first();

                if(empty($normal) || empty($multiplier)) {
                    // $arr[] = [
                    //     'service_id'    => $service['service_id'],
                    //     'service_title' => $service['title'],
                    //     'rate_price'    => 'none',
                    // ];
                    continue;
                } else {
                    $multiply = $exceedWeight * $multiplier['price'];
                    $totalRatePrice = $normal['price'] + $multiply;

                    $arr[] = [
                        'service_id'    => $service['service_id'],
                        'service_title' => $service['title'],
                        'rate_price'    => Number_format($totalRatePrice, 2, '.', ''),
                    ];
                }
            } else {
                // $arr[] = [
                //     'service_id'    => $service['service_id'],
                //     'service_title' => $service['title'],
                //     'rate_price'    => 'none',
                // ];
                continue;
            }
        }

        return $arr;
    }

    public static function getServiceZone($service_id, $zone_title) {
        $service_zone_model = new Service_zone_model();
        $serviceZoneList = $service_zone_model->where([
            'is_deleted' => 0,
            'service_id' => $service_id,
        ])->findAll();

        if(empty($serviceZoneList)) {
            return '';
        }

        foreach($serviceZoneList as $serviceZone) {
            if(strpos($zone_title, strtolower($serviceZone['title'])) !== false) {
                return $serviceZone['zone'];
            }
        }

        return '';
    }
}











?>