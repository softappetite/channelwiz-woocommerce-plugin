<?php
namespace App\Services\Walmart;

use App\Services\Walmart\model\OrderLinesCancel;

class OrderLinesCancelRequest extends AbstractRequest {

	public function cancel($purchaseOrderId,$orders) {
		
		$orderCancel = new OrderLinesCancel();
		
		
		foreach($orders as $order) {
		
		//die(var_dump($order));
		
		$orderCancel['orderLines']['orderLine'][] = array(
				'lineNumber' =>$order['id'],
				'orderLineStatuses' => array(
					'orderLineStatus' => array(
						array(
							'status' => 'Cancelled',
							'cancellationReason' =>$order['cancellationReason'],
							'statusQuantity' => array(
								'unitOfMeasurement' => 'Each',
								'amount' =>$order['quantity']
							),
							
						)
					)
				)
			);
		
		
		}
              
		//die(var_dump($orderCancel->asXML()));
		//$orderCancel=$order;
		
		//return '<ns3:status>Cancelled</ns3:status>';
		return $this->post('/'.$purchaseOrderId.'/cancel', $orderCancel->asXML());
	}

	public function getEndpoint() {
		return '/v3/orders';
	}

	protected function getResponse() {
		return 'WalmartSellerAPI\OrderLinesCancelResponse';
	}

	public function getHeaders($url, $method, $headers = array()) {
		$headers[0]='Content-Type: application/xml';
		//$headers[] = 'WM_CONSUMER.CHANNEL.TYPE: '.$this->config['channelTypeId'];
		return parent::getHeaders($url, $method, $headers);
	}
}