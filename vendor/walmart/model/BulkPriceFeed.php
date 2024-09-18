<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class BulkPriceFeed extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct(array(
            'prices/BulkPriceFeed',
            'prices/PriceFeed'
        ), $data);
    }
}
?>