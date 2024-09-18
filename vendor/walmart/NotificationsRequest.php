<?php
namespace App\Services\Walmart;

class NotificationsRequest extends AbstractRequest {

	
	
	public function eventTypes() {
		return $this->get('/eventTypes');
	}
	
	public function newSubscription($params=array()) {
		
		$params=json_encode($params,true);
		
		return $this->post('/subscriptions',$params);
	}
	
	public function retire($subscriptionId) {
		
	
		return $this->delete('/subscriptions/'.$subscriptionId);
	}
	
		public function allSubscriptions($params=array()) {
		return $this->get('/subscriptions',$params);
	}

	
	public function getEndpoint() {
		return '/v3/webhooks';
	}

	protected function getResponse() {
		return 'WalmartSellerAPI\NotificationsResponse';
	}
	
}