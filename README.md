#Dxyz
�ھɰ汾 Discuz X ��ʵ�ֲ����°汾���ԣ�ʹΪ���°汾������Ӧ�ü��ݾɰ汾ƽ̨��  
֧�ְ汾: Discuz X1.5 | Discuz X2 | Discuz X2.5 | Discuz X3 (������)

##��ʵ�ֵ�����
###Discuz X2.5 +
 * �� DB ������������֧��
 * �� SQL ��� format ��֧��
 * �����ݲ��֧��
 * ������ʹ�÷�������� [Discuz! �����Ŀ�](http://dev.discuz.org/wiki/index.php?title=X2.5%E7%9A%84%E6%96%B0%E7%A8%8B%E5%BA%8F%E6%9E%B6%E6%9E%84#.E6.95.B0.E6.8D.AE.E5.BA.93DB.E5.B1.82)��
 * ȡ���Դ�������ķ�б�ߴ��������е���`dxyz_input();`��
 * �� C ��Ĳ���֧�֣�`C::app | C::t`��

###Discuz X2 +
 * ��д�����װ�ļ��������԰�ʱ��ֱ��ʹ�� $installlang['english'] ��X1.5 ��ʽ�� $installlang['plugin_iden']['english']��

##�ļ���Դ
����ļ���ȡ�� Discuz X3/20130620, ��������Ҫ�޸�.

##ʹ�÷���
 * ����ļ���ͨ�� DiscuzX �Դ���ڣ��� *plugin.php | admin.php*�����ã���������´������:
    ```php
    <?php
    require_once DISCUZ_ROOT . '/dxyz/init.php';

    //Type your code here.
    ```
 * ����ļ��Ƕ�����ڣ���������´������:
    ```php
    <?php
    require './source/class/class_core.php';
    require './source/function/function_forum.php';

    require './dxyz/init.php';

    $discuz = C::app();
    $discuz->init();

    //Type your code here.
    ```
 * �������Ҫȡ�� X1.5/X2 �� *$_GET | $_POST | $_COOKIE* �ķ�б�ߴ�������***���� /dxyz/init.php ��***���� `dxyz_input();`  
   ����Ƕ�������ļ���������� `$discuz->init();` ֮��ʹ�øú���  
   �벻Ҫ��Ϊ X2.5 ���°汾��д����չ��ʹ�ñ���������Ϊ��Щ��չ�ǰ��� *$_GET | $_POST | $_COOKIE* �Ѿ�������б�ߴ���������д�ģ�����Щ��չ�е��ñ��������ܻ����� SQL ����©����

##ע������
 * ��д���ݲ��ļ�ʱ����ʹ�� Dxyz_DB ����� DB �ࡣ
 * ��д�����װ�ļ��������԰�ʱ����ֱ��ʹ�� $installlang['english'] ��X1.5 Ĭ�ϸ�ʽ�� $installlang['plugin_iden']['english']��
 * ����ܲ���֤����֧�����°汾���������ԡ�����ʱ�������н��в��ԡ�
 * ����ܽ��Դ�����Ч�����Է���ļ�������д������ʱ�������п��ǰ汾������졣
 * ������Ѱ��� X2.5 ����������ϵͳ���ݲ��ļ�����������ݱ��ֶο��ܲ������������ڰ汾�С�ʹ��������ݿ���ʱ�����в��ԡ�

##�汾��¼
###Version - 0.2 Beta:
 * �������ļ�����Ϊ X3 �����ļ�
 
###Version - 0.1 Beta:
 * ʵ�� X1.5/X2 �����ݲ��֧��
 * ���� X1.5 �����װ���������԰���ȡ��ʽ
 * ���� X1.5/X2 �� Core ��Ĳ���֧��
 * ���� X1.5/X2 ��ȡ����б�ߵĺ���
