![avatar](http://p06ero5ye.bkt.clouddn.com/LH8VYD%25_%5BGW6G42SG%5D3A@XK.png)


## 介绍

前台功能:

- 登录、注册
- 个人信息管理
- 微博展示、评论
- 私信、消息提醒


后台功能:

- 用户管理
- 管理员管理、微博、评论管理
- 系统设置关键字过滤
- 前台注册开关



## 安装教程

环境要求:

- PHP >= 5.5.9

## 步骤

步骤一  
先导入根目录下的weibo.sql数据库文件

步骤二  
配置数据库，打开 \App\Common\Conf\config.php 文件，修改下面的内容：

```
 'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'weibo',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
```




步骤1  
设置 Uploads 目录和 App/Runtime  权限为 777

```
chmod -R  777 Uploads
chmod -R  777 App/Runtime 
````

步骤2  
配置伪静态并设置 meedu 的运行目录为 public 。

伪静态规则（Nginx）：

```
location / {
if (!-e $request_filename) {
   rewrite  ^(.*)$  /index.php?s=$1  last;
   break;
}
}
```


步骤3  
到这里，网站可以正常访问了。

后台登录地址：http://youdomain.com/Admin/Index/index  
超级管理员账号: admin  密码: woshiadmin
