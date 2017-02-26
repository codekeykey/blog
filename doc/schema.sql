CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `telephone` varchar(32) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY(`id`),
  UNIQUE KEY `base_user_idx_1` (`username`) USING BTREE,
  KEY `base_user_idx_2` (`telephone`) USING BTREE
) ENGINE=Innodb DEFAULT CHARSET=utf8 COMMENT='管理员列表';


CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `author` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '作者',
  `category` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类别',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '博客标题',
  `content` varchar(10192) NOT NULL DEFAULT '' COMMENT '博客正文',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY(`id`),
  KEY `base_user_idx_1` (`author`) USING BTREE,
  KEY `base_user_idx_2` (`category`) USING BTREE
) ENGINE=Innodb DEFAULT CHARSET=utf8 COMMENT='博客表';
