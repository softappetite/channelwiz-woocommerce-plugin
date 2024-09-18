<?php
namespace App\Services\Walmart;

use App\Services\Walmart\model\ItemFeed;

class ProductFeedRequest extends FeedRequest {

	public function submit($type, $items) {
		$utcTimezone = new \DateTimeZone("UTC");
		$timezone = new \DateTimeZone(date_default_timezone_get());
		
		$time = new \DateTime();
		$time->setTimezone($timezone);
		$time->setTimestamp(time());
		$time->setTimezone($utcTimezone);
	
		$feed = new ItemFeed();

		 $uniqueID = time();	
		$feed['MPItemFeedHeader'] = array(
				"version"=>"3.2",
				"requestId"=>"36588",
				"requestBatchId"=>"36588",
				
		);
		
		
		
	$feed['MPItem'] =array( array(
					'processMode'=>'REPLACE_ALL',
					'feedDate' => $time->format('Y-m-d\TH:i:s.u\Z'),				
                    'sku' =>'DFYEZ-0002748',
					'productIdentifiers' =>[
					'productIdentifier' =>[
                                'productIdType' => 'UPC',
                                'productId' => '673419250658',
                            ]
								
					],
					'MPProduct' =>array(
					'productName' =>'LEGO Super Heroes Mighty Micros: Captain America vs. Red S, 76065',					
					'category' => [
                'ToysCategory' => [
                    'Toys' => [
                        'shortDescription' => 'LEGO Super Heroes Mighty Micros: Captain ',
                        'brand' => 'LEGO',
						]
						]
						]
													
					),
					'MPOffer' =>array(
					'price' =>29.32,
					'ShippingWeight' =>array(
					'measure'=>0,
					'unit' =>'lb',
					),
					'ProductTaxCode' => '2038710',
								
					)
                 ));
				 
				 
				
			
				 

		//$feed[$type] = $items;
		
	//die(var_dump($feed->asXML()));
	
	
	
		
		//die($feed->asXML());
		
		//die($items);
		
		
		return $this->post('?feedType=item&setupType=byMatch', $feed->asXML());
		//return $this->post('?feedType=item&setupType=byMatch', $feed->asXML());
	}
	
	
	/*public function getHeaders($url, $method, $headers = array()) {
		$headers[0]='Content-Type: application/xml';
		
		return parent::getHeaders($url, $method, $headers);
	}
	*/
	public function xxxsubmit($type, $items) {
		$utcTimezone = new \DateTimeZone("UTC");
		$timezone = new \DateTimeZone(date_default_timezone_get());
		
		$time = new \DateTime();
		$time->setTimezone($timezone);
		$time->setTimestamp(time());
		$time->setTimezone($utcTimezone);
	
		$feed = new ItemFeed();

		$feed['MPItemFeedHeader'] = array(
			'version' => '3.2',
			'feedDate' => $time->format('Y-m-d\TH:i:s.u\Z')
		);

		$feed[$type] = $items;
		
		
		
		return $this->post('?feedType=item', $feed->asXML());
	}
}

/*$xml='<MPItemFeed xmlns="http://walmart.com/mp/v3/item">
  <MPItemFeedHeader>
    <version>3.1</version>
    <mart>WALMART_US</mart>
  </MPItemFeedHeader>
  <MPItem>
    <processMode>CREATE</processMode>
    <sku>WAL047875881273000</sku>
    <productIdentifiers>
      <productIdentifier>
        <productIdType>UPC</productIdType>
        <productId>6355SU1258555</productId>
      </productIdentifier>      
    </productIdentifiers>
    <MPOffer>
      <price>39.99</price>
      <StartDate>2021-05-01</StartDate>
      <EndDate>2021-07-03</EndDate>
      <MustShipAlone>No</MustShipAlone>
      <ShippingWeight>
        <measure>0.5</measure>
        <unit>lb</unit>
      </ShippingWeight>
      <ProductTaxCode>2042518</ProductTaxCode>
      <shipsInOriginalPackaging>No</shipsInOriginalPackaging>
    </MPOffer>
  </MPItem>
</MPItemFeed>';*/