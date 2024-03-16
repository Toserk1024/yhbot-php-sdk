<?php
if ($yhsdk_init == true) {
    //数据变量初始化(可根据需求自行添加)
    //自行修改请参考云湖官网事件订阅文档
    $back = array();
    $post_data = json_decode(file_get_contents('php://input'), true);
    $event_type = $post_data['header']['eventType']; //事件类型
    $command = $post_data['event']['message']['commandName']; //命令名称
    $content = $post_data['event']['message']['content']['text']; //具体内容
    //设定原路返回的对象，以方便发送
    if ($post_data['event']['chat']['chatType'] == 'bot') {
        //如果为私聊则设定发送对象为发送者
        $back['id'] = $post_data['event']['sender']['senderId'];
        $back['type'] = $post_data['event']['sender']['senderType'];
    } else {
        //如果在群聊中则设定当前群聊为发送对象
        $back['id'] = $post_data['event']['chat']['chatId'];
        $back['type'] = $post_data['event']['chat']['chatType'];
    }
}

//消息发送函数(支持消息批量发送)
function send($object, $type, $content, $batch = false, $buttons = null) {
    $data = array();
    $data['content'] = array();
    if (!$batch) {
        $tool = 'send';
        $data['recvId'] = $object['id'];
    } else {
        $tool = 'batch_send';
        $data['recvIds'] = $object['ids'];
    }
    $data['recvType'] = $object['type'];
    $content_data = make_content($type, $content, $buttons);
    $data = array_merge($data, $content_data);
    $back_data = send_request($data, $tool);
    return $back_data;
}

//消息编辑函数
function edit($msg_id, $object, $type, $content, $buttons = null) {
    $data = array();
    $data['msgId'] = $msg_id;
    $data['recvId'] = $object['id'];
    $data['recvType'] = $object['type'];
    $content_data = make_content($type, $content, $buttons);
    $data = array_merge($data, $content_data);
    $back_data = send_request($data, $tool);
    return $back_data;
}

//发送数据处理函数
function make_content($type, $content, $buttons) {
    $data = array();
    $data['content'] = array();
    $data['contentType'] = $type;
    
    if (in_array($type, array('text', 'markdown', 'html'))) {
        $data['content']['text'] = $content;
    } elseif ($type == 'image') {
        $data['content']['imageUrl'] = $content;
    } elseif ($type == 'file') {
        $data['content']['fileName'] = $content['name'];
        $data['content']['fileUrl'] = $content['url'];
    } else {
        echo "无效的数据类型{$type}";
        exit;
    }
    if(!empty($buttons)) {
        $data['content']['buttons'] = $buttons;
    }
    return $data;
}

//看板设置函数
function set_board($type, $content, $is_all = true, $object = null) {
    $data = array();
    if ($is_all) {
        $tool = 'board-all';
    } else {
        $data['recvId'] = $object['id'];
        $data['recvType'] = $object['type'];
        $tool = 'board';
    }
    if (!in_array($type, array('text', 'markdown', 'html'))) {
        echo "无效的数据类型{$type}";
        exit;
    }
    $data['contentType'] = $type;
    $data['content'] = $content;
    $back_data = send_request($data, $tool);
    return $back_data;
}

//看板取消设置函数
function unset_board($is_all = true, $object = null) {
    $data = array();
    if ($is_all) {
        $tool = 'board-all-dismiss';
    } else {
        $tool = 'board-dismiss';
        $data['recvId'] = $object['id'];
        $data['recvType'] = $object['type'];
    }
    $back_data = send_request($data, $tool);
    return $back_data;
}

//API数据发送函数
function send_request($data, $tool) {
    global $bot_token;
    $send_data = json_encode($data, 320);
    $send_header = array('Content-Type: application/json; charset=utf-8');
    $send_url = "https://chat-go.jwzhd.com/open-apis/v1/bot/{$tool}?token=" . $bot_token;
    
    $send = curl_init();
    curl_setopt($send, CURLOPT_URL, $send_url);
    curl_setopt($send, CURLOPT_POST, 1);
    curl_setopt($send, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($send, CURLOPT_POSTFIELDS, $send_data);
    curl_setopt($send, CURLOPT_HTTPHEADER, $send_header);
    $data = curl_exec($send);
    curl_close($send);
    return $data;
}

//Log写入函数
function write_log($action) {
    global $back, $event_type;
    //将传入的事件进行对应
    $events = array(
        "message.receive.normal" => "普通消息",
        "message.receive.instruction" => "指令消息",
        "bot.followed" => "关注机器人",
        "bot.unfollowed" => "取消关注机器人",
        "group.join" => "加入群",
        "group.leave" => "退出群",
        "button.report.inline" => "按钮事件"
    );
    $event = $events[$event_type];
    $time = date('Y/m/d H:i:s');
    $content = "{$time} | {$event} | ID:{$back['id']} | 操作: {$action}\n";
    file_put_contents('log.txt', $content, FILE_APPEND);
}