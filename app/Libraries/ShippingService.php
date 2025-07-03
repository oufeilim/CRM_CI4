<?php

namespace App\Libraries;

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
}











?>