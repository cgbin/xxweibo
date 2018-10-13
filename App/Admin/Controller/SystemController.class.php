<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class SystemController extends CommonController {
    /**
     * 网站设置
     */
    Public function index () {
        $config = include $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/App/Index/Conf/system.php';
        $this->assign('config',$config);
        $this->display();
    }

    /**
     * 修改网站设置
     */
    Public function runEdit () {
        $path = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/App/Index/Conf/system.php';
        $config = include $path;
        $config['WEBNAME'] = $_POST['webname'];
        $config['COPY'] = $_POST['copy'];
        $config['REGIS_ON'] = $_POST['regis_on'];

        $data = "<?php\r\nreturn " . var_export($config, true) . ";\r\n?>";

        if (file_put_contents($path, $data)) {
            $this->success('修改成功', U('index'));
        } else {
            $this->error('修改失败， 请修改' . $path . '的写入权限');
        }
    }

    /**
     * 关键设置视图
     */
    Public function filter () {
        $config = include $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/App/Index/Conf/system.php';
        $this->filter = implode('|', $config['FILTER']);
        $this->display();
    }

    /**
     * 执行修改关键词
     */
    Public function runEditFilter () {
        $path = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/App/Index/Conf/system.php';
        $config = include $path;
        $config['FILTER'] = explode('|', $_POST['filter']);
        //要写入将内容替换成字符串
        //var_export()作用为
        //$var =var_export(array('a','b',array('aa','bb','cc')),TRUE)，加上TRUE后，不会再打印出来，
        //而是给了一个变量，这样就可以直接输出;
        // echo $var;此时输出来的形式与var_dump()打印的相似。
        $data = "<?php\r\nreturn " . var_export($config, true) . ";\r\n?>";
        //写入文件
        if (file_put_contents($path, $data)) {
            $this->success('修改成功', U('filter'));
        } else {
            $this->error('修改失败， 请修改' . $path . '的写入权限');
        }
    }
}