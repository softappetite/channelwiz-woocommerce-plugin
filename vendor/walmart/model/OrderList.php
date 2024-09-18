<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class OrderList extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct(array(
            'orders/PurchaseOrderV3.3',
            'orders/list'
        ), $data);
    }
}
?>