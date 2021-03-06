DROP TABLE IF EXISTS `np_access`;
CREATE TABLE IF NOT EXISTS `np_access` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '组ID',
  `node_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '节点ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '节点等级',
  `module` varchar(50) NOT NULL DEFAULT '' COMMENT '节点名',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `node_id` (`node_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '权限表';

DROP TABLE IF EXISTS `np_node`;
CREATE TABLE IF NOT EXISTS `np_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '节点操作名',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '节点说明',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `level` (`level`),
  KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT '节点表';
INSERT INTO `np_node` (`id`, `pid`, `level`, `name`, `title`, `status`, `sort`, `remark`) VALUES
(1, 0, 1, 'admin', '后台', 1, 0, '后台模块'),

(2, 1, 2, 'settings', '设置', 1, 0, '设置控制器'),
(3, 1, 2, 'theme', '界面', 1, 0, '界面控制器'),
(4, 1, 2, 'category', '栏目', 1, 0, '栏目控制器'),
(5, 1, 2, 'content', '内容', 1, 0, '内容控制器'),
(6, 1, 2, 'user', '用户', 1, 0, '用户控制器'),
(7, 1, 2, 'wechat', '微信', 1, 0, '微信控制器'),
(8, 1, 2, 'mall', '商城', 1, 0, '商城控制器'),
(9, 1, 2, 'book', '书库', 1, 0, '书库控制器'),
(10, 1, 2, 'expand', '扩展', 1, 0, '扩展控制器'),

(11, 2, 3, 'info', '系统信息', 1, 0, '系统信息方法'),

(12, 2, 3, 'basic', '基本设置', 1, 0, '基本设置方法'),
(13, 12, 4, 'editor', '基本设置编辑', 1, 0, '基本设置编辑操作'),

(14, 2, 3, 'lang', '语言设置', 1, 0, '语言设置方法'),
(15, 14, 4, 'editor', '语言设置编辑', 1, 0, '语言设置编辑操作'),

(16, 2, 3, 'image', '图片设置', 1, 0, '图片设置方法'),
(17, 16, 4, 'editor', '图片设置编辑', 1, 0, '图片设置编辑操作'),
(18, 16, 4, 'upload', '图片设置上传', 1, 0, '图片设置上传操作'),

(19, 2, 3, 'safe', '安全与效率设置', 1, 0, '安全与效率设置方法'),
(20, 19, 4, 'editor', '安全与效率设置编辑', 1, 0, '安全与效率设置编辑操作'),

(21, 2, 3, 'email', '邮件设置设置', 1, 0, '邮件设置方法'),
(22, 21, 4, 'editor', '邮件设置设置编辑', 1, 0, '邮件设置设置编辑操作'),

(23, 3, 3, 'cms', '网站界面设置', 1, 0, '网站界面设置方法'),
(24, 23, 4, 'editor', '网站界面设置编辑', 1, 0, '网站界面设置编辑操作'),

(25, 3, 3, 'member', '会员界面设置', 1, 0, '会员界面设置方法'),
(26, 25, 4, 'editor', '会员界面设置编辑', 1, 0, '会员界面设置编辑操作'),

(27, 3, 3, 'mall', '商城界面设置', 1, 0, '商城界面设置方法'),
(28, 27, 4, 'editor', '商城界面设置编辑', 1, 0, '商城界面设置编辑操作'),

(29, 4, 3, 'category', '管理栏目', 1, 0, '管理栏目方法'),
(30, 29, 4, 'added', '管理栏目添加', 1, 0, '管理栏目添加操作'),
(31, 29, 4, 'remove', '管理栏目删除', 1, 0, '管理栏目删除操作'),
(32, 29, 4, 'editor', '管理栏目编辑', 1, 0, '管理栏目编辑操作'),
(33, 29, 4, 'upload', '管理栏目上传', 1, 0, '管理栏目上传操作'),

(34, 4, 3, 'model', '管理模型', 1, 0, '管理模型方法'),

(35, 4, 3, 'fields', '自定义字段', 1, 0, '自定义字段'),
(36, 35, 4, 'added', '自定义字段添加', 1, 0, '自定义字段添加操作'),
(37, 35, 4, 'remove', '自定义字段删除', 1, 0, '自定义字段删除操作'),
(38, 35, 4, 'editor', '自定义字段编辑', 1, 0, '自定义字段编辑操作'),

(39, 4, 3, 'type', '管理类别', 1, 0, '管理类别方法'),
(40, 39, 4, 'added', '管理类别添加', 1, 0, '管理类别添加操作'),
(41, 39, 4, 'remove', '管理类别删除', 1, 0, '管理类别删除操作'),
(42, 39, 4, 'editor', '管理类别编辑', 1, 0, '管理类别编辑操作'),

(43, 5, 3, 'content', '管理内容', 1, 0, '管理内容方法'),
(44, 43, 4, 'added', '管理内容添加', 1, 0, '管理内容添加操作'),
(45, 43, 4, 'remove', '管理内容删除', 1, 0, '管理内容删除操作'),
(46, 43, 4, 'editor', '管理内容编辑', 1, 0, '管理内容编辑操作'),
(47, 43, 4, 'upload', '管理内容上传', 1, 0, '管理内容上传操作'),

(48, 5, 3, 'banner', '管理幻灯片', 1, 0, '管理幻灯片方法'),
(49, 48, 4, 'added', '管理幻灯片添加', 1, 0, '管理幻灯片添加操作'),
(50, 48, 4, 'remove', '管理幻灯片删除', 1, 0, '管理幻灯片删除操作'),
(51, 48, 4, 'editor', '管理幻灯片编辑', 1, 0, '管理幻灯片编辑操作'),
(52, 48, 4, 'upload', '管理幻灯片上传', 1, 0, '管理幻灯片上传操作'),

(53, 5, 3, 'ads', '管理广告', 1, 0, '管理广告方法'),
(54, 53, 4, 'added', '管理广告添加', 1, 0, '管理广告添加操作'),
(55, 53, 4, 'remove', '管理广告删除', 1, 0, '管理广告删除操作'),
(56, 53, 4, 'editor', '管理广告编辑', 1, 0, '管理广告编辑操作'),
(57, 53, 4, 'upload', '管理广告上传', 1, 0, '管理广告上传操作'),

(58, 5, 3, 'comment', '管理评论', 1, 0, '管理评论方法'),
(59, 58, 4, 'remove', '管理评论删除', 1, 0, '管理评论删除操作'),
(60, 58, 4, 'editor', '管理评论编辑', 1, 0, '管理评论编辑操作'),

(61, 5, 3, 'cache', '更新缓存或静态', 1, 0, '更新缓存或静态方法'),
(62, 61, 4, 'compile', '编译与HTML静态缓存文件', 1, 0, '编译与HTML静态缓存文件操作'),
(63, 61, 4, 'cache', '数据缓存的文件', 1, 0, '数据缓存的文件操作'),

(64, 5, 3, 'recycle', '内容回收站', 1, 0, '内容回收站方法'),
(65, 64, 4, 'remove', '内容回收站删除', 1, 0, '内容回收站删除操作'),
(66, 64, 4, 'recover', '内容回收站恢复', 1, 0, '内容回收站恢复操作'),

(67, 6, 3, 'member', '会员管理', 1, 0, '会员管理方法'),
(68, 67, 4, 'added', '会员管理添加', 1, 0, '会员管理添加操作'),
(69, 67, 4, 'remove', '会员管理删除', 1, 0, '会员管理删除操作'),
(70, 67, 4, 'editor', '会员管理编辑', 1, 0, '会员管理编辑操作'),
(71, 67, 4, 'upload', '会员管理上传', 1, 0, '会员管理上传操作'),

(72, 6, 3, 'level', '会员等级管理', 1, 0, '会员等级管理方法'),
(73, 72, 4, 'added', '会员等级管理添加', 1, 0, '会员等级管理添加操作'),
(74, 72, 4, 'remove', '会员等级管理删除', 1, 0, '会员等级管理删除操作'),
(75, 72, 4, 'editor', '会员等级管理编辑', 1, 0, '会员等级管理编辑操作'),

(76, 6, 3, 'admin', '管理员管理', 1, 0, '管理员管理方法'),
(77, 76, 4, 'added', '管理员管理添加', 1, 0, '管理员管理添加操作'),
(78, 76, 4, 'remove', '管理员管理删除', 1, 0, '管理员管理删除操作'),
(79, 76, 4, 'editor', '管理员管理编辑', 1, 0, '管理员管理编辑操作'),

(80, 6, 3, 'role', '管理员组管理', 1, 0, '管理员组管理方法'),
(81, 80, 4, 'added', '管理员组管理添加', 1, 0, '管理员组管理添加操作'),
(82, 80, 4, 'remove', '管理员组管理删除', 1, 0, '管理员组管理删除操作'),
(83, 80, 4, 'editor', '管理员组管理编辑', 1, 0, '管理员组管理编辑操作'),

(84, 6, 3, 'node', '系统节点管理', 1, 0, '系统节点管理方法'),
(85, 84, 4, 'added', '系统节点管理添加', 1, 0, '系统节点管理添加操作'),
(86, 84, 4, 'remove', '系统节点管理删除', 1, 0, '系统节点管理删除操作'),
(87, 84, 4, 'editor', '系统节点管理编辑', 1, 0, '系统节点管理编辑操作'),

(88, 7, 3, 'keyword', '关键词自动回复', 1, 0, '关键词自动回复方法'),
(89, 88, 4, 'added', '关键词自动回复添加', 1, 0, '关键词自动回复添加操作'),
(90, 88, 4, 'remove', '关键词自动回复删除', 1, 0, '关键词自动回复删除操作'),
(91, 88, 4, 'editor', '关键词自动回复编辑', 1, 0, '关键词自动回复编辑操作'),
(92, 88, 4, 'upload', '关键词自动回复上传', 1, 0, '关键词自动回复上传操作'),

(93, 7, 3, 'auto', '默认自动回复', 1, 0, '默认自动回复方法'),
(94, 93, 4, 'added', '默认自动回复添加', 1, 0, '默认自动回复添加操作'),
(95, 93, 4, 'remove', '默认自动回复删除', 1, 0, '默认自动回复删除操作'),
(96, 93, 4, 'editor', '默认自动回复编辑', 1, 0, '默认自动回复编辑操作'),
(97, 93, 4, 'upload', '默认自动回复上传', 1, 0, '默认自动回复上传操作'),

(98, 7, 3, 'attention', '关注自动回复', 1, 0, '关注自动回复方法'),
(99, 98, 4, 'added', '关注自动回复添加', 1, 0, '关注自动回复添加操作'),
(100, 98, 4, 'remove', '关注自动回复删除', 1, 0, '关注自动回复删除操作'),
(101, 98, 4, 'editor', '关注自动回复编辑', 1, 0, '关注自动回复编辑操作'),
(102, 98, 4, 'upload', '关注自动回复上传', 1, 0, '关注自动回复上传操作'),

(103, 7, 3, 'config', '接口配置', 1, 0, '接口配置方法'),
(104, 103, 4, 'editor', '接口配置编辑', 1, 0, '接口配置编辑操作'),

(105, 7, 3, 'menu', '自定义菜单', 1, 0, '自定义菜单方法'),
(106, 105, 4, 'editor', '自定义菜单编辑', 1, 0, '自定义菜单编辑操作'),
(107, 105, 4, 'upload', '自定义菜单上传', 1, 0, '自定义菜单上传操作'),

(108, 8, 3, 'goods', '管理商品', 1, 0, '管理商品方法'),
(109, 108, 4, 'added', '管理商品添加', 1, 0, '管理商品添加操作'),
(110, 108, 4, 'remove', '管理商品删除', 1, 0, '管理商品删除操作'),
(111, 108, 4, 'editor', '管理商品编辑', 1, 0, '管理商品编辑操作'),
(112, 108, 4, 'upload', '管理商品上传', 1, 0, '管理商品上传操作'),

(113, 8, 3, 'orders', '管理订单', 1, 0, '管理订单方法'),
(114, 113, 4, 'remove', '管理订单删除', 1, 0, '管理订单删除操作'),
(115, 113, 4, 'editor', '管理订单编辑', 1, 0, '管理订单编辑操作'),

(116, 8, 3, 'category', '管理商城导航', 1, 0, '管理商城导航方法'),
(117, 116, 4, 'added', '管理商城导航添加', 1, 0, '管理商城导航添加操作'),
(118, 116, 4, 'remove', '管理商城导航删除', 1, 0, '管理商城导航删除操作'),
(119, 116, 4, 'editor', '管理商城导航编辑', 1, 0, '管理商城导航编辑操作'),
(120, 116, 4, 'upload', '管理商城导航上传', 1, 0, '管理商城导航上传操作'),

(121, 8, 3, 'type', '管理商品分类', 1, 0, '管理商品分类方法'),
(122, 121, 4, 'added', '管理商品分类添加', 1, 0, '管理商品分类添加操作'),
(123, 121, 4, 'remove', '管理商品分类删除', 1, 0, '管理商品分类删除操作'),
(124, 121, 4, 'editor', '管理商品分类编辑', 1, 0, '管理商品分类编辑操作'),

(125, 8, 3, 'brand', '管理商品品牌', 1, 0, '管理商品品牌方法'),
(126, 125, 4, 'added', '管理商品品牌添加', 1, 0, '管理商品品牌添加操作'),
(127, 125, 4, 'remove', '管理商品品牌删除', 1, 0, '管理商品品牌删除操作'),
(128, 125, 4, 'editor', '管理商品品牌编辑', 1, 0, '管理商品品牌编辑操作'),
(129, 125, 4, 'upload', '管理商品品牌上传', 1, 0, '管理商品品牌上传操作'),

(130, 8, 3, 'comment', '管理商品评论', 1, 0, '管理商品评论方法'),
(131, 130, 4, 'remove', '管理商品评论删除', 1, 0, '管理商品评论删除操作'),
(132, 130, 4, 'editor', '管理商品评论编辑', 1, 0, '管理商品评论编辑操作'),

(133, 8, 3, 'account', '账户流水', 1, 0, '账户流水方法'),

(134, 8, 3, 'grecycle', '商品回收站', 1, 0, '商品回收站方法'),
(135, 134, 4, 'remove', '商品回收站删除', 1, 0, '商品回收站删除操作'),
(136, 134, 4, 'recover', '商品回收站恢复', 1, 0, '商品回收站恢复操作'),

(137, 8, 3, 'settings', '商城设置', 1, 0, '商城设置方法'),
(138, 137, 4, 'editor', '商城设置编辑', 1, 0, '商城设置编辑操作'),

(139, 9, 3, 'book', '管理书库', 1, 0, '管理书库方法'),
(140, 139, 4, 'added', '管理书库添加', 1, 0, '管理书库添加操作'),
(141, 139, 4, 'remove', '管理书库删除', 1, 0, '管理书库删除操作'),
(142, 139, 4, 'editor', '管理书库编辑', 1, 0, '管理书库编辑操作'),
(143, 139, 4, 'upload', '管理书库上传', 1, 0, '管理书库上传操作'),

(144, 9, 3, 'article', '管理章节', 1, 0, '管理章节方法'),
(145, 144, 4, 'added', '管理章节添加', 1, 0, '管理章节添加操作'),
(146, 144, 4, 'remove', '管理章节删除', 1, 0, '管理章节删除操作'),
(147, 144, 4, 'editor', '管理章节编辑', 1, 0, '管理章节编辑操作'),

(148, 9, 3, 'type', '管理分类', 1, 0, '管理分类方法'),
(149, 148, 4, 'added', '管理分类添加', 1, 0, '管理分类添加操作'),
(150, 148, 4, 'remove', '管理分类删除', 1, 0, '管理分类删除操作'),
(151, 148, 4, 'editor', '管理分类编辑', 1, 0, '管理分类编辑操作'),

(152, 9, 3, 'author', '管理作者', 1, 0, '管管理作者方法'),
(153, 152, 4, 'added', '管理作者添加', 1, 0, '管理作者添加操作'),
(154, 152, 4, 'remove', '管理作者删除', 1, 0, '管理作者删除操作'),
(155, 152, 4, 'editor', '管理作者编辑', 1, 0, '管理作者编辑操作'),

(156, 10, 3, 'log', '系统日志', 1, 0, '系统日志方法'),

(157, 10, 3, 'databack', '数据与备份', 1, 0, '数据与备份方法'),
(158, 157, 4, 'reduction', '数据与备份还原', 1, 0, '数据与备份还原操作'),
(159, 157, 4, 'backup', '数据与备份备份', 1, 0, '数据与备份备份操作'),
(160, 157, 4, 'remove', '数据与备份删除', 1, 0, '数据与备份删除操作'),

(161, 10, 3, 'upgrade', '在线升级', 1, 0, '在线升级方法'),
(162, 10, 3, 'elog', '错误日志', 1, 0, '错误日志方法'),
(163, 10, 3, 'visit', '访问统计', 1, 0, '访问统计方法');

DROP TABLE IF EXISTS `np_role`;
CREATE TABLE IF NOT EXISTS `np_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '组名',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '组表';
INSERT INTO np_role(`name`, `pid`, `status`, `remark`) VALUES('创始人', 0, 1, '创始人');

DROP TABLE IF EXISTS `np_role_admin`;
CREATE TABLE IF NOT EXISTS `np_role_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '组ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '管理员组关系表';
INSERT INTO np_role_admin(`role_id`, `user_id`) VALUES(1, 1);

DROP TABLE IF EXISTS `np_admin`;
CREATE TABLE IF NOT EXISTS `np_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `salt` char(6) NOT NULL DEFAULT '' COMMENT '佐料',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `last_login_ip_attr` varchar(255) NOT NULL DEFAULT '' COMMENT '登录IP地区',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '管理员表';
INSERT INTO np_admin(`username`, `password`, `email`, `salt`) VALUES('levisun', 'de0c5656615eb18d37cfad23e084449b', 'levisun@mail.com', '0af476');
