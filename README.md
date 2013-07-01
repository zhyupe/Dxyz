#Dxyz
使用相同的代码开发 DiscuzX 1.5 ~ 3 扩展

##文件来源
相关文件提取自 Discuz X3/20130620, 并做过必要修改.

##使用方法
 * 如果文件是通过 DiscuzX 自带入口（如 *plugin.php | admin.php*）调用，请参照以下代码调用:

        <?php
        require_once DISCUZ_ROOT . '/dxyz/init.php';

        //Type your code here.

 * 如果文件是独立入口，请参照以下代码调用:

        <?php
        require './source/class/class_core.php';
        require './source/function/function_forum.php';

        require './dxyz/init.php';

        $discuz = C::app();
        $discuz->init();

        //Type your code here.

 * 如果您需要取消 X1.5/X2 对 *$_GET | $_POST | $_COOKIE* 的反斜线处理，请在***您自己的代码前***加入`dxyz_input();`  
   请不要在为 X2.5 以下版本编写的扩展中使用本函数，因为这些扩展是按照 *$_GET | $_POST | $_COOKIE* 已经经过反斜线处理的情况编写的，在这些扩展中调用本函数可能会留下 SQL 攻击漏洞。

##注意事项
 * 编写数据层文件时，请使用 Dxyz_DB 类代替 DB 类。
 * 编写插件安装文件调用语言包时，请直接使用 $installlang['english'] （X1.5 默认格式是 $installlang['plugin_iden']['english']）
 * 本框架不保证可以支持最新版本的所有特性。开发时请您自行进行测试。
 * 本框架仅对代码有效，不对风格文件进行重写。开发时请您自行考虑版本间风格差异。
 * 本框架已包含 X2.5 中所附带的系统数据层文件，但相关数据表及字段可能并不包含在早期版本中。使用相关内容开发时请自行测试。

##版本记录
###Version - 0.2 Beta:
 * 将部分文件更换为 X3 内置文件
###Version - 0.1 Beta:
 * 实现 X1.5/X2 对数据层的支持
 * 更改 X1.5 插件安装过程中语言包获取格式
 * 增加 X1.5/X2 对 Core 类的部分支持
 * 增加 X1.5/X2 中取消反斜线的函数