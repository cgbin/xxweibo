<?php
return array(
    'MULTI_MODULE'=>true,

    'DEFAULT_THEME'         =>  'default',



    'DATA_CACHE_SUBDIR'=>true,
    'DATA_PATH_LEVEL'=>2,

    'TAGLIB_PRE_LOAD'=>'TagLib\Test',
    'TAGLIB_BUILD_IN'=>'cx,TagLib\Test',


    'URL_HTML_SUFFIX'       => '',
    //用于加密的KEY
    'ENCTYPTION_KEY'=>'www.xiaodangjia.com',
    'AUTO_LOGIN_TIME'=>time()+3600*24*7,

    //设置图片上传参数
    'UPLOAD_MAX_SIZE'=>200000,
    'UPLOAD_EXTS'=>array('jpg','jpeg','png','gif'),
    'UPLOAD_PATH'=>'./Uploads/',
    //水印图片地址
    'WATER_PATH'=>'./Uploads/checked.gif',
    //字体位置
    'TXEX_PATH'=>'./Uploads/1.ttf',

    'LOAD_EXT_CONFIG' => 'system',

);