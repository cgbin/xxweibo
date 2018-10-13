<?php
return array(
    'TMPL_PARSE_STRING'=>array(
        '__ADMIN__'=>__ROOT__.'/App/Admin/View/Public',
    ),

    //'配置项'=>'配置值'
    //配置数据库
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'weibo',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'hd_',    // 数据库表前缀
    'URL_MODEL'=>2,


    'URL_ROUTER_ON'=>true,
    'URL_ROUTE_RULES'=>array(
        ':id\d'=>'Index/User/index',
        'follow/:uid\d' => array('Index/User/followList', 'type=1'),
        'fans/:uid\d' => array('Index/User/followList', 'type=0'),
    ),
	//'配置项'=>'配置值'
   'MULTI_MODULE'=>true,
//    'DEFAULT_THEME'         =>  'default',
    'DEFAULT_MODULE'        =>  'Index',  // 默认模块
//    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
//    'DEFAULT_ACTION'        =>  'index', // 默认操作名称

);