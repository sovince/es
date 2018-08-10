<?php
/**
 * Created by PhpStorm.
 * Powered by: Vince
 * Email: so_vince@outlook.com
 * Date: 2018/8/10
 * Time: 17:42
 */

namespace app\index\worker;


use Elasticsearch\ClientBuilder;

class Freshman
{
    public $es;

    public function __construct()
    {
        $params = array(
            '127.0.0.1:9200'
        );
        $this->es = ClientBuilder::create()->setHosts($params)->build();
    }

    /**
     * 1.创建索引
     */
    public function createIndex(){
        $params = [
            'index' => 'test', //索引名称
            'body' => [
                'settings'=> [ //配置
                    'number_of_shards'=> 3,//主分片数
                    'number_of_replicas'=> 1 //主分片的副本数
                ],
                'mappings'=> [  //映射
                    '_default_' => [ //默认配置，每个类型缺省的配置使用默认配置
                        '_all'=>[   //  关闭所有字段的检索
                            'enabled' => 'false'
                        ],
                        '_source'=>[   //  存储原始文档
                            'enabled' => 'true'
                        ],
                        'properties'=> [ //配置数据结构与类型
                            'name'=> [ //字段1
                                'type'=>'string',//类型 string、integer、float、double、boolean、date
                                'index'=> 'analyzed',//索引是否精确值  analyzed not_analyzed
                            ],
                            'age'=> [ //字段2
                                'type'=>'integer',
                            ],
                            'sex'=> [ //字段3
                                'type'=>'string',
                                'index'=> 'not_analyzed',
                            ],
                        ]
                    ],
                    'my_type' => [
                        'properties' => [
                            'phone'=> [
                                'type'=>'string',
                            ],
                        ]
                    ],
                ],
            ]
        ];

        $res = $this->es->indices()->create($params);
        halt($res);
    }
    /**
     * 2.删除索引
     */
    public function deleteIndex(){
        $params = [
            'index' => 'test'
        ];

        $res = $this->es->indices()->delete($params);
        halt($res);
    }


    /**
     * 3.查看Mappings
     */
    public function getMappings(){
        $params = [
            'index' => 'test'
        ];

        $res = $this->es->indices()->getMapping();
        halt($res);
    }

    /**
     * 4.修改Mappings
     */
    public function putMappings(){
        $params = [
            'index' => 'test',
            'type' => 'my_type',
            'body' => [
                'my_type' => [
                    'properties' => [
                        'idcard' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ];

        $res = $this->es->indices()->putMapping($params);
        halt($res);
    }

    /**
     * 5.插入单条 Document
     */
    public function postSinDoc(){
        $params = [
            'index' => 'test',
            'type' => 'my_type',
            'body' => [
                'age' => 17,
                'name' => 'saki',
                'sex' => '女性',
                'idcard' => 1112,
                'phone' => '1245789',
            ]
        ];

        $res = $this->es->index($params);
        halt($res);
    }

    /**
     * 6.插入多条 Document
     */
    public function postBulkDoc(){
        for($i = 0; $i < 5; $i++) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'test',
                    '_type' => 'my_type',
                ]
            ];

            $params['body'][] = [
                'age' => 17+$i,
                'name' => 'reimu'.$i,
                'sex' => '女性',
                'idcard' => 1112+$i,
                'phone' => '1245789'.$i,
            ];
        }

        $res = $this->es->bulk($params);
        halt($res);
    }


    /**
     * 7.通过id获取Document
     */
    public function getDocById(){
        $params = [
            'index' => 'test',
            'type' => 'my_type',
            'id' => 'AWIDV5l2A907wJBVKu6k'
        ];

        $res = $this->es->get($params);
        halt($res);
    }

    /**
     * 8.通过id更新Document
     */
    public function updateDocById(){
        $params = [
            'index' => 'test',
            'type' => 'my_type',
            'id' => 'AWIDV5l2A907wJBVKu6k',
            'body' => [
                'doc' => [ //将doc中的文档与现有文档合并
                    'name' => 'marisa'
                ]
            ]
        ];

        $res = $this->es->update($params);
        halt($res);
    }

    /**
     * 9.通过id删除Document
     */
    public function deleteDocById(){
        $params = [
            'index' => 'test',
            'type' => 'my_type',
            'id' => 'AWIDV5l2A907wJBVKu6k'
        ];

        $res = $this->es->delete($params);
        halt($res);
    }

    /**
     * 10.搜索Document
     */
    public function searchDoc()
    {
        $params = [
            'index' => 'test',
            'type' => 'my_type',
            'body' => [
                'query' => [
                    'constant_score' => [ //非评分模式执行
                        'filter' => [ //过滤器，不会计算相关度，速度快
                            'term' => [ //精确查找，不支持多个条件
                                'name' => 'reimu0'
                            ]
                        ]

                    ]
                ]
            ]
        ];

        $res = $this->es->search($params);
        halt($res);
    }
}