<?php
namespace App\Services\Walmart;

class OrderResponse extends AbstractResponse {
    protected function getModel($name) {
        switch($name) {
            case 'list':
                return 'WalmartSellerAPI\model\OrderList';
            case 'order':
                return 'WalmartSellerAPI\model\Order';
            default:
                throw new \Exception('OrderResponse '.$name.' Not Found');
        }
    }    
}