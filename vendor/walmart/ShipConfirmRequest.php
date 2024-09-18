<?php
namespace App\Services\Walmart;

use App\Services\Walmart\model\OrderShipment;

class ShipConfirmRequest extends AbstractRequest {

	public function confirm($purchaseOrderId, $shipments) {
		$shipConfirm = new OrderShipment();

		$shipConfirm['orderLines'] = array(
			'orderLine' => array()
		);

		$utcTimezone = new \DateTimeZone("UTC");
		$timezone = new \DateTimeZone(date_default_timezone_get());

		foreach($shipments as $shipment) {
			$shipTime = new \DateTime();
			$shipTime->setTimezone($timezone);
			$shipTime->setTimestamp($shipment['shipTime']);
			$shipTime->setTimezone($utcTimezone);

			if(empty($shipment['shippingProvider'])) {
				$carrierName = array(
					'otherCarrier' => $shipment['shippingProviderName']
				);
			} else {
				$carrierName = array(
					'carrier' => $shipment['shippingProvider']
				);
			}

			$shipConfirm['orderLines']['orderLine'][] = array(
				'lineNumber' => $shipment['id'],
				'orderLineStatuses' => array(
					'orderLineStatus' => array(
						array(
							'status' => 'Shipped',
							'statusQuantity' => array(
								'unitOfMeasurement' => 'Each',
								'amount' => $shipment['quantity']
							),
							'trackingInfo' => array(
								'shipDateTime' => $shipTime->format('Y-m-d\TH:i:s.u\Z'),
								/*'shipDateTime' => '2021-04-02T17:04:42',*/
								'carrierName' => $carrierName,
								'methodCode' => $shipment['shippingMethod'],
								'trackingNumber' => $shipment['trackingNumber']
							)
						)
					)
				)
			);
		}
//die(var_dump($shipConfirm->asXML()));
		
		return $this->post('/'.$purchaseOrderId.'/shipping', $shipConfirm->asXML());
		//return $this->post('/'.$purchaseOrderId.'/shipping', $shipConfirm->asXML());
	}

	public function getEndpoint() {
		return '/v3/orders';
	}

	protected function getResponse() {
		return 'WalmartSellerAPI\ShipConfirmResponse';
	}

	public function getHeaders($url, $method, $headers = array()) {
		$headers[0]='Content-Type: application/xml';
		//die(var_dump($headers));
		
		//$headers[] = 'WM_CONSUMER.CHANNEL.TYPE: '.$this->config['channelTypeId'];
		return parent::getHeaders($url, $method, $headers);
	}
}