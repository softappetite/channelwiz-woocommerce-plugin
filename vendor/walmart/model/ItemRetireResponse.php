<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class ItemRetireResponse extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct('responses/ItemRetireResponse', $data);
    }
}
?>