<?php
$yhsdk_init = true; //是否初始化变量(建议开启)
$bot_token = '*****'; //机器人的Token
require __DIR__ . '/yh.sdk.php';

if ($event_type == 'message.receive.normal') {
    if ($content == '文本发送测试') {
        send($back, 'text', '测试成功');
    }
    if ($content == '图片发送测试') {
        send($back, 'image', '图片url');
    }
    if ($content == 'Markdown发送测试') {
        send($back, 'markdown', '# 测试成功');
    }
    if ($content == '文件发送测试') {
        $file = array('name' => '文件名称', 'url' => '文件下载地址');
        send($back, 'file', $file);
    }
    if ($content == '批量发送测试') {
        $object = array('type' => 'user', 'ids' => array('1000000','1000001','1000003'));
        send($object, 'text', '测试成功', true);
    }
    
    if ($content == '消息编辑测试') {
        $msg_id = '*****';  //编辑消息ID
        edit($msg_id, $back, 'text', '消息编辑成功');
    }
    
    if ($content == '全局看板测试') {
        set_board('text', '全局看板设置成功');
    }
    if ($content == '用户看板测试') {
        set_board('text', '用户看板设置成功', false, $back);
    }
    
    if ($content == '取消全局看板测试') {
        unset_board();
    }
    if ($content == '取消用户看板测试') {
        unset_board(false, $back); 
    }
    
    if ($content == 'log写入测试') {
        write_log('测试消息发送');
    }
}