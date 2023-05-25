<?php

namespace OrderDesk\Shopify;

use OrderDesk\BaseApiClient;
use GuzzleHttp\Exception\RequestException;

class ShopifyApiClient extends BaseApiClient
{

  public function __construct()
  {
    $configFile = 'config.php';
    parent::__construct($configFile);
  }

  public function getShipments(int $orderId): array
  {
    try {
      $response = $this->httpClient->request('GET', $this->config['orderListEndpoint']);

      if ($response->getStatusCode() === 200) {
        $shipments = json_decode($response->getBody(), true);

        if (is_array($shipments)) {
          $shipmentInfo = [];

          foreach ($shipments as $shipment) {
            $shipmentInfo[] = $shipment['tracking_number'];
          }

          return $shipmentInfo;
        }
      }
    } catch (RequestException $e) {
      // Log or handle the exception appropriately
      throw new \Exception('Error retrieving shipments.', 0, $e);
    }

    return [];
  }
}
