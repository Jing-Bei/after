/*
Navicat MySQL Data Transfer

Source Server         : 阿里云服务器
Source Server Version : 50631
Source Host           : 39.105.187.27:3306
Source Database       : after

Target Server Type    : MYSQL
Target Server Version : 50631
File Encoding         : 65001

Date: 2019-05-03 15:49:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `after_chatlog`
-- ----------------------------
DROP TABLE IF EXISTS `after_chatlog`;
CREATE TABLE `after_chatlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) NOT NULL COMMENT '会话来源id',
  `fromname` varchar(155) NOT NULL DEFAULT '' COMMENT '消息来源用户名',
  `fromavatar` varchar(155) NOT NULL DEFAULT '' COMMENT '来源的用户头像',
  `toid` int(11) NOT NULL COMMENT '会话发送的id',
  `content` text NOT NULL COMMENT '发送的内容',
  `timeline` int(10) NOT NULL COMMENT '记录时间',
  `type` varchar(55) NOT NULL COMMENT '聊天类型',
  `needsend` tinyint(1) DEFAULT '0' COMMENT '0 不需要推送 1 需要推送',
  PRIMARY KEY (`id`),
  KEY `fromid` (`fromid`) USING BTREE,
  KEY `toid` (`toid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of after_chatlog
-- ----------------------------
INSERT INTO `after_chatlog` VALUES ('1', '2', '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', '13', '你好', '1555898475', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('2', '13', '前端大神', 'http://tp1.sinaimg.cn/1241679004/180/5743814375/0', '2', '你好呀', '1555898483', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('3', '13', '前端大神', 'http://tp1.sinaimg.cn/1241679004/180/5743814375/0', '2', 'face[哈哈] ', '1555898489', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('4', '2', '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', '3', '你好', '1556177120', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('5', '2', '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', '2', 'img[/uploads/20190426/f9bbb9d64f1e277ebb701a4e45adc19d.png]', '1556244584', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('6', '2', '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', '2', '黑寡妇黑寡妇', '1556270475', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('7', '2', '马云', 'http://tp4.sinaimg.cn/2145291155/180/5601307179/1', '2', 'juytuyt ', '1556270479', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('8', '3', '罗玉凤', 'http://tp1.sinaimg.cn/1241679004/180/5743814375/0', '2', 'nihao ', '1556419465', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('9', '13', '李白', '/uploads/20190503/66bf601384b684f6a3b95c8a9c4ffedf.jpg', '2', '你好', '1556868416', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('10', '2', '韩信', '/uploads/20190503/8c2dff2ebeeedcc9c696737577e766fe.jpg', '13', '你好啊', '1556868435', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('11', '13', '李白', '/uploads/20190503/66bf601384b684f6a3b95c8a9c4ffedf.jpg', '2', '我不好', '1556868448', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('12', '13', '李白', '/uploads/20190503/66bf601384b684f6a3b95c8a9c4ffedf.jpg', '2', 'img[/uploads/20190503/cdc6d7b35b2c88e3b699dcd6660a7652.jpg]', '1556868466', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('13', '13', '李白', '/uploads/20190503/66bf601384b684f6a3b95c8a9c4ffedf.jpg', '2', 'face[太开心] ', '1556868471', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('14', '13', '李白', '/uploads/20190503/66bf601384b684f6a3b95c8a9c4ffedf.jpg', '2', '还', '1556868620', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('15', '2', '韩信', '/uploads/20190503/8c2dff2ebeeedcc9c696737577e766fe.jpg', '13', '黑', '1556868628', 'friend', '0');
INSERT INTO `after_chatlog` VALUES ('16', '2', '韩信', '/uploads/20190503/8c2dff2ebeeedcc9c696737577e766fe.jpg', '1', '哈哈', '1556868890', 'friend', '1');
INSERT INTO `after_chatlog` VALUES ('17', '13', '李白', '/uploads/20190503/66bf601384b684f6a3b95c8a9c4ffedf.jpg', '2', '和规范化', '1556869009', 'friend', '0');

-- ----------------------------
-- Table structure for `after_chatuser`
-- ----------------------------
DROP TABLE IF EXISTS `after_chatuser`;
CREATE TABLE `after_chatuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(155) DEFAULT NULL,
  `pwd` varchar(155) DEFAULT NULL COMMENT '密码',
  `groupid` int(5) DEFAULT NULL COMMENT '所属的分组id',
  `status` varchar(55) DEFAULT NULL,
  `sign` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of after_chatuser
-- ----------------------------
INSERT INTO `after_chatuser` VALUES ('1', '宋江', 'e10adc3949ba59abbe56e057f20f883e', '1', 'outline', '弟弟们，替晁盖哥哥报仇', '/uploads/20190503/201e381d5e09b7756108668a7875c85d.jpg');
INSERT INTO `after_chatuser` VALUES ('2', '韩信', 'e10adc3949ba59abbe56e057f20f883e', '1', 'online', '我是韩信，我只要80万精兵', '/uploads/20190503/8c2dff2ebeeedcc9c696737577e766fe.jpg');
INSERT INTO `after_chatuser` VALUES ('3', '鲁班七号', 'e10adc3949ba59abbe56e057f20f883e', '1', 'online', '我是鲁班七号', '/uploads/20190503/9d7e1e52c8a81ae06ef41cd77bb7ebd9.jpg');
INSERT INTO `after_chatuser` VALUES ('13', '李白', 'e10adc3949ba59abbe56e057f20f883e', '1', 'online', '我是李白', '/uploads/20190503/66bf601384b684f6a3b95c8a9c4ffedf.jpg');

-- ----------------------------
-- Table structure for `after_node`
-- ----------------------------
DROP TABLE IF EXISTS `after_node`;
CREATE TABLE `after_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(155) NOT NULL DEFAULT '' COMMENT '节点名称',
  `module_name` varchar(155) NOT NULL DEFAULT '' COMMENT '模块名',
  `control_name` varchar(155) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) NOT NULL COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `typeid` int(11) NOT NULL COMMENT '父级节点id',
  `style` varchar(155) DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of after_node
-- ----------------------------
INSERT INTO `after_node` VALUES ('1', '后台管理', '#', '#', '#', '2', '0', 'fa fa-users');
INSERT INTO `after_node` VALUES ('2', '用户列表', 'admin', 'user', 'index', '2', '1', '');
INSERT INTO `after_node` VALUES ('3', '添加用户', 'admin', 'user', 'useradd', '1', '2', '');
INSERT INTO `after_node` VALUES ('4', '编辑用户', 'admin', 'user', 'useredit', '1', '2', '');
INSERT INTO `after_node` VALUES ('5', '删除用户', 'admin', 'user', 'userdel', '1', '2', '');
INSERT INTO `after_node` VALUES ('6', '角色列表', 'admin', 'role', 'index', '2', '1', '');
INSERT INTO `after_node` VALUES ('7', '添加角色', 'admin', 'role', 'roleadd', '1', '6', '');
INSERT INTO `after_node` VALUES ('8', '编辑角色', 'admin', 'role', 'roleedit', '1', '6', '');
INSERT INTO `after_node` VALUES ('9', '删除角色', 'admin', 'role', 'roledel', '1', '6', '');
INSERT INTO `after_node` VALUES ('10', '分配权限', 'admin', 'role', 'giveaccess', '1', '6', '');
INSERT INTO `after_node` VALUES ('23', '售后管理', '#', '#', '#', '2', '0', 'fa fa-paw');
INSERT INTO `after_node` VALUES ('24', '客服管理', 'admin', 'layuser', 'index', '2', '23', '');
INSERT INTO `after_node` VALUES ('26', '添加客服', 'admin', 'layuser', 'useradd', '1', '24', '');
INSERT INTO `after_node` VALUES ('27', '删除客服', 'admin', 'layuser', 'userdel', '1', '24', '');
INSERT INTO `after_node` VALUES ('28', '编辑客服', 'admin', 'layuser', 'useredit', '1', '24', '');

-- ----------------------------
-- Table structure for `after_role`
-- ----------------------------
DROP TABLE IF EXISTS `after_role`;
CREATE TABLE `after_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `rolename` varchar(155) NOT NULL COMMENT '角色名称',
  `rule` varchar(255) DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of after_role
-- ----------------------------
INSERT INTO `after_role` VALUES ('1', '超级管理员', '');
INSERT INTO `after_role` VALUES ('2', '系统维护员', '1,2,3,4,5,6,7,8,9,10');

-- ----------------------------
-- Table structure for `after_rule`
-- ----------------------------
DROP TABLE IF EXISTS `after_rule`;
CREATE TABLE `after_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rulename` varchar(155) NOT NULL COMMENT '规则标题',
  `baseurl` varchar(155) NOT NULL COMMENT '采集站点的地址',
  `listurl` varchar(155) NOT NULL COMMENT '列表页地址',
  `ismore` tinyint(1) NOT NULL COMMENT '是否批量采集 1 否 2是',
  `start` int(11) DEFAULT '0' COMMENT '列表页开始地址',
  `end` int(11) DEFAULT '0' COMMENT '列表页结束地址',
  `titlediv` varchar(155) NOT NULL COMMENT '标题父层地址',
  `title` varchar(155) NOT NULL COMMENT '文章标题内容规则',
  `titleurl` varchar(155) NOT NULL COMMENT '标题地址规则',
  `body` varchar(155) NOT NULL COMMENT '文章内容规则',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of after_rule
-- ----------------------------
INSERT INTO `after_rule` VALUES ('1', '脚本之家php文章采集', 'http://www.jb51.net', 'http://www.jb51.net/list/list_15_1.htm', '1', '0', '0', '.artlist dl dt a', 'text', 'href', '#content', '1471244221');
INSERT INTO `after_rule` VALUES ('2', 'thinkphp官网文章规则', 'http://www.thinkphp.cn', 'http://www.thinkphp.cn/code/system/p/1.html', '1', '0', '0', '.extend ul li .hd a', 'text', 'href', '.wrapper .detail-bd', '1471244221');
INSERT INTO `after_rule` VALUES ('3', '果壳网科学人采集规则', 'http://www.guokr.com', 'http://www.guokr.com/scientific/', '1', '0', '0', '#waterfall .article h3 a', 'text', 'href', '.document div:eq(0)', '1471247277');

-- ----------------------------
-- Table structure for `after_user`
-- ----------------------------
DROP TABLE IF EXISTS `after_user`;
CREATE TABLE `after_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '密码',
  `loginnum` int(11) DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) DEFAULT '0' COMMENT '最后登录时间',
  `real_name` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '真实姓名',
  `status` int(1) DEFAULT '0' COMMENT '状态',
  `typeid` int(11) DEFAULT '1' COMMENT '用户角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of after_user
-- ----------------------------
INSERT INTO `after_user` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '69', '127.0.0.1', '1556862102', 'admin', '1', '1');
