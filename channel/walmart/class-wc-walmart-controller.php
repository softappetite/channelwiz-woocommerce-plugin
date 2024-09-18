<?php

//namespace App\Http\Controllers\Cron;

class WC_Walmart_Controller 
{
	

	 public function addProduct($id = '',$isAuth = true,$match_upc=false)
    {	
		
		
		$wm_product_id = get_post_meta($id, '_walmart_product_id', true);
		$product = wc_get_product($id);
			
			
          
            if (!empty($product)) {
                $products_data = array();
				
				
					$upc = get_post_meta($id, '_global_unique_id', true);	
					
				 
					
					$stock_status=$product->get_stock_status();
					$quantity=0;
					if($stock_status=='instock')
					{
						if(empty($product->get_stock_quantity()))
							$quantity=5;// default qty if stock not mentioned
						else
							$quantity=$product->get_stock_quantity();
					
					}
			
					//die(var_dump( $product->get_price()));
					$default_package_weight=rand(1, 10) / 100;
                    $totalAmount = $product->get_price() * $quantity;
					
					$title = !empty($product->get_name()) ? substr($product->get_name(), 0, 200) : '';					
                    $price =number_format($product->get_price(),2);
					
					
                    $description = !empty($product->get_description()) ? $product->get_description() : $product->get_name();	
								
					$package_height = !empty($product->get_height()) ? (int)$product->get_height() : rand(5, 12);	
					$package_length = !empty($product->get_length()) ? (int)$product->get_length() : rand(5, 15);	
					$package_width = !empty($product->get_width()) ? (int)$product->get_width() : rand(5, 10);	
					
					$weight = !empty($product->get_weight()) ? $product->get_weight() : $default_package_weight;
				
				
					$brand=get_post_meta($id, '_Brand', true );;
					$item_specifics=$description;
					$wm_category_specifics=null;
					
					$image_urls=[];
					
					if ($product->get_image_id())
					 $image_urls[]=wp_get_attachment_url( $product->get_image_id());
					
				
					$subCategoryName='Other';
					//if($products->img1 &&  strlen($upc)==12 && strlen($title)>0 && strlen($brand)>0  && $weight>0 && strlen($item_specifics)>0 && strlen($description)>0)
					
					if(strlen($upc)==12 && strlen($title)>0)
					{
					
					$products_data[]=array('id'=>$id,'fulfillmentLagTime'=>1,'productIdType'=>'UPC','productId'=>$upc,'sku'=>$product->get_sku(),'subCategory'=>$subCategoryName,'price'=>$price,'weight'=>$weight,'qty'=>$quantity,'productName'=>substr(addslashes($title),0,200),'shortDescription'=>substr(addslashes( $description),0,4000),'brand'=>$brand,'features'=>substr(addslashes( $item_specifics),0,4000),'mainImageUrl'=>$image_urls[0],'images'=>$image_urls,'wm_category_specifics'=>$wm_category_specifics);	
						$ids[]=$id;
						
					
						
					}
						
					
					
				 }
				 
				// die(var_dump($products_data));
				
							if(count($products_data)>0)
							{		
								$wm_helper=new WC_Walmart_Helper;							
								$feedId=$wm_helper->addNewProduct($products_data);
								if($feedId)
								{
									
								
								}
							}
							
					
		
			
	
		
		
	}
     public function fetchOrder()
    {
		
			$wm_helper=new WC_Walmart_Helper;
			$response=$wm_helper->fetchOrder(strtotime("-365 days"));
			die('xx');
			foreach($response as $Orderdata)
			{
				if(isset($Orderdata['list']))
				{
					foreach($Orderdata['list']['elements']['order'] as $order)
					{
						
						$wm_order = Order::where('wm_purchaseOrderId',$order['purchaseOrderId'])->where('user', $user->id)->first();                       
						if(empty($wm_order))
						{
							$wm_order = new Order();
							
						
							$wm_order->user = $user->id;
							$wm_order->wm_purchaseOrderId=$order['purchaseOrderId'];					
							$fullName=$order['shippingInfo']['postalAddress']['name'];
							
						
							//$wm_order->filled_date=date('Y-m-d', $order['orderDate']);
							
							$addressLine1=$order['shippingInfo']['postalAddress']['address1']?? '';
							$addressLine2=$order['shippingInfo']['postalAddress']['address2']?? '';
							
							$city=$order['shippingInfo']['postalAddress']['city']?? '';
							$stateOrProvince=$order['shippingInfo']['postalAddress']['state']?? '';
							$postalCode=$order['shippingInfo']['postalAddress']['postalCode']?? '';
							$countryCode=$order['shippingInfo']['postalAddress']['country']?? '';
							
							
							$wm_order->street1 =nl2br("$fullName\n$addressLine1 $addressLine2 \n$city, $stateOrProvince\n$postalCode\n$countryCode");																																										                            $wm_order->street1=str_replace('<br />','',$wm_order->street1);
							
							// dd($order['orderLines']['orderLine'][0]);
							if(isset($order['orderLines']['orderLine'][0]))
							{
							
								$line=$order['orderLines']['orderLine'][0];
								
								if(isset($line['item']['sku']))
								{
									//dd($line);
									$wm_order->title=$line['item']['productName'];
									
									$wm_order->subtotal=$line['charges']['charge'][0]['chargeAmount']['amount'];
									
									
									
									$wm_order->quantity=1;
									$wm_order->wm_sku=$line['item']['sku'];
									
									if($wm_order->subtotal>0)
										$wm_order->wm_fees =round(($wm_order->subtotal*0.15),2);
									else
										$wm_order->wm_fees =0;
									if(isset($line['charges']['charge'][0]['tax']['taxAmount']['amount']))	
										$wm_order->tax_collected =$line['charges']['charge'][0]['tax']['taxAmount']['amount'];
									else
										$wm_order->tax_collected=0;	
									
									$wm_order->total=($wm_order->subtotal+$wm_order->tax_collected);
									
									$wm_order->supplier_subtotal =0;	
									$wm_order->net_price = round(( $wm_order->total-$wm_order->wm_fees),2);
									
									//product details							
									$wm_product = Product::where('wm_sku',$line['item']['sku'])->where('user', $user->id)->first(); 
									if($wm_product)
									{
										$wm_order->img=$wm_product->img1;
										
										
									}
									
								}
							
							}
														
							$wm_order->save();
							
						}
							
						
						
					}// for each order item
					
				}
			}
		
		
		
	}
    public function fetchProduct()
    {
		
			$wm_helper=new WC_Walmart_Helper;
			$productsdata=$wm_helper->fetchProductList();		
			die(var_dump($productsdata));	
				foreach($productsdata as $products )				
				{			               
					foreach($products as $p){
						if(isset($p['upc']))
						$return=$wm_helper->getImageByUPC($wm_credentials['id'],array('upc'=>$p['upc']));
						$images=$return[0];
						$wm_category_id=$return[1];
						if(isset($p['wpid'])){						
						$wm_product = Product::where('wm_sku',$p['sku'])->where('user', $user->id)->first();                       
							if(empty($wm_product)){
								$wm_product = new Product();							
								$wm_product->wm_pid = $p['wpid'];
								$wm_product->user = $user->id;
								$wm_product->title  = $p['productName']; 
								$wm_product->wm_sku  = $p['sku'];
								$wm_product->wm_upc  = $p['upc'];
								$wm_product->price  =0;// $p['price']['amount'];
								$wm_product->target_price  = $p['price']['amount'];	
								$wm_product->brand  = @$p['brand'];	
								$wm_product->wm_category_id=$wm_category_id;	
								
								/*$status_arr=array('1'=>'PUBLISHED', '2'=>'READY_TO_PUBLISH', '3'=>'IN_PROGRESS', '4'=>'UNPUBLISHED', '5'=>'STAGE','6'=>'SYSTEM_PROBLEM');								if($p['publishedStatus'])									
									$wm_product->wm_status=@array_search ($p['publishedStatus'], $status_arr);					*/		 
			
								$wm_product->wm_publishedStatus=$p['publishedStatus'];	
								$wm_product->wm_lifecycleStatus=$p['lifecycleStatus'];		
													
								if(isset($images[0]))
								  $wm_product->img1=$images[0];
								 if(isset($images[1]))
								  $wm_product->img2=$images[1]; 
								 if(isset($images[2]))
								  $wm_product->img3=$images[2];								
								 if(isset($images[3]))
								  $wm_product->img4=$images[3];								  
								 if(isset($images[4]))
								  $wm_product->img5=$images[3];
								  if(isset($images[5]))
								  $wm_product->img6=$images[3];
								  if(isset($images[6]))
								  $wm_product->img7=$images[3];
								  if(isset($images[7]))
								  $wm_product->img8=$images[3];
								  if(isset($images[8]))
								  $wm_product->img9=$images[3];
								  
								  $wm_product->wm_enabled=1;
								  $wm_product->isListed=1;
								 
								 
								
								$wm_product->save();
							
							}
							else
							{
								$wm_product->wm_sku  = $p['sku'];
								$wm_product->wm_upc  = $p['upc'];
								$wm_product->price  =0;// $p['price']['amount'];
								$wm_product->target_price  = $p['price']['amount'];	
								$wm_product->wm_pid = $p['wpid'];
								$wm_product->wm_enabled=1;
								$wm_product->isListed=1;
								if($wm_category_id>0)
									$wm_product->wm_category_id=$wm_category_id;
									
								/*$status_arr=array('1'=>'PUBLISHED', '2'=>'READY_TO_PUBLISH', '3'=>'IN_PROGRESS', '4'=>'UNPUBLISHED', '5'=>'STAGE','6'=>'SYSTEM_PROBLEM');								if($p['publishedStatus'])									
									$wm_product->wm_status=@array_search ($p['publishedStatus'], $status_arr);		*/
									
								$wm_product->wm_publishedStatus=$p['publishedStatus'];	
								$wm_product->wm_lifecycleStatus=$p['lifecycleStatus'];					 
									
								$wm_product->save();
								
							}
						
						}
						else
						{
							
							
						}
						
						
					}
						
				}
			
			
			
	
		return true;
	
	
	}
	
