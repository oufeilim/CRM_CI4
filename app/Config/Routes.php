<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::dashboard');

$routes->get('uploads/(:any)', 'FileController::serve/$1');
$routes->get('uploads/(:any)/(:any)/(:any)', 'FileController::serve/$1/$2/$3');

/* Start Company */
$routes->get('/company_list', 'Company::company_list');

$routes->get('/api/fetchCompanyList', 'Company::fetchCompanyList');

$routes->get('/company_upsert', 'Company::company_upsert');
$routes->get('/company_upsert/(:num)', 'Company::company_upsert/$1');
$routes->post('/company_submit', 'Company::company_submit');
$routes->post('/company_del', 'Company::company_del');
/* End Company */


/* Start User */
$routes->get('/user_list', 'User::user_list');

$routes->get('/api/fetchUserList', 'User::fetchUserList');
$routes->post('/api/fetchUserOne', 'User::fetchUserOne');

$routes->get('/user_upsert', 'User::user_upsert');
$routes->get('/user_upsert/(:num)', 'User::user_upsert/$1');
$routes->post('/user_submit', 'User::user_submit');
$routes->post('/user_del', 'User::user_del');
/* End User */


/* Start Category */
$routes->get('/category_list', 'Category::category_list');

$routes->get('/api/fetchCategoryList', 'Category::fetchCategoryList');

$routes->get('/category_upsert', 'Category::category_upsert');
$routes->get('/category_upsert/(:num)', 'Category::category_upsert/$1');
$routes->post('/category_submit', 'Category::category_submit');
$routes->post('/category_del', 'Category::category_del');
/* End Category */


/* Start Product */
$routes->get('/product_list', 'Product::product_list');

$routes->get('/api/fetchProductList', 'Product::fetchProductList');

$routes->get('/product_upsert', 'Product::product_upsert');
$routes->get('/product_upsert/(:num)', 'Product::product_upsert/$1');
$routes->post('/product_submit', 'Product::product_submit');
$routes->post('/product_submit_variation', 'Product::product_submit_variation');
$routes->post('/product_del', 'Product::product_del');
/* End Product */


/* Start Attribute */
$routes->get('/attribute_list', 'Attribute::attribute_list');

$routes->get('/api/fetchAttributeList', 'Attribute::fetchattributeList');
$routes->get('/api/fetchParentAttributeList', 'Attribute::fetchParentAttributeList');

$routes->get('/attribute_upsert', 'Attribute::attribute_upsert');
$routes->get('/attribute_upsert/(:num)', 'Attribute::attribute_upsert/$1');
$routes->post('/attribute_submit', 'Attribute::attribute_submit');
$routes->post('/attribute_del', 'Attribute::attribute_del');
/* End Attribute */


/* Start Sales Order */
$routes->get('/sales_order_list', 'Sales_order::sales_order_list');

$routes->get('/api/fetchSalesOrderList', 'Sales_order::fetchSalesOrderList');

$routes->get('/sales_order_upsert', 'Sales_order::sales_order_upsert');
$routes->get('/sales_order_upsert/(:num)', 'Sales_order::sales_order_upsert/$1');
$routes->post('/sales_order_submit', 'Sales_order::sales_order_submit');
$routes->post('/sales_order_del', 'Sales_order::sales_order_del');

// $routes->get('/sales_order/print/(:num)', 'Sales_order::print_invoice/$1');
$routes->get('/sales_order_invoice/(:num)', 'Sales_order::sales_order_invoice/$1');
/* End Sales Order */


/* Start Promo Code */
$routes->get('/promo_code_list', 'Promo_code::promo_code_list');

$routes->get('/api/fetchPromoCodeList', 'Promo_code::fetchPromoCodeList');
$routes->post('/api/fetchPromoCodeOne', 'Promo_code::fetchPromoCodeOne');

$routes->get('/promo_code_upsert', 'Promo_code::promo_code_upsert');
$routes->get('/promo_code_upsert/(:num)', 'Promo_code::promo_code_upsert/$1');
$routes->post('/promo_code_submit', 'Promo_code::promo_code_submit');
$routes->post('/promo_code_del', 'Promo_code::promo_code_del');
/* End Promo Code */


/* Start Service */
$routes->get('/service_list', 'Service::service_list');

