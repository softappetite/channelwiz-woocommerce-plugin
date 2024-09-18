<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class ItemPriceResponse extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct('responses/ItemPriceResponse', $data);
    }
}
?>