<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class PartnerFeedResponse extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct('responses/PartnerFeedResponse', $data);
    }
}
?>