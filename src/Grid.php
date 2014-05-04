<?php

namespace Flood;

class Grid
{

    /**
     * @var boolean Indicate whether this grid was returned from an API result.
     */
    protected $response = false;

    protected $status;

    /**
     * @var int Stop after n minutes. 0 minutes to run forever.
     */
    protected $stop_after;

    /**
     * @var string On demand "demand" or host your own "hosted".
     */
    protected $infrastructure;

    /**
     * @var string Type of AWS instance to launch.
     */
    protected $instance_type;

    /**
     * @var int Number of grid nodes to launch.
     */
    protected $instance_quantity;

    /**
     * @var string Target region to start the grid in.
     */
    protected $region;

    /**
     * @var string Name of the current grid.
     */
    protected $name;

    /**
     * @var string Unique identifier of the current grid.
     */
    protected $uuid;

    /**
     * @var Array Optional tags e.g. key1=value, key2=value.
     */
    protected $aws_tags;

    /**
     * @var float Optional spot price for instances in decimal cents e.g. 0.48.
     */
    protected $aws_spot_price;

    /**
     * @param bool $response
     */
    public function __construct($response = false)
    {
        $this->response = $response;
    }

    /**
     * Create a Grid object from JSON object.
     *
     * @param $json
     *   A JSON object returned from an API call.
     */
    public static function fromJson($json)
    {
        $grid = new \Flood\Grid(true);

        // Set flood properties
        $grid->status            = $json['status'];
        $grid->stop_after        = $json['stop_after'];
        $grid->infrastructure    = $json['infrastructure'];
        $grid->instance_type     = $json['instance_type'];
        $grid->instance_quantity = $json['instance_quantity'];
        $grid->region            = $json['region'];
        $grid->name              = $json['name'];
        $grid->uuid              = $json['uuid'];

        return $grid;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Only update the object if the value has not been set.
     * This effectively treats an object as read-only.
     */
    public function __set($name, $value)
    {
        if ($this->response == false) {
            $this->$name = $value;
        }
    }

}
