<?php

namespace Flood;

class Flood
{

    /**
     * @var boolean Indicate whether this flood was returned from an API result.
     */
    protected $response = false;

    /**
     * @var string The current status of the flood.
     */
    protected $status;

    /**
     * @var Array Tags associated with the flood test.
     */
    protected $tag_list;

    /**
     * @var string Target URL.
     */
    protected $url;

    /**
     * @var int Number of seconds duration to pass in to test plan.
     */
    protected $duration;

    /**
     * @var int Number of seconds rampup to pass in to test plan.
     */
    protected $rampup;

    /**
     * @var int Number of threads to pass in to test plan.
     */
    protected $threads;

    protected $results;

    /**
     * @var string Unique identifier of the current flood.
     */
    protected $uuid;

    protected $apdex;

    protected $mean_response_time;

    protected $tool;

    /**
     * @var string Name of the current grid.
     */
    protected $name;

    protected $plan;

    /**
     * @var Array Target regions to launch the test in.
     */
    protected $regions;

    /**
     * @var string A specific grid UUID to launch the test in instead of a region.
     */
    protected $grid;

    /**
     * @var Array Test plans to use, as a multi-part upload.
     */
    protected $files;

    /**
     * @param bool $response
     */
    public function __construct($response = false)
    {
        $this->response = $response;
    }

    /**
     * Create a Flood object from JSON object.
     *
     * @param $json
     *   A JSON object returned from an API call.
     */
    public static function fromJson($json)
    {
        $flood = new \Flood\Flood(true);

        // Set flood properties
        $flood->uuid     = $json['uuid'];
        $flood->apdex    = $json['apdex'];
        $flood->tool     = $json['tool'];
        $flood->name     = $json['name'];
        $flood->notes    = $json['notes'];
        $flood->plan     = $json['plan'];
        $flood->regions  = $json['regions'];
        $flood->results  = $json['results'];
        $flood->threads  = $json['threads'];
        $flood->rampup   = $json['rampup'];
        $flood->duration = $json['duration'];
        $flood->url      = $json['url'];
        $flood->tag_list = $json['tag_list'];
        $flood->status   = $json['status'];

        return $flood;
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function getResult($type = null)
    {
        if (isset($this->results[$type])) {
            return $this->results[$type];
        }
    }

    public function getReport()
    {
        return $this->getResult('report');
    }

    public function getUrl()
    {
        return $this->getResult('link');
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
