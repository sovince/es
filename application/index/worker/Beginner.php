<?php
/**
 * Created by PhpStorm.
 * Powered by: Vince
 * Email: so_vince@outlook.com
 * Date: 2018/8/10
 * Time: 14:22
 */

namespace app\index\worker;


use Elasticsearch\ClientBuilder;

class Beginner
{
    public $client;

    public function __construct()
    {
        $params = array(
            '127.0.0.1:9200'
        );
        $this->client = ClientBuilder::create()->setHosts($params)->build();
    }
    public function createIndex(){
        $client = $this->client;

        $param = [
            'index'=>'my_index',
            'type'=>'my_type',
            'body'=>[
                'name'=>'Vince',
                'sex'=>'male'
            ]
        ];


        $result = $client->index($param);
        halt($result);
    }

    public function getMappings(){
        $client = $this->client;

        $param = [
            'index'=>'my_index',

        ];
        $result = $client->indices()->getMapping($param);
        halt($result);
    }
}