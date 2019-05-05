<?php
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Db;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $data) {
       $message = json_decode($data, true);
       $message_type = $message['type'];
       switch($message_type) {
           case 'init':
               $db1 = Db::instance('db1');  //数据库链接
               // uid
               $uid = $message['id'];
               // 设置session
               $_SESSION = [
                   'username' => $message['username'],
                   'avatar'   => $message['avatar'],
                   'id'       => $uid
               ];

               // 将当前链接与uid绑定
               Gateway::bindUid($client_id, $uid);
               // 通知当前客户端初始化
               $init_message = array(
                   'message_type' => 'init',
                   'id'           => $uid,
               );
               Gateway::sendToClient($client_id, json_encode($init_message));

               // 更新上线状态
               $status_message = [
                   'message_type' => 'changeStatus',
                   'id'           => $uid,
                   'status'       => 'online',
               ];
               $_SESSION['online'] = 'online';
               $db1->query("UPDATE `after_chatuser` SET `status` = 'online' WHERE id=" . $uid);
               //发送上线操作
               Gateway::sendToAll(json_encode($status_message));


               //查询最近1周有无需要推送的离线信息
               $time = time() - 7 * 3600 * 24;
               $resMsg = $db1->select('id,fromid,fromname,fromavatar,timeline,content')->from('after_chatlog')
                   ->where("toid= {$uid} and timeline > {$time} and type = 'friend' and needsend = 1" )
                   ->query();
                //var_export($resMsg);
               if( !empty( $resMsg ) ){

                   foreach( $resMsg as $key=>$vo ){

                       $log_message = [
                           'message_type' => 'logMessage',
                           'data' => [
                               'username' => $vo['fromname'],
                               'avatar'   => $vo['fromavatar'],
                               'id'       => $vo['fromid'],
                               'type'     => 'friend',
                               'content'  => htmlspecialchars( $vo['content'] ),
                               'timestamp'=> $vo['timeline'] * 1000,
                           ]
                       ];

                       Gateway::sendToUid( $uid, json_encode($log_message) );

                       //设置推送状态为已经推送
                       $db1->query("UPDATE `after_chatlog` SET `needsend` = '0' WHERE id=" . $vo['id']);

                   }
               }

               return;
               break;
           case 'chatMessage':
               $db1 = Db::instance('db1');  //数据库链接
               // 聊天消息
               $type = $message['data']['to']['type'];
               $to_id = $message['data']['to']['id'];
               $uid = $_SESSION['id'];
 
               $chat_message = [
                    'message_type' => 'chatMessage',
                    'data' => [
                        'username' => $_SESSION['username'],
                        'avatar'   => $_SESSION['avatar'],
                        'id'       => $type === 'friend' ? $uid : $to_id,
                        'type'     => $type,
                        'content'  => htmlspecialchars($message['data']['mine']['content']),
                        'timestamp'=> time()*1000,
                    ]
               ];
               //聊天记录数组
               $param = [
                   'fromid' => $uid,
                   'toid' => $to_id,
                   'fromname' => $_SESSION['username'],
                   'fromavatar' => $_SESSION['avatar'],
                   'content' => htmlspecialchars($message['data']['mine']['content']),
                   'timeline' => time(),
                   'needsend' => 0
               ];

               switch ($type) {
                   // 私聊
                   case 'friend':
                       // 插入
                       $param['type'] = 'friend';
                       if( empty( Gateway::getClientIdByUid( $to_id ) ) ){
                           $param['needsend'] = 1;  //用户不在线,标记此消息推送
                       }
                       $db1->insert('after_chatlog')->cols( $param )->query();
                       return Gateway::sendToUid($to_id, json_encode($chat_message));
               }
               return;
               break;
           case 'changeStatus':
               $status_message = [
                   'message_type' => $message_type,
                   'id'           => $message['data']['id'],
                   'status'       => $message['data']['status'],
               ];

               $_SESSION['online'] = $message['data']['status'];

               // 更新在线状态
               $db1 = Db::instance('db1');  //数据库链接
               $db1->query("UPDATE `after_chatuser` SET `status` = '".$message['data']['status']."' WHERE id=" . $message['data']['id']);

               Gateway::sendToAll(json_encode($status_message));
               return;
               break;
           case 'ping':
               return;
               break;
           default:
               echo "unknown message $data" . PHP_EOL;
       }
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id) {
       $logout_message = [
           'message_type' => 'logout',
           'id'           => $_SESSION['id']
       ];
       //Gateway::sendToAll(json_encode($logout_message));
   }
}