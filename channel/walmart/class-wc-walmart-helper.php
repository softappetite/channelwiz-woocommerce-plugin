<?php
//namespace App\Http\Helpers\Walmart;

/*use App\Services\Walmart\ItemRequest;
use App\Services\Walmart\SearchRequest;
use App\Services\Walmart\OrderRequest;
use App\Models\WmStore;
use App\Services\Walmart\FeedRequest;
use App\Services\Walmart\NotificationsRequest;
use App\Models\WmNotificationsEventsType;
use App\Models\WmNotificationsSubscriptions;
use App\Models\WalmartFeed;
use App\Models\Order;
use App\Models\Product;
use App\Models\WmUpcList;*/


class WC_Walmart_Helper {
	
	
	 public function getStoreSetting()
     {
		$arr=array();			
		$arr['clientId']=get_option('wcsmp_walmart_client_id');
		$arr['clientSecret']=get_option('wcsmp_walmart_client_secret');	
		$arr['env']='prod';	
		return $arr;
	   }
	   
	   // product
	    public function getItemList($params=array())
       {
			$setting=$this->getStoreSetting();		
			$WM_CLIENT_ID=$setting['clientId'];		
			$WM_CLIENT_SECRET=$setting['clientSecret'];						
			$config=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new ItemRequest($config,null, $setting['env']);
			return $response =$m->items($params);
		   
	   }
	     public function getOrder($store_id,$purchaseOrderId)
       {
			$config=$this->getStoreSetting($store_id);		  
			$WM_CLIENT_ID=$config['clientId'];		
			$WM_CLIENT_SECRET=$config['clientSecret'];
			$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new OrderRequest($params,null, $config['env']);				
			$response =$m->order($purchaseOrderId);			 
			$response=json_decode($response,true);
			 //die(var_dump($response));
			 $this->saveOrder($config['user_id'],$store_id,$response['order']);
		
			return true;
			 
		   
		   
	   }
	   
	 
	   
	   
	    public function retire($store_id,$sku)
       {
			$config=$this->getStoreSetting($store_id);		  
			$WM_CLIENT_ID=$config['clientId'];		
			$WM_CLIENT_SECRET=$config['clientSecret'];
			$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new ItemRequest($params,null, $config['env']);		
			$response =$m->retire($sku);
			$response=json_decode($response,true);
			// die(var_dump($response));
			
		
			return $response;
			 
		   
		   
	   }
	   
	    public function getItemById($store_id,$item_id)
       {
			$config=$this->getStoreSetting($store_id);		  
			$WM_CLIENT_ID=$config['clientId'];		
			$WM_CLIENT_SECRET=$config['clientSecret'];
			$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new ItemRequest($params,null, $config['env']);		
			$response =$m->item2($item_id);
			$response=json_decode($response,true);
			 die(var_dump($response));
			
		
			return $response;
			 
		   
		   
	   }
	     public function getItemBySKU($store_id,$sku)
       {
			$config=$this->getStoreSetting($store_id);		  
			$WM_CLIENT_ID=$config['clientId'];		
			$WM_CLIENT_SECRET=$config['clientSecret'];
			$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new ItemRequest($params,null, $config['env']);		
			$response =$m->item3($sku);
			$response=json_decode($response,true);
			return $response;
			 
		   
		   
	   }
	   function generateRandomString($length = 16) 
	   {
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
	   }
	   
	    public function getToken($config,$flag=0) {
		  
	
				$curl = curl_init();
				if($config['env']=='prod')
					$url = 'https://marketplace.walmartapis.com/v3/token';		
				else
				  $url = 'https://sandbox.walmartapis.com/v3/token';
					
				$options = array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url,
					CURLOPT_USERAGENT => 'Digital Cloud Commerce',
					CURLOPT_HEADER => 1,
					CURLOPT_RETURNTRANSFER => 1,
					CURLINFO_HEADER_OUT => true
				);
		
				$httpHeaders = array();
		
				$options[CURLOPT_POST] = 1;
				$options[CURLOPT_POSTFIELDS] = 'grant_type=client_credentials';
				$httpHeaders[] = 'Authorization: Basic '.base64_encode($config['clientId'].':'.$config['clientSecret']);
				$httpHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
				$httpHeaders[] = 'Accept: application/json';
				$httpHeaders[] = 'WM_SVC.NAME: Walmart Marketplace';
				$httpHeaders[] = 'WM_QOS.CORRELATION_ID: '.base64_encode($this->generateRandomString());
				$httpHeaders[] = 'WM_SVC.VERSION: 1.0.0';
				
				
			
