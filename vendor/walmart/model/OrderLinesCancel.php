<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class OrderLinesCancel extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct(array(
            'orders/CancelRequestV3.3',
            'orders/orderCancellation'
        ), $data);
    }
}
?>