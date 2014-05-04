<?php

namespace Flood;

class Client
{

    protected $client;

    const API_ENDPOINT = 'https://api.flood.io';

    public function __construct($apikey)
    {
        $this->client = new \GuzzleHttp\Client([
            'base_url' => self::API_ENDPOINT,
            'defaults' => [
                'headers' => ['Accept' => 'application/json'],
                'auth' => [$apikey, ''],
            ]
        ]);
    }

    public function floodList($page = 1)
    {
        $response = $this->sendRequest('/floods', ['page' => $page]);

        return $response['response'];
    }

    public function floodReport($flood_id)
    {
        $response = $this->sendRequest(['/floods/{flood_id}/report',
            [
                'flood_id' => $flood_id
            ]
        ]);

        return $response['response'];
    }

    public function floodResult($flood_id, $format = 'json')
    {
        return $this->sendRequest(['/floods/{flood_id}/result.{format}',
            [
                'flood_id' => $flood_id,
                'format' => $format
            ]
        ]);
    }

    public function floodRepeat($flood_id, $region)
    {
        $response = $this->sendRequest(['/floods/{flood_id}/repeat',
            [
                'flood_id' => $flood_id
            ]
        ], ['region' => $region]);

        return $response['response'];
    }

    public function floodStop($flood_id, $region = NULL)
    {
        $params = array();
        if ($region != NULL) {
            $params['region'] = $region;
        }
        $response = $this->sendRequest(['/floods/{flood_id}/stop',
            [
                'flood_id' => $flood_id
            ]
        ], $params);

        return $response['response'];
    }

    public function gridList($page = 1)
    {
        $response = $this->sendRequest('/grids', ['page' => $page]);

        return $response['response'];
    }

    public function gridDetail($grid_id)
    {
        return $this->sendRequest(['/grids/{flood_id}',
            [
                'grid_id' => $grid_id
            ]
        ]);
    }

    protected function sendRequest($url, $params = array(), $method = 'GET')
    {
        try {
            switch ($method) {
                case 'GET':
                    $response = $this->client->get($url, [
                        'query' => $params
                    ]);
                    break;
                case 'POST':
                    $response = $this->client->post($url, [
                        'body' => $params
                    ]);
                    break;
                case 'DELETE':
                    $response = $this->client->delete($url);
                    break;
            }

            if (strpos($response->getHeader('Content-Type'), 'application/json') === 0) {
                return $json = $response->json();
            } else {
                return $response->getBody();
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() == 400) {
                $error = $e->getResponse()->json();
                throw new \Exception($error['error_description']);
            }
        }
    }

}
