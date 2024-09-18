<?php
namespace App\Services\Walmart;

class SearchRequest extends AbstractRequest {

	
	public function items($params) {
		
		return $this->get('', $params);
	}
	
	

	public function getEndpoint() {
		return '/v3/items/walmart/search';
	}

	protected function getResponse() {
		return 'WalmartSellerAPI\SearchResponse';
	}
}