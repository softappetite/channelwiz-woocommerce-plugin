<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class Inventory extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct(array(
            'inventory/Inventory',
            'inventory/inventory'
        ), $data);
    }
}
?>