<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class InventoryFeed extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct('inventory/InventoryFeed', $data);
    }
}
?>