<?php 

namespace OrderDesk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

abstract class BaseApiClient
{
    protected $httpClient;
    protected $config;

    public function __construct(string $configFile)
    {
        $this->httpClient = new Client();
        $this->config = require $configFile;
    }

    public function getNumberOfOrders(): int
    {
        try {
            $response = $this->httpClient->request('GET', $this->config['orderListEndpoint']);
            
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                if (isset($data['status']) && $data['status'] === 'success' && isset($data['items']) && is_array($data['items'])) {
                    return count($data['items']);
                }
            }
        } catch (RequestException $e) {
            // Log or handle the exception appropriately
            throw new \Exception('Error retrieving number of orders.', 0, $e);
        }

        return 0;
    }

    abstract public function getShipments(int $orderId): array;
}
?>