<?php

namespace Flood\Tests;

use Flood;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    protected $client;

    public function setUp()
    {
        $this->client = Flood\Client::getClient(FLOOD_APIKEY);
    }

    /**
     * @covers \Flood\Client::floodList()
     */
    public function testFloodList()
    {
        return $this->client->floodList();
    }

    /**
     * @covers \Flood\Client::floodReport()
     * @depends testFloodList
     */
    public function testFloodReport($floods)
    {
        if (count($floods) > 0) {
            $flood = array_pop($floods);
            $this->client->floodReport($flood->uuid);
        }
    }

    /**
     * @covers \Flood\Client::floodResult()
     * @depends testFloodList
     */
    public function testFloodResult($floods)
    {
        if (count($floods) > 0) {
            $flood = array_pop($floods);
            foreach (array('csv', 'json') as $format) {
                $this->client->floodResult($flood->uuid, $format);
            }
        }
    }

    /**
     * @covers \Flood\Client::floodRepeat()
     * @depends testFloodList
     */
    public function testFloodRepeat($floods)
    {
        if (count($floods) > 0) {
            $flood = array_pop($floods);
            $this->client->floodRepeat($flood->uuid);
        }
    }

    /**
     * @covers \Flood\Client::gridList()
     */
    public function testGridList()
    {
        return $this->client->gridList();
    }

    /**
     * @covers \Flood\Client::gridDetail()
     * @depends testGridList
     */
    public function testGridDetail($grids)
    {
        if (count($grids) > 0) {
            $grid = array_pop($grids);
            $this->client->gridDetail($grid->uuid);
        }
    }

}
