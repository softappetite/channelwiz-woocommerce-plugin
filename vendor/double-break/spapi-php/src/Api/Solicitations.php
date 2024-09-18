<?php
/**
* This class is autogenerated by the Spapi class generator
* Date of generation: 2022-05-26
* Specification: https://github.com/amzn/selling-partner-api-models/blob/main/models/solicitations-api-model/solicitations.json
* Source MD5 signature: fb9233c5a562a31cc043bbdc17792209
*
*
* Selling Partner API for Solicitations
* With the Solicitations API you can build applications that send non-critical solicitations to buyers. You can get a list of solicitation types that are available for an order that you specify, then call an operation that sends a solicitation to the buyer for that order. Buyers cannot respond to solicitations sent by this API, and these solicitations do not appear in the Messaging section of Seller Central or in the recipient's Message Center. The Solicitations API returns responses that are formed according to the <a href=https://tools.ietf.org/html/draft-kelly-json-hal-08>JSON Hypertext Application Language</a> (HAL) standard.
*/
namespace DoubleBreak\Spapi\Api;
use DoubleBreak\Spapi\Client;

class Solicitations extends Client {

  /**
  * Operation getSolicitationActionsForOrder
  *
  * @param string $amazonOrderId An Amazon order identifier. This specifies the order for which you want a list of available solicitation types.
  *
  * @param array $queryParams
  *    - *marketplaceIds* array - A marketplace identifier. This specifies the marketplace in which the order was placed. Only one marketplace can be specified.
  *
  */
  public function getSolicitationActionsForOrder($amazonOrderId, $queryParams = [])
  {
    return $this->send("/solicitations/v1/orders/{$amazonOrderId}", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  public function getSolicitationActionsForOrderAsync($amazonOrderId, $queryParams = [])
  {
    return $this->sendAsync("/solicitations/v1/orders/{$amazonOrderId}", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation createProductReviewAndSellerFeedbackSolicitation
  *
  * @param string $amazonOrderId An Amazon order identifier. This specifies the order for which a solicitation is sent.
  *
  * @param array $queryParams
  *    - *marketplaceIds* array - A marketplace identifier. This specifies the marketplace in which the order was placed. Only one marketplace can be specified.
  *
  */
  public function createProductReviewAndSellerFeedbackSolicitation($amazonOrderId, $queryParams = [])
  {
    return $this->send("/solicitations/v1/orders/{$amazonOrderId}/solicitations/productReviewAndSellerFeedback", [
      'method' => 'POST',
      'query' => $queryParams,
    ]);
  }

  public function createProductReviewAndSellerFeedbackSolicitationAsync($amazonOrderId, $queryParams = [])
  {
    return $this->sendAsync("/solicitations/v1/orders/{$amazonOrderId}/solicitations/productReviewAndSellerFeedback", [
      'method' => 'POST',
      'query' => $queryParams,
    ]);
  }
}