				$options[CURLOPT_HTTPHEADER] = $httpHeaders;		
				curl_setopt_array($curl, $options);		
				$response = curl_exec($curl);					
				if($response !== false) {
					$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		
					$header = substr($response, 0, $headerSize);
					$body = substr($response, $headerSize);

					$accessToken = json_decode($body, true);

					if(isset($accessToken['access_token'])) {
						
					return  $accessToken['access_token'];

					
					} else {
						
						return  false;
					}
				} else {
					
					
					return  false;
				}
				
				curl_close($curl);
		

			
	}
	 public function fetchProductList()
       {
		   $response=[];
		   ini_set("memory_limit", "-1");
		   set_time_limit(0);
		  
							
			$params=array('limit'=>'50','nextCursor'=>'*');
			$products=$this->getItemList($params);
			$products = json_decode($products, true);
			if(isset($products['ItemResponse']))
			{
				$response[]=$products['ItemResponse'];
				if(isset($products['nextCursor']))
				{			   			   
				 
				   return $response;
				   $limit=50;			 
				   $nextCursor=$products['nextCursor'];	
				   $page=1;			  
				   while($nextCursor)
				   {
						$page++;
						 
						$params=array('limit'=>$limit,'nextCursor'=>$nextCursor, 'includeDetails'=>'true');
						$products=$this->getItemList($store_id, $params);				
						$products = json_decode($products, true);														
						if(isset($products['ItemResponse']))
							$response[]=$products['ItemResponse'];
						
						if(isset($products['nextCursor']))
							$nextCursor=$products['nextCursor'];
						else 
						  break;	
						  
						  //if($page>3)
						  //break;
						  
										   
					   
				   }
				   
				   
				 }
			   
			}
	
		  
		   
		   return $response;
	
	
	   }
	   
	    // product
	    public function getImageByUPC($store_id,$params=array())
       {
			$setting=$this->getStoreSetting($store_id);		  
			$WM_CLIENT_ID=$setting['clientId'];		
			$WM_CLIENT_SECRET=$setting['clientSecret'];						
			$config=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new SearchRequest($config,null, $setting['env']);
			$response =$m->items($params);
			$response = json_decode($response, true);	
			$images=[];	
			$sub_cat_id=0;
			if(isset($response['items'][0]))
			{
				$product=$response['items'][0];
				foreach($product['images'] as $img)
					$images[]=$img['url'];					
					//new get category				
					if(isset($product['properties']['categories']))
					{
						
							 foreach($product['properties']['categories'] as $cat)
							{
							   $sql="SELECT * FROM wm_subcategory WHERE  subCategoryName LIKE '%".str_replace("'", "", $cat)."%' limit 1";
								$rows=\DB::select($sql);								
								if($rows)
								{
									if(isset($rows[0]))
									{
										$row=$rows[0];									
										$sub_cat_id=$row->id;									
										break;
									}
									
								}
							
														
							
						 }
						
						 
					 
					 
					}
					//end category
					
					
						
			}
					
			return array($images,$sub_cat_id);		
	   }
	   
	  
		
	 // Order
	   public function fetchOrder($start_date)
       {
	
			$response=[];
			$config=$this->getStoreSetting();		 
			$WM_CLIENT_ID=$config['clientId'];		
			$WM_CLIENT_SECRET=$config['clientSecret'];
			$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new OrderRequest($params,null, $config['env']);
			$orders =$m->orders($start_date);		      
			$orders = json_decode($orders, true);
			die(var_dump($orders));	
			if(isset($orders['list']))
			{
				$response[]=$orders;				
				$totalItems=$orders['list']['meta']['totalCount'];
				$limit=100;
				$total_page=(int)($totalItems/ $limit);
				$nextCursor=$orders['list']['meta']['nextCursor'];
				for($page=1;$page<=$total_page;$page++)
				{
					
					$orders =$m->orders($start_date,$nextCursor);		
					$orders = json_decode($orders, true);	
					$response[]=$orders;
					if(isset($orders['list']['meta']['nextCursor']))
						$nextCursor=$orders['list']['meta']['nextCursor'];
					else 
					  break;	
				   
				   
			   }
			   
			}
		
		  return $response;
		   
	   }
	   
	function trimFeatures($NewStr) 
{
 $NewStr =str_replace('<p>', '', $NewStr);
 $NewStr =str_replace('</p>', '', $NewStr);
 $NewStr =str_replace('<b>', '', $NewStr);
 $NewStr =str_replace('</b>', '', $NewStr);

 


 return $NewStr;

}	   
	   	   
	   // add new product without setup by match 
 public function addNewProduct($products)
{

	$config=$this->getStoreSetting();	
	$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));	
	$auth =base64_encode($config['clientId'].':'.$config['clientSecret']);  
	//$store_id = $config['store_id'];
	$weight = "0";
	$taxCode = '2038710';
 	$token=$this->getToken($config,1);	
	$body = '{
	"MPItemFeedHeader": {
	"sellingChannel": "marketplace",
	"processMode": "REPLACE",
	"subset": "EXTERNAL",
	"locale": "en",
	"version": "1.4"
	},
	"MPItem": [	
	';
	
	foreach($products as $product)
	{
		
		
		$images=[];
		$img_str="";
		$fearture_str="";
		//$subCategory="Video Games";
		
		$count=0;
		foreach($product['images'] as $img)
		{
			$images[]=$img;
			$img_str.='"'.$img.'",';
			
		}
		
		//dd(explode('^^',$product['features']));
		$optional='';
		$count=0;
		foreach(explode("\n",$product['features']) as $feature)
		{
			$fearture_str.='"'.$this->trimFeatures($feature).'",';
			
		}
		//new 02-11-22
		$wm_category_specifics=json_decode($product['wm_category_specifics'],true);
		if(is_array($wm_category_specifics))
		{
			foreach($wm_category_specifics as $key=>$val)
			{
				
				$controller=new \App\Http\Controllers\CategorymapController();					
				$field_type =$controller->getWalmartCategorySpecFieldTYpe($key);				
				if($val!=null)
				{
					if(is_array($val))
					{
						if (!in_array("Array", $val) && $val[0]!='')
						{
							$str="";
							foreach($val as $va2)
							{
								
								$str.='"'.$va2.'",';
							}
							
						 	$optional.='"'.$key.'": ['.$str.'],';
						}
					
					}
					else
					{
						if($field_type=='number' || $field_type=='integer')
							$optional.='"'.$key.'":'.$val.',';
						
						else
							$optional.='"'.$key.'": "'.$val.'",';
					}
				}
				
			}
		}
			
			/* "keyFeatures": [
            '.$fearture_str.'
          ],*/
		//$fearture_str=$product['features'];
		
		
	
		$optional.='"productSecondaryImageURL": [';	 
		$optional.=$img_str;
		$optional.=' ],';
		
	//if($product['subCategory']=="Video Games")
	  // $optional.='"esrbRating": "Everyone",';  
	  //else
	 //$subCategory="Computers";  
	 
		
	$body.='{
      "Orderable": {
        "sku": "'.$product['sku'].'",
        "productIdentifiers": {
          "productIdType": "'.$product['productIdType'].'",
          "productId": "'.$product['productId'].'"
        },
        "productName": "'.$product['productName'].'",
		 "brand": "'.$product['brand'].'",      	
        "price": '.$product['price'].',
		  "fulfillmentLagTime": '.$product['fulfillmentLagTime'].',
		 "ShippingWeight": '.$product['weight'].'
		
      
        
      },
      "Visible": {
        "'.$product['subCategory'].'": {
          "shortDescription": "'.$product['shortDescription'].'",        
		   "mainImageUrl": "'.$product['mainImageUrl'].'",	  
		 '.$optional.' 
		  "keyFeatures": [
            '.$fearture_str.'
          ],
         
          
         
          
        }
      }
    },';	
		
		
	}
		
	
	$body.='	
		 
       ]
}';
  
  
  die(var_dump($body));
  
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Authorization: Basic '.$auth,	
  'Accept: application/json',
  'WM_SEC.ACCESS_TOKEN: '.$token,
 /* 'Content-Type: application/json',*/
  'WM_QOS.CORRELATION_ID: '.$uuid ,
   'WM_SVC.NAME: Walmart Marketplace'

));
	
