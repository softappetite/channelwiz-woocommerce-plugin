<?php
/**
* This class is autogenerated by the Spapi class generator
* Date of generation: 2022-05-26
* Specification: https://github.com/amzn/selling-partner-api-models/blob/main/models/notifications-api-model/notifications.json
* Source MD5 signature: c69dafb82442fc89d154797d88e26b97
*
*
* Selling Partner API for Notifications
* The Selling Partner API for Notifications lets you subscribe to notifications that are relevant to a selling partner's business. Using this API you can create a destination to receive notifications, subscribe to notifications, delete notification subscriptions, and more.

For more information, see the [Notifications Use Case Guide](doc:notifications-api-v1-use-case-guide).
*/
namespace DoubleBreak\Spapi\Api;
use DoubleBreak\Spapi\Client;

class Notifications extends Client {

  /**
  * Operation getSubscription
  *
  * @param string $notificationType The type of notification.
  *
  * For more information about notification types, see [the Notifications API Use Case Guide](doc:notifications-api-v1-use-case-guide).
  *
  */
  public function getSubscription($notificationType)
  {
    return $this->send("/notifications/v1/subscriptions/{$notificationType}", [
      'method' => 'GET',
    ]);
  }

  public function getSubscriptionAsync($notificationType)
  {
    return $this->sendAsync("/notifications/v1/subscriptions/{$notificationType}", [
      'method' => 'GET',
    ]);
  }

  /**
  * Operation createSubscription
  *
  * @param string $notificationType The type of notification.
  *
  * For more information about notification types, see [the Notifications API Use Case Guide](doc:notifications-api-v1-use-case-guide).
  *
  */
  public function createSubscription($notificationType, $body = [])
  {
    return $this->send("/notifications/v1/subscriptions/{$notificationType}", [
      'method' => 'POST',
      'json' => $body
    ]);
  }

  public function createSubscriptionAsync($notificationType, $body = [])
  {
    return $this->sendAsync("/notifications/v1/subscriptions/{$notificationType}", [
      'method' => 'POST',
      'json' => $body
    ]);
  }

  /**
  * Operation getSubscriptionById
  *
  * @param string $subscriptionId The identifier for the subscription that you want to get.
  * @param string $notificationType The type of notification.
  *
  * For more information about notification types, see [the Notifications API Use Case Guide](doc:notifications-api-v1-use-case-guide).
  *
  */
  public function getSubscriptionById($subscriptionId, $notificationType)
  {
    return $this->send("/notifications/v1/subscriptions/{$notificationType}/{$subscriptionId}", [
      'method' => 'GET',
    ]);
  }

  public function getSubscriptionByIdAsync($subscriptionId, $notificationType)
  {
    return $this->sendAsync("/notifications/v1/subscriptions/{$notificationType}/{$subscriptionId}", [
      'method' => 'GET',
    ]);
  }

  /**
  * Operation deleteSubscriptionById
  *
  * @param string $subscriptionId The identifier for the subscription that you want to delete.
  * @param string $notificationType The type of notification.
  *
  * For more information about notification types, see [the Notifications API Use Case Guide](doc:notifications-api-v1-use-case-guide).
  *
  */
  public function deleteSubscriptionById($subscriptionId, $notificationType)
  {
    return $this->send("/notifications/v1/subscriptions/{$notificationType}/{$subscriptionId}", [
      'method' => 'DELETE',
    ]);
  }

  public function deleteSubscriptionByIdAsync($subscriptionId, $notificationType)
  {
    return $this->sendAsync("/notifications/v1/subscriptions/{$notificationType}/{$subscriptionId}", [
      'method' => 'DELETE',
    ]);
  }

  /**
  * Operation getDestinations
  *
  */
  public function getDestinations()
  {
    return $this->send("/notifications/v1/destinations", [
      'method' => 'GET',
    ]);
  }

  public function getDestinationsAsync()
  {
    return $this->sendAsync("/notifications/v1/destinations", [
      'method' => 'GET',
    ]);
  }

  /**
  * Operation createDestination
  *
  */
  public function createDestination($body = [])
  {
    return $this->send("/notifications/v1/destinations", [
      'method' => 'POST',
      'json' => $body
    ]);
  }

  public function createDestinationAsync($body = [])
  {
    return $this->sendAsync("/notifications/v1/destinations", [
      'method' => 'POST',
      'json' => $body
    ]);
  }

  /**
  * Operation getDestination
  *
  * @param string $destinationId The identifier generated when you created the destination.
  *
  */
  public function getDestination($destinationId)
  {
    return $this->send("/notifications/v1/destinations/{$destinationId}", [
      'method' => 'GET',
    ]);
  }

  public function getDestinationAsync($destinationId)
  {
    return $this->sendAsync("/notifications/v1/destinations/{$destinationId}", [
      'method' => 'GET',
    ]);
  }

  /**
  * Operation deleteDestination
  *
  * @param string $destinationId The identifier for the destination that you want to delete.
  *
  */
  public function deleteDestination($destinationId)
  {
    return $this->send("/notifications/v1/destinations/{$destinationId}", [
      'method' => 'DELETE',
    ]);
  }

  public function deleteDestinationAsync($destinationId)
  {
    return $this->sendAsync("/notifications/v1/destinations/{$destinationId}", [
      'method' => 'DELETE',
    ]);
  }
}
