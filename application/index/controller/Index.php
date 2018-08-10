<?php
namespace app\index\controller;

use Elasticsearch\ClientBuilder;

class Index
{
    public function index()
    {
        $params = array(
            '127.0.0.1:9200'
        );
        $client = ClientBuilder::create()->setHosts($params)->build();
        halt($client);

        echo 'ok';//
    }
}