// curl_setopt($ch, CURLOPT_HEADER, 1);

if($config['env']=='prod')
	$url='https://marketplace.walmartapis.com/v3/feeds?feedType=MP_ITEM';
else
	$url=$url='https://sandbox.walmartapis.com/v3/feeds?feedType=MP_ITEM';

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_POSTFIELDS,     $body );
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
$feed = json_decode($result);
  

 
curl_close($ch);

//dd($feed);
if(isset($feed->feedId))
{
  return $feed->feedId;
}

else 
	return false;

		   
		   
		   
}

 // get feed status
	   
	    public function getFeedStatus($store_id,$feedid)
       {
		    $params_new=array();			
			$config=$this->getStoreSetting($store_id);	
			if($config)
			{	  
				$WM_CLIENT_ID=$config['clientId'];		
				$WM_CLIENT_SECRET=$config['clientSecret'];
				$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
				$m=new FeedRequest($params,null, $config['env']);		
				return $response=$m->find($feedid,$params_new);
			
			}
	   }
	   
	    public function bulkUpdateInventory($store_id,$skus)
       {
			$config=$this->getStoreSetting($store_id);			  
			$WM_CLIENT_ID=$config['clientId'];		
			$WM_CLIENT_SECRET=$config['clientSecret'];
			$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new FeedRequest($params,null, $config['env']);
			$response =$m->bulkUpdateInventory($skus);
			$response = json_decode($response, true);
			
			if($response)
			{
				/*if(isset($response['feedId']))
				{
					$model=new WmFeedInventory;
					$model->ref_wm_store_id=$store_id;
					$model->feed_id=$response['feedId'];	
					$model->feed_type="inventory";		
					$model->save(false);
					
				}
				*/
			
			}
			
			return $response;
		   
	   }
	   
	   
	    // bulk price update
	    public function bulkUpdatePricing($store_id,$skus)
       {
			$config=$this->getStoreSetting($store_id);		  
			$WM_CLIENT_ID=$config['clientId'];		
			$WM_CLIENT_SECRET=$config['clientSecret'];
			$params=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			
			try{
				$m=new FeedRequest($params,null, $config['env']);
				$response =$m->bulkUpdatePricing($skus);
			}
			catch(Exception $e) 
			{
 				
			}
			
			$response = json_decode($response, true);
			
			
			
			if($response)
			{
				/*if(isset($response['feedId']))
				{
					$model=new WmFeedInventory;
					$model->ref_wm_store_id=$store_id;
					$model->feed_id=$response['feedId'];	
					$model->feed_type="price";		
					if($model->save(false)){
					
					
					}
				}
				*/
			
			}
			//die(var_dump($response));
			
			
			return $response;
		   
	   }
	   
	
	  

	   
	   // add new item setup by match
	   
	   // new 
 public function submitByMatch($config,$products)
{
		
	
  $auth =base64_encode($config['clientId'].':'.$config['clientSecret']);  
  $store_id = $config['store_id'];
  $weight = "0";
  $taxCode = '2038710';
    
  //Get Authentication Token
 $token=$this->getToken($config);

  //Send Feed Request
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'WM_SVC.NAME: Walmart Marketplace',
      'WM_QOS.CORRELATION_ID: '.$store_id,
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Basic '.$auth,
      'WM_SEC.ACCESS_TOKEN: '.$token
  ));
  $body = '{
       "MPItemFeedHeader": {
         "version": "3.2.1"
       },
       "MPItem": [
         
		';
	
	foreach($products as $product)
	{	
		
	$body.=' {
           "sku": "'.$product['sku'].'",
           "productIdentifiers": [
             {
               "productIdType": "'.$product['productIdType'].'",
               "productId": "'.$product['productId'].'"
             }
           ],
           "MPProduct": {
             "category": "'.$product['category'].'",
             "subCategory": "'.$product['subCategory'].'"
           },
           "MPOffer": {
             "price": '.$product['price'].',
             "shippingWeight": {
               "measure": '.$product['weight'].',
               "unit": "lb"
             },
             "productTaxCode": '.$taxCode.'
           }
         }';	
		
		
	}
		
		
	$body.='	
		 
       ]
     }';
	 
	
	 
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_URL, 'https://marketplace.walmartapis.com/v3/feeds?feedType=item&setupType=byMatch');
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_POSTFIELDS,     $body );
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$result=curl_exec($ch);


  $feed = json_decode($result);
  curl_close($ch);
