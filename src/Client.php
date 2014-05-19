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
                'auth'    => [$apikey, ''],
            ]
        ]);
    }

    public static function getClient($apikey) {
        static $client;

        if (!isset($client)) {
            $client = new Client($apikey);
        }

        return $client;
    }

    /**
     * Returns a paginated list of all flood tests.
     */
    public function floodList($page = 1)
    {
        $floods   = array();
        $response = $this->sendRequest('/floods', ['page' => $page]);

        foreach ($response['response'] as $flood) {
            $floods[] = Flood::fromJson($flood);
        }

        return $floods;
    }

    /**
     * Gets a flood test plain text report. This may be a long-running request.
     */
    public function floodReport($flood_id)
    {
        $response = $this->sendRequest(['/floods/{flood_id}/report',
            [
                'flood_id' => $flood_id
            ]
        ]);

        return $response['response'];
    }

    /**
     * Gets detailed flood test results in .json or .csv format. This may be a long-running request.
     */
    public function floodResult($flood_id, $format = 'json')
    {
        return $this->sendRequest(['/floods/{flood_id}/result.{format}',
            [
                'flood_id' => $flood_id,
                'format'   => $format
            ]
        ]);
    }

    /**
     * Creates a new flood test and returns its details. This may be a long-running request.
     */
    public function floodStart($params)
    {
        if (!isset($params['region'])) {
            throw new Exception('Region parameter is required.');
        } else if (!isset($params['flood_tool']) || !in_array($params['flood_tool'], Util::supportedTools())) {
            throw new Exception('Flood tool parameter is required or not a supported value.');
        }
    }

    /**
     * Repeats a previous flood test and returns its details. This may be a long-running request. If a grid region or
     * uuid is not specified then the flood test will be repeated on all available grids for your account.
     *
     * @param $flood_id
     * @param string $region
     *   Target region to launch the test in.
     * @param string $grid
     *   Use a specific grid UUID to launch the test in instead of a region.
     * @return Flood
     *   A Flood object.
     */
    public function floodRepeat($flood_id, $region = NULL, $grid = NULL)
    {
        $params = array();

        if (isset($region)) {
            $params['region'] = $region;
        } else if (isset($grid)) {
            $params['grid'] = $grid;
        }

        $response = $this->sendRequest(['/floods/{flood_id}/repeat',
            [
                'flood_id' => $flood_id
            ]
        ], $params);

        return Flood::fromJson($response['response']);
    }

    /**
     * Stops a flood test and returns its status. This may be a long-running request.
     */
    public function floodStop($flood_id, $region = null)
    {
        $params = array();
        if ($region != null) {
            $params['region'] = $region;
        }
        $response = $this->sendRequest(['/floods/{flood_id}/stop',
            [
                'flood_id' => $flood_id
            ]
        ], $params);

        return $response['response'];
    }

    /**
     * Returns a paginated list of all grids.
     */
    public function gridList($page = 1)
    {
        $grids = array();
        $response = $this->sendRequest('/grids', ['page' => $page]);

        foreach ($response['response'] as $grid) {
            $grids[] = Grid::fromJson($grid);
        }

        return $grids;
    }

    /**
     * Show details about a grid.
     */
    public function gridDetail($grid_id)
    {
        return $this->sendRequest(['/grids/{flood_id}',
            [
                'grid_id' => $grid_id
            ]
        ]);
    }

    /**
     * Creates a new grid. This may be a long-running request.
     */
    public function gridCreate() {

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