	 public function error_report($market,$id, $msg)
    {
		
	
		$modelProduct = Product::where('id', '=', $id)->first();		
		if($modelProduct->error_report)
		{
			$error_report=json_decode($modelProduct->error_report,true);			
			$error_report[$market]=$msg;
			
			
		}
		else
		{
			$error_report[$market]=$msg;
		}
		if($modelProduct)
		{
			$modelProduct->error_report=json_encode($error_report);
			$modelProduct->save();
		
		}
		
		
	}
	
		private function batchQueueWithInsert($id, $queue_inserted = true)
    {
        $product_batch = ProductBatch::where('id', $id)->first();

        if($product_batch) {
            if(!$queue_inserted) {
                $product_batch->decrement('total_inserted');
            }
			
            
            
        }
    }
	
	
	public function processWMFeed($items,$user_id,$store_id)
	{
		
		foreach($items as $item)
		{
			
			if($item['ingestionStatus']=='SUCCESS')
			{
				if($item['sku']){	
						
					$product = Product::where('wm_sku', $item['sku'])->where('user', $user_id)->first();
					if($product)
					{
						$product->wm_pid=$item['itemid'];	
						$product->wm_status=1;					
						$product->save();
						
						if(isset($item['sku']))
						{
							$skus_inv[$item['sku']]=array((int)$product->inventory);						
							$wh = new WalmartHelper();
							if(count($skus_inv)>0)
							{
								$feedIds[]=$wh->bulkUpdateInventory($store_id,$skus_inv);															
								
							}
						
						}
						
						
							
					}
				}				
			}
			else // if feed status get Error
			{
				
				if($item['sku']){	
						
					$product = Product::where('wm_sku', $item['sku'])->where('user', $user_id)->first();
					if($product)
					{
						
						
						if($product->ebay_enabled!=1 && $product->amazon_enabled!=1 && $product->wm_enabled==1 && $product->shopify_enabled!=1)
						{
							
							$productQueue = ProductQueue::find($product->productQueue_id);						
							if($productQueue)
							{
								$error_msg=[];
								$this->batchQueueWithInsert($productQueue->product_batch_id, false);
								
								foreach($item['ingestionErrors'] as $errors)
									foreach($errors as $error)
										$error_msg[]=@$error['description'];	
								if($error_msg)
								{								
									$productQueue->queue_status ='Error:'.implode(',',$error_msg);
									$productQueue->save();
								}
								$product->delete();		
							
							}
						
						
						}
						
						/*$product->wm_uploaded=null;	
						$product->wm_status=2;					
						$product->save();*/
						
						//$this->error_report('walmart',$product->id,$item);
						
					}
					
				}
				
				
				
			}
					
				
		
							
		}
		
			
						
		
		
	}
	
