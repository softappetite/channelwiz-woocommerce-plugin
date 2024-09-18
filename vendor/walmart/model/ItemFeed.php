<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class ItemFeed extends AbstractModel {
    public function __construct($data = null) {
        parent::__construct('product/MPItemFeed', $data);
    }
}
?>