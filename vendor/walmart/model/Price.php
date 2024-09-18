<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class Price extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct(array(
            'prices/BulkPriceFeed',
            'prices/Price'
        ), $data);
    }
}
?>