	public function updateWMFeedStatus($feedId=0)
	{
		
		set_time_limit(0);
		
		$store_id=null;
		
		if($feedId>0)
			$sql="select * from wm_feed  where   feed_type='item' and id='".$feedId."' limit 1";
		else
			$sql="select * from wm_feed  where   ( feed_status='pending'  or   feed_status='INPROGRESS'  ) and feed_type='item'    order by updated_at desc limit 1";
		//$rows=Yii::$app->db->createCommand($sql)->queryAll();		
		$rows=\DB::select($sql);
		foreach($rows as $row)
		{
				
			$skus_inv=array();
			$store_id=$row->ref_wm_store_id;
			$params=array('limit'=>50);
			$wh = new WalmartHelper();
			$return =$wh->getFeedStatus($store_id,$row->feed_id,$params);			
			$return=json_decode($return,true);			
			if($return)
			{
				
				
				$model=WalmartFeed::find($row->id);		
				if($model)
				{
					
					$model->feed_status=$return['feedStatus'];
					$model->submitted=$return['itemsReceived'];
					$model->proceed=$return['itemsSucceeded'];
					$model->pending=$return['itemsProcessing'];
					$model->error=$return['itemsFailed'];
					$model->save();
					//die(var_dump($return['itemsReceived']));
					
					if(isset($return['itemDetails']['itemIngestionStatus']))
					{
						$items=$return['itemDetails']['itemIngestionStatus'];
						$this->processWMFeed($items,$row->user,$store_id);
						
						//$total_items=$return['itemsSucceeded'];
						$total_items=$return['itemsReceived'];
						
						$limit=50;
						$total_page=(int)ceil(($total_items/$limit));
						
						for($page=1;$page<=$total_page;$page++)
						{
							
							$offset=$limit*$page;
							$params=array('limit'=>50,'offset'=>$offset);
							$wh = new WalmartHelper();
							$return =$wh->getFeedStatus($store_id,$row->feed_id,$params);			
							$return=json_decode($return,true);							
							if($return)
							{
								
								if(isset($return['itemDetails']['itemIngestionStatus'])){
								$items=$return['itemDetails']['itemIngestionStatus'];
								$this->processWMFeed($items,$row->user,$store_id);
								
								
								
								}
							}
							
							
						}
						
						
					}
					
				
				
				}
			
			}

		}// for each store 
		
		
	}
	
