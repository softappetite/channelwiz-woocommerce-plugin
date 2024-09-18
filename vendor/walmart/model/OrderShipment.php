<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class OrderShipment extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct(array(
            'orders/ShipConfirmRequestV3.3',
            'orders/orderShipment'
        ), $data);
    }
}
?>