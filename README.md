# after
Workerman+Layim+TP5简单客服系统  

实现了功能:  
1、可通过后台对聊天成员的增删改查   
2、实现了离线客服登录后聊天记录推送   
3、实现了表情和图片的发送  
4、实现了一对一聊天和聊天记录的查看  
5、实现了在线状态显示  

# 注意事项:  
back文件加下有数据库备份文件，请建立数据库，并导入。同时配置项目中的config文件中的datebase.php的数据库信息。  
需要修改Workerman数据库配置 vendor/Workerman/Applications/Config/Db.php

# 关于LayIM
因为layIM不开源，要是商用的话，建议去http://layim.layui.com  这里，layUI的官网去授权吧  
 

# 如何运行  
1、将代码下载到本地，并配置好虚拟域名（基于tp5框架，只要按照tp5框架的配置方式即可）  
  
2、导入 back 文件夹下的 after.sql 表，数据库名为 after 

3、在win下启动方式，双击 after/vendor/Workerman/start_for_win.bat 启动 workerman，不要关闭！停止按 Ctrl+c

4、在linux下启动方式，php /after/vendor/workerman/start.php start (启动，可以输出错误信息，但是关闭远程连接后会自动停止)；php /after/vendor/workerman/start.php start -d (守护进程模式启动，关闭Xshell连接不会停止)；php /after/vendor/workerman/start.php stop (停止服务)

5、访问聊天系统，进入前台，注册新用户登录即可聊天。 请用两个浏览器打开，登录不同的账户互相聊天。后台默认账号是admin，密码admin  

# 效果
![image](https://github.com/Jing-Bei/after/blob/master/images/01.png)
![image](https://github.com/Jing-Bei/after/blob/master/images/02.png)
![image](https://github.com/Jing-Bei/after/blob/master/images/03.png)
![image](https://github.com/Jing-Bei/after/blob/master/images/04.png)
![image](https://github.com/Jing-Bei/after/blob/master/images/05.png)
![image](https://github.com/Jing-Bei/after/blob/master/images/06.png)
![image](https://github.com/Jing-Bei/after/blob/master/images/07.png)
![image](https://github.com/Jing-Bei/after/blob/master/images/08.png)


# 预览地址
http://chat.itsideline.com