	// update walmart feed status
	public function updateProduct($id)
    {
		$user = Auth::user()->accountRole();  
		$wm_credentials = WmStore::where('user_id', '=', $user->id)->first();
		
        if ($id && $wm_credentials) {
			 
			 
			   $store_id=$wm_credentials['id'];
            
                $product = Product::where('id', $id)->whereNotNull('wm_pid')->whereNotNull('wm_sku')->first();
                
                if (!empty($product)) {
                    if ($product->wm_pid) {
						
						$quantity = $product->inventory;						
						$sku=$product->wm_sku;
						$skus_inv[$sku]=array((int)$quantity);
						$price = $product->target_price;
						$skus_price=[];
			
						if($price>0)
						{
							$selling_price=$price;	
							$skus_price[$sku]=array('price'=>$selling_price,'salePrice'=>0);
							
						}	
						
                       $wh = new WalmartHelper();
					   if(count($skus_inv)>0)
						{
							$feedIds[]=$wh->bulkUpdateInventory($store_id,$skus_inv);	
							
							
						}
						
					   if(count($skus_price)>0)
						{
							$feedIds[]=$wh->bulkUpdatePricing($store_id,$skus_price);
							
						
						}
						
						
						
					   
					   
					   

					}
					
				}
				
		}
		
	}
	//unpublish product
	public function publishUnPublishProduct($id,$qty=0)
    {
		$user = Auth::user()->accountRole();  
		$wm_credentials = WmStore::where('user_id', '=', $user->id)->first();
		
        if ($id && $wm_credentials) {
			 
			 
			   $store_id=$wm_credentials['id'];
            
                $product = Product::where('id', $id)->whereNotNull('wm_pid')->whereNotNull('wm_sku')->first();
                
                if (!empty($product)) {
                    if ($product->wm_pid) {
						
						$quantity = $product->inventory;						
						$sku=$product->wm_sku;
						
						if($qty==0)
							$skus_inv[$sku]=array((int)$qty);
						else
						   $skus_inv[$sku]=array((int)$quantity);
						
						$price = $product->target_price;
						$skus_price=[];			
						
                       $wh = new WalmartHelper();
					   if(count($skus_inv)>0)
						{
							$feedIds[]=$wh->bulkUpdateInventory($store_id,$skus_inv);	
							
							
						}
						
					  

					}
					
				}
				
		}
		
	}
	