$routes->get('/api/fetchServiceList', 'Service::fetchServiceList');

$routes->get('/service_upsert', 'Service::service_upsert');
$routes->get('/service_upsert/(:num)', 'Service::service_upsert/$1');
$routes->post('/service_submit', 'Service::service_submit');
$routes->post('/service_del', 'Service::service_del');
/* End Service */


/* Start Service Zone */
$routes->get('/service_zone_list', 'Service::service_zone_list');

$routes->get('/api/fetchServiceZoneList', 'Service::fetchServiceZoneList');

$routes->get('/service_zone_upsert', 'Service::service_zone_upsert');
$routes->get('/service_zone_upsert/(:num)', 'Service::service_zone_upsert/$1');
$routes->post('/service_zone_submit', 'Service::service_zone_submit');
$routes->post('/service_zone_del', 'Service::service_zone_del');
/* End Service Zone */


/* Start Service Rate */
$routes->post('/api/insertBatchServiceRate', 'Service::insertBatchServiceRate');
$routes->get('/api/fetchServiceRateList', 'Service::fetchServiceRateList');
$routes->post('/api/updateServiceRates', 'Service::updateServiceRates');
/* End Service Rate */


/* Start Wallet Log */
$routes->get('/wallet_log_list', 'Wallet_log::wallet_log_list');

$routes->get('/api/fetchWalletLogList', 'Wallet_log::fetchWalletLogList');

$routes->get('/wallet_log_upsert', 'Wallet_log::wallet_log_upsert');
$routes->get('/wallet_log_upsert/(:num)', 'Wallet_log::wallet_log_upsert/$1');
$routes->post('/wallet_log_submit', 'Wallet_log::wallet_log_submit');
$routes->post('/wallet_log_del', 'Wallet_log::wallet_log_del');
/* End Wallet Log */


/* Start Topup Request */
$routes->get('/topup_request_list', 'Topup_request::topup_request_list');

$routes->get('/api/fetchTopupRequestList', 'Topup_request::fetchTopupRequestList');

$routes->get('/topup_request_upsert', 'Topup_request::topup_request_upsert');
$routes->get('/topup_request_upsert/(:num)', 'Topup_request::topup_request_upsert/$1');
$routes->post('/topup_request_submit', 'Topup_request::topup_request_submit');
$routes->post('/topup_request_del', 'Topup_request::topup_request_del');
/* End Topup Request */


/******************************************************************************************************************************
 * Below EC
 */

/* Start EC */
$routes->get('/ec', 'Ec::index');
$routes->get('/ec/category', 'Ec::category');

$routes->get('/api/ec/fetchCategoryList', 'Ec::fetchCategoryList');

$routes->get('/ec/category_product_list/(:num)/(:any)', 'Ec::category_product_list/$1/$2');
$routes->get('/api/ec/getCategoryProductList/(:num)/(:any)', 'Ec::getCategoryProductList/$1/$2');

$routes->get('/ec/product_detail/(:num)/(:any)', 'Ec::product_detail/$1/$2');
$routes->get('/api/ec/getProductDetail/(:num)/(:any)', 'Ec::getProductDetail/$1/$2');
/* End EC */


/* Start EC Cart */
$routes->get('/ec/cart', 'Ec::cart');

$routes->post('/api/ec/addItemIntoCart', 'Ec::addItemIntoCart');

$routes->get('/api/ec/fetchCartList', 'Ec::fetchCartList');
$routes->post('/api/ec/updateCartItemQty', 'Ec::updateCartItemQty');
$routes->post('/api/ec/deleteCartItem', 'Ec::deleteCartItem');
$routes->post('/api/ec/deleteCartItems', 'Ec::deleteCartItems');
/* End EC Cart */


/* Start EC Wallet */
$routes->get('/ec/wallet_topup', 'Ec::wallet_topup');
$routes->get('/ec/topup_log', 'Ec::topup_log');
$routes->post('/api/ec/getWalletBalance', 'Ec::getWalletBalance');
/* End EC Wallet */


/* Start Checkout */
$routes->get('/ec/checkout', 'Ec::checkout');
$routes->get('/ec/checkout_success', 'Ec::checkout_success');
$routes->post('/api/get_service_price', 'Service::get_service_price');
/* End Checkout */