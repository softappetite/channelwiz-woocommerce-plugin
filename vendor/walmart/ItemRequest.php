<?php
//namespace App\Services\Walmart;

//use App\Services\Walmart\AbstractRequest;

class ItemRequest extends AbstractRequest {

	public function retire($sku) {
		return $this->delete('/'.$sku);
	}
	
	public function items($params) {
		//die('zzz');
		return $this->get('', $params);
	}
	
	public function item($sku) {
		return $this->get('/'.$sku);
	}
	
	public function item2($sku) {
		return $this->get('/'.$sku,array('productIdType'=>'ITEM_ID'));
	}
	
	public function item3($sku) {
		return $this->get('/'.$sku,array('productIdType'=>'SKU'));
	}

	public function getEndpoint() {
		return '/v3/items';
	}

	protected function getResponse() {
		return 'WalmartSellerAPI\ItemResponse';
	}
}