	// single product retired
	public function productRetired($id,$isAuth=true)
    {
		$sku_list=[];
		
		 if (!empty($id)) {
			  
			$product = Product::where('id', $id)->first();
					
			if($isAuth) {
				$user = Auth::user()->accountRole();  
			} else {
				$user = User::find($product['user']);
			}		
		
			$wm_credentials = WmStore::where('user_id', '=', $user->id)->first();
			if(!empty($wm_credentials)){	
		
					$sku_lis []=$product['wm_sku'];
				
						
				
				$wh = new WalmartHelper();
				if(count($sku_lis)>0)
				{
					return $wh->bulkRetire($wm_credentials['id'],$sku_lis);	

				
				}
			
			}
			
		
		 }
		
		
	}
	
	public function bulkRetired($id,$isAuth=true)
    {
		$sku_list=[];
		
		 if (!empty($id)) {
			  
			$marketplace = Marketplace::where('id', $id)->first();
					
			if($isAuth) {
				$user = Auth::user()->accountRole();  
			} else {
				$user = User::find($marketplace['user_id']);
			}		
		
			$wm_credentials = WmStore::where('user_id', '=', $user->id)->first();
			if(!empty($wm_credentials)){	
		
				$getProducts = Product::query() 
				->select('wm_sku')          
				->where('user', $user->id) 
				->where('marketplace_id', $id)           
				->whereNotNull('wm_pid')			          
				->get();
				
				foreach ($getProducts as $ky => $product) {
					$sku_list []=$product['wm_sku'];
				
				}		
				
				$wh = new WalmartHelper();
				if(count($sku_list)>0)
				{
					$feedIds[]=$wh->bulkRetire($wm_credentials['id'],$sku_list);	
					
				
				}
			
			}
			
		
		 }
		
		
	}
		
	// Walmart Order Notification
	public function notification($ref_store_id=0)
	{
		
		if($ref_store_id>0)
		{
					$sql="select id from wm_store where id not in ( select ref_wm_store_id from wm_notifications_subscriptions ) and id='".$ref_store_id."'   LIMIT 1";
				//else
					//$sql="select id from wm_store where id not in ( select ref_wm_store_id from wm_notifications_subscriptions )   LIMIT 1";
				
				$rows=\DB::select($sql);					
				foreach($rows as $row)
				{
					
					$model = WmStore::find($row->id);			
					if($model)
					{
						$store_id=$model->id;				
						$wh = new WalmartHelper();
						$return =$wh->getNotificationEventsType($store_id);	
						if($return)
						{
							
							$rtn=$this->subscribe($store_id);					
							$model->is_subscribe_to_notification=1;
							$model->save();
							
							
						}
						
						
					}
					
				}
		
		
		}
		
	}
	
	
	
	//subscribe to notification
	public function subscribe($store_id)
	{
		$data=[];
		$return=null;
		
		$sql="SELECT * FROM wm_notifications_events_type  where ref_wm_store_id= '".$store_id."' and  eventUrl is not null and eventType not in 
( select eventType from wm_notifications_subscriptions where ref_wm_store_id= '".$store_id."' ) ";


	     $rows=\DB::select($sql);			
		if(count($rows)>0)
		{
			
			
				foreach($rows as $row)
				{
					
						$events=array('resourceName'=>$row->resourceName,'eventType'=>$row->eventType,'eventVersion'=>$row->eventVersion,'eventUrl'=>$row->eventUrl,'status'=>'ACTIVE');						
						$params['events'][]=$events;
					
				}
				
				
				if(count($params['events'])<=3)
				{
					
					$wh = new WalmartHelper();
					$return =$wh->newSubscription($store_id,$params);	
				}
			
			
		}
		
		return $return;
	}
	
	
	
	
	public function searchExistingProduct($params)
	{
		$user = Auth::user()->accountRole();  
		$wm_credentials = WmStore::where('user_id', '=', $user->id)->first();
		
		$wh = new WalmartHelper();
		$return =$wh->searchExistingProduct($wm_credentials['id'],$params);		
		return $return;
	}
	
	
	

		

}

