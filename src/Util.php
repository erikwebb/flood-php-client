<?php

namespace Flood;

class Util
{

    public static function supportedTools()
    {
        return [
            'gatling-1.5.5',
            'jmeter-2.11',
        ];
    }

    public static function supportedRegions()
    {
        return [
            'ap-northeast-1',
            'ap-southeast-1',
            'ap-southeast-2',
            'eu-west-1',
            'sa-east-1',
            'us-east-1',
            'us-west-1',
            'us-west-2',
        ];
    }

}
