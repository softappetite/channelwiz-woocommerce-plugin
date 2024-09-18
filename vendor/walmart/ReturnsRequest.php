<?php
namespace App\Services\Walmart;

class ReturnsRequest extends AbstractRequest {


	
	public function returns($params=array(),$nextCursor = null) {
		
		if($nextCursor == null) {
			
		}
		else
		{
			parse_str($nextCursor, $params);
			
		}
		return $this->get('', $params);
	}
	
	
	public function getEndpoint() {
		return '/v3/returns';
	}

	protected function getResponse() {
		return 'WalmartSellerAPI\ReturnsResponse';
	}
}