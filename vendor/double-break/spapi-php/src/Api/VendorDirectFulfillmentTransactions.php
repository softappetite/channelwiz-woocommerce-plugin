<?php
/**
* This class is autogenerated by the Spapi class generator
* Date of generation: 2022-05-26
* Specification: https://github.com/amzn/selling-partner-api-models/blob/main/models/vendor-direct-fulfillment-transactions-api-model/vendorDirectFulfillmentTransactions_2021-12-28.json
* Source MD5 signature: 524c60fa23d3e605132bf963cbc03f29
*
*
* Selling Partner API for Direct Fulfillment Transaction Status
* The Selling Partner API for Direct Fulfillment Transaction Status provides programmatic access to a direct fulfillment vendor's transaction status.
*/
namespace DoubleBreak\Spapi\Api;
use DoubleBreak\Spapi\Client;

class VendorDirectFulfillmentTransactions extends Client {

  /**
  * Operation getTransactionStatus
  *
  * @param string $transactionId Previously returned in the response to the POST request of a specific transaction.
  *
  */
  public function getTransactionStatus($transactionId)
  {
    return $this->send("/vendor/directFulfillment/transactions/2021-12-28/transactions/{$transactionId}", [
      'method' => 'GET',
    ]);
  }

  public function getTransactionStatusAsync($transactionId)
  {
    return $this->sendAsync("/vendor/directFulfillment/transactions/2021-12-28/transactions/{$transactionId}", [
      'method' => 'GET',
    ]);
  }

  /**
  * Operation 
  *
  */
  public function ()
  {
    return $this->send("/vendor/directFulfillment/transactions/2021-12-28/transactions/{transactionId}", [
      'method' => 'X-AMZN-API-SANDBOX',
    ]);
  }

  public function Async()
  {
    return $this->sendAsync("/vendor/directFulfillment/transactions/2021-12-28/transactions/{transactionId}", [
      'method' => 'X-AMZN-API-SANDBOX',
    ]);
  }
}
