# after
layIM+workerman+thinkphp5的客服系统 v1.0  

实现了功能:  
1、通过after后台实现对聊天成员的增删改查，动态推送给在线的用户    
2、实现了离线客服登录后聊天记录推送   
3、实现了表情和图片的发送  
4、实现了单聊聊天记录和群聊聊天记录的查看  
5、实现了在线状态显示  

# 注意事项:  
back文件加下有数据库备份文件，请建立数据库，并导入。同时配置项目中的config文件中的datebase.php的数据库信息。  
别忘了vendor/Workerman/Applications/Config/Db.php，workerman的数据库同步跟上。

# 关于LayIM
因为layIM不开源，要是商用的话，建议去http://layim.layui.com  这里，layUI的官网去授权吧  

# 数据库在哪里？  
back 文件夹下有一个 after.sql 导入即可  

# 如何运行  
1、将代码下载到本地，并配置好虚拟域名，使 after 可以运行。（基于tp5框架，只要按照tp5框架的配置方式即可）  
  
2、导入 back 文件夹下的 after.sql 表，数据库名 为 after 
  
3、启动 getwayworker，本案例 基于的win平台的gatewayworker，如果您想在linux下部署，请先阅读 gatewayworker 文档有了基本的理解，然后下载 linux 版本的
gatewayworker，然后移植本程序的业务逻辑部分即可。如果您是win，请双击/vendor/Workerman/start_for_win.bat,然后不要关闭窗口。   
  
4、访问聊天系统，进入前台，注册新用户登录即可聊天。 请用两个浏览器打开，登录不同的账户互相聊天。后台默认账号是admin，密码admin  

5、在win下启动方式，双击 after/vendor/Workerman/start_for_win.bat 启动 workerman，不要关闭！停止按 Ctrl+c

6、在linux下启动方式，php /after/vendor/workerman/start.php start (启动，可以输出错误信息，但是关闭远程连接后会自动停止)；php /after/vendor/workerman/start.php start -d (守护进程模式启动，关闭Xshell连接不会停止)；php /after/vendor/workerman/start.php stop (停止服务)

# 了解效果
http://chat.itsideline.com


