DROP TABLE IF EXISTS `np_config`;
CREATE TABLE IF NOT EXISTS `np_config` (
  `id` smallint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '名称',
  `value` varchar(500) NOT NULL COMMENT '值',
  `lang` varchar(20) NOT NULL COMMENT '语言 niphp为全局设置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `value` (`value`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '设置表';
INSERT INTO `np_config` (`name`, `value`, `lang`) VALUES
-- 安全与效率
('system_portal', 'hor', 'niphp'),
('content_check', '1', 'niphp'),
('member_login_captcha', '1', 'niphp'),
('website_submit_captcha', '1', 'niphp'),
('upload_file_max', '5', 'niphp'),
('upload_file_type', 'jpg|gif|png', 'niphp'),
('website_static', '0', 'niphp'),

-- 邮件设置
('smtp_host', 'smtp.qq.com', 'niphp'),
('smtp_port', '25', 'niphp'),
('smtp_username', 'levisun.mail@qq.com', 'niphp'),
('smtp_password', 'ehesr1fcvhj6w0bbw1mo', 'niphp'),
('smtp_from_email', 'levisun.mail@qq.com', 'niphp'),
('smtp_from_name', 'niphp', 'niphp'),

-- 微信公众号设置
('wechat_token', 'vcoaur13959748401', 'niphp'),
('wechat_encodingaeskey', 'bmknlqjy2hwxkeymsffnvabyzjdqw8r0lahkevevq9s', 'niphp'),
('wechat_appid', 'wx5b906f26f9d9e621', 'niphp'),
('wechat_appsecret', '53028b1fb3cda6efb99e84c4667d9fb2', 'niphp'),

-- 基本设置
('website_name', '腐朽的木屋_php编程与前端开发', 'zh-cn'),
('website_keywords', 'php,phpcms,php源码,php 框架,php开发工具,企业网站模板,免费网站psd模板,niphpcms', 'zh-cn'),
('website_description', '腐朽的木屋是一个提供最新的php资讯、php技术、php编程技巧、前端开发、设计素材、psd模板的网站。', 'zh-cn'),
('bottom_message', '&lt;a href=&quot;http://www.miitbeian.gov.cn&quot; target=&quot;_blank&quot;&gt;陕icp备15001502号-1&lt;/a&gt;', 'zh-cn'),
('copyright', 'copyright &amp;copy; 2014-2015 &lt;a href=&quot;http://www.niphp.com&quot; target=&quot;_blank&quot;&gt;niphp.com&lt;/a&gt;版权所有', 'zh-cn'),
('script', '&lt;script src=&quot;https://s4.cnzz.com/z_stat.php?id=1255299626&amp;web_id=1255299626&quot; language=&quot;JavaScript&quot;&gt;&lt;/script&gt;
&lt;script&gt;
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement(&quot;script&quot;);
  hm.src = &quot;//hm.baidu.com/hm.js?10513d684eab5a784b249cd5f32f9c03&quot;;
  var s = document.getElementsByTagName(&quot;script&quot;)[0];
  s.parentNode.insertBefore(hm, s);
})();
&lt;/script&gt;', 'zh-cn'),

-- 图片
('auto_image', '1', 'zh-cn'),
('add_water', '1', 'zh-cn'),
('water_type', '1', 'zh-cn'),
('water_location', '1', 'zh-cn'),
('water_text', 'niphp', 'zh-cn'),
('water_image', './upload/water/lots.png', 'zh-cn'),
('article_module_width', '200', 'zh-cn'),
('article_module_height', '150', 'zh-cn'),
('ask_module_width', '50', 'zh-cn'),
('ask_module_height', '50', 'zh-cn'),
('download_module_width', '600', 'zh-cn'),
('download_module_height', '160', 'zh-cn'),
('job_module_width', '200', 'zh-cn'),
('job_module_height', '150', 'zh-cn'),
('link_module_width', '100', 'zh-cn'),
('link_module_height', '50', 'zh-cn'),
('page_module_width', '200', 'zh-cn'),
('page_module_height', '160', 'zh-cn'),
('picture_module_width', '200', 'zh-cn'),
('picture_module_height', '150', 'zh-cn'),
('product_module_width', '200', 'zh-cn'),
('product_module_height', '150', 'zh-cn'),

-- 模板设置
('cms_theme', 'design', 'zh-cn'),
('member_theme', 'default', 'zh-cn'),
('mall_theme', 'default', 'zh-cn'),

-- 商城设置
('mall_name', '商城名称', 'zh-cn'),
('mall_keywords', '商城关键词', 'zh-cn'),
('mall_description', '商城描述', 'zh-cn'),
('mall_bottom_message', '商城底部信息', 'zh-cn'),
('mall_copyright', '商城版权', 'zh-cn'),
('mall_script', '商城JS', 'zh-cn'),
('mall_postage', '1500', 'zh-cn'),
('mall_free_postage', '18000', 'zh-cn'),
('mall_fast_mail', '商城快递名称', 'zh-cn'),
('mall_integral', '1', 'zh-cn'),
('mall_integral_ratio', '100', 'zh-cn'),
('mall_red', '1', 'zh-cn');
