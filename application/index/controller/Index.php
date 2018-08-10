<?php
namespace app\index\controller;

use app\index\worker\Beginner;
use app\index\worker\Freshman;
use Elasticsearch\ClientBuilder;

class Index
{
    public function index()
    {
        $beginner = new Beginner();
        $freshman = new Freshman();

        //$freshman->getMappings();
        $freshman->createIndex();
    }
}
