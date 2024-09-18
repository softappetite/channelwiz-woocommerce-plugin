<?php
namespace App\Services\Walmart;

class OrderLinesCancelResponse extends AbstractResponse {
    protected function getModel($name) {
        switch($name) {
            case 'order':
                return 'WalmartSellerAPI\model\Order';
            default:
                throw new Exception('OrderLinesCancelResponse '.$name.' Not Found');
        }
    }    
}