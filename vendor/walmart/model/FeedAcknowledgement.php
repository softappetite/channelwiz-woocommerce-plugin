<?php
namespace App\Services\Walmart\model;

use App\Services\Walmart\model\AbstractModel;

class FeedAcknowledgement extends AbstractModel {

    public function __construct($data = null) {
        parent::__construct('responses/FeedAcknowledgement', $data);
    }
}
?>