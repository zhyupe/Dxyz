#Dxyz
在旧版本 Discuz X 中实现部分新版本特性，使为最新版本开发的应用兼容旧版本平台。  
支持版本: Discuz X1.5 | Discuz X2 | Discuz X2.5 | Discuz X3 (待测试)

##已实现的特性
###Discuz X2.5 +
 * 对 DB 类新增方法的支持
 * 对 SQL 语句 format 的支持
 * 对数据层的支持
 * （具体使用方法请参照 [Discuz! 技术文库](http://dev.discuz.org/wiki/index.php?title=X2.5%E7%9A%84%E6%96%B0%E7%A8%8B%E5%BA%8F%E6%9E%B6%E6%9E%84#.E6.95.B0.E6.8D.AE.E5.BA.93DB.E5.B1.82)）
 * 取消对传入参数的反斜线处理（需自行调用`dxyz_input();`）
 * 对 C 类的部分支持（`C::app | C::t`）

###Discuz X2 +
 * 编写插件安装文件调用语言包时，直接使用 $installlang['english'] （X1.5 格式是 $installlang['plugin_iden']['english']）

##文件来源
相关文件提取自 Discuz X3/20130620, 并做过必要修改.

##使用方法
 * 如果文件是通过 DiscuzX 自带入口（如 *plugin.php | admin.php*）调用，请参照以下代码调用:
    ```php
    <?php
    require_once DISCUZ_ROOT . '/dxyz/init.php';

    //Type your code here.
    ```
 * 如果文件是独立入口，请参照以下代码调用:
    ```php
    <?php
    require './source/class/class_core.php';
    require './source/function/function_forum.php';

    require './dxyz/init.php';

    $discuz = C::app();
    $discuz->init();

    //Type your code here.
    ```
 * 如果您需要取消 X1.5/X2 对 *$_GET | $_POST | $_COOKIE* 的反斜线处理，请在***调用 /dxyz/init.php 后***加入 `dxyz_input();`  
   如果是独立入口文件，请务必在 `$discuz->init();` 之后使用该函数  
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