if(isset($feed->feedId))
  return $feed->feedId;
  else return false;
		   
		   
		   
	   }
	   
	   
	   
	   public function addNewProductByMatch($store_id,$products)
       {
			$config=$this->getStoreSetting($store_id);			
			if(count($products)>0)
			{
				$feedId =$this->submitByMatch($config,$products);			
				if($feedId)
				{
					$model=new WalmartFeed;						
					$model->ref_wm_store_id=$store_id;	
					$model->user = $config['user_id'];									
					$model->feed_id=$feedId;	
					$model->feed_type="Item";	
					$model->feed_status="pending";							
					$model->save();
				
				}
				
				return $feedId;
			
			}
			return null;
		   
	   } 
	     public function searchExistingProduct($store_id,$params=array())
       {
			$setting=$this->getStoreSetting($store_id);		  
			$WM_CLIENT_ID=$setting['clientId'];		
			$WM_CLIENT_SECRET=$setting['clientSecret'];						
			$config=array('clientId'=>$WM_CLIENT_ID,'clientSecret'=>$WM_CLIENT_SECRET);
			$m=new SearchRequest($config,null, $setting['env']);
			$response =$m->items($params);
			$response = json_decode($response, true);	
			//dd($response);	
			if(isset($response['items'][0]))
			{
				return true;
						
			}
			else
			return false;
					
				
	   }
	   
	   

}

//  end of classWalmartHelper