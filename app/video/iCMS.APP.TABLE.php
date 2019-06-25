<?php defined('iPHP') OR exit('What are you doing?');?>
CREATE TABLE `#iCMS@__video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID категории',
  `scid` varchar(255) NOT NULL DEFAULT '' COMMENT '副栏目',
  `ucid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户分类',
  `pid` varchar(255) NOT NULL DEFAULT '' COMMENT '属性',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Название',
  `stitle` varchar(255) NOT NULL DEFAULT '' COMMENT '短标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词',
  `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '标签',
  `description` varchar(5120) NOT NULL DEFAULT '' COMMENT 'Описание',
  `clink` varchar(255) NOT NULL DEFAULT '' COMMENT 'Пользовательская ссылка',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT '别名',
  `enname` varchar(255) NOT NULL DEFAULT '' COMMENT 'Транслит',
  `star` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星级',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `genre` varchar(255) NOT NULL DEFAULT '' COMMENT '类型',
  `actor` varchar(255) NOT NULL DEFAULT '' COMMENT '主演',
  `director` varchar(255) NOT NULL DEFAULT '' COMMENT '导演',
  `attrs` varchar(255) NOT NULL DEFAULT '' COMMENT '编剧',
  `year` varchar(255) NOT NULL DEFAULT '' COMMENT '发行年份',
  `version` varchar(255) NOT NULL DEFAULT '' COMMENT '版本',
  `language` varchar(255) NOT NULL DEFAULT '' COMMENT '语言',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '地区',
  `cycle` varchar(255) NOT NULL DEFAULT '' COMMENT '更新周期',
  `ser` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '连载集',
  `release` varchar(512) NOT NULL DEFAULT '' COMMENT 'Дата выхода',
  `time` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '片长',
  `total` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '总集数',
  `company` varchar(255) NOT NULL DEFAULT '' COMMENT '发行公司',
  `tv` varchar(255) NOT NULL DEFAULT '' COMMENT '电视台',
  `score` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '站内评分',
  `scorenum` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '站内评分数',
  `scores` varchar(255) NOT NULL DEFAULT '' COMMENT '影片其它评分',
  `from` varchar(255) DEFAULT '' COMMENT '数据源',
  `editor` varchar(255) NOT NULL DEFAULT '' COMMENT '编辑',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `haspic` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有缩略图',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '预览图',
  `bpic` varchar(255) NOT NULL DEFAULT '' COMMENT '大图',
  `mpic` varchar(255) NOT NULL DEFAULT '' COMMENT '中图',
  `spic` varchar(255) NOT NULL DEFAULT '' COMMENT '小图',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'Внешняя ссылка',
  `tpl` varchar(255) NOT NULL DEFAULT '' COMMENT 'Шаблон',
  `pubdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Время публикации',
  `postime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Время публикации',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总点击数',
  `hits_today` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当天点击数',
  `hits_yday` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '昨天点击数',
  `hits_week` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '周点击',
  `hits_month` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '月点击',
  `favorite` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `comments` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `good` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '顶',
  `bad` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '踩',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `creative` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '文章类型 1原创 0转载',
  `mobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1手机发布 0 pc',
  `postype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型 0用户 1管理员',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '[[0:草稿],[1:正常],[2:回收],[3:审核],[4:不合格]]',
  PRIMARY KEY (`id`),
  KEY `id` (`status`,`id`),
  KEY `hits` (`status`,`hits`),
  KEY `pubdate` (`status`,`pubdate`),
  KEY `hits_week` (`status`,`hits_week`),
  KEY `hits_month` (`status`,`hits_month`),
  KEY `cid_hits` (`status`,`cid`,`hits`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#iCMS@__video_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(10) unsigned NOT NULL DEFAULT '0',
  `photo` mediumtext NOT NULL,
  `body` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aid` (`video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#iCMS@__video_resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '视频ID',
  `from` varchar(128) NOT NULL DEFAULT '' COMMENT '来源',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Название',
  `src` varchar(255) NOT NULL DEFAULT '' COMMENT '数据',
  `size` int(10) NOT NULL DEFAULT '0' COMMENT 'KB',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'VIP',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Статус',
  KEY `id` (`id`),
  KEY `video_id` (`status`,`video_id`,`type`,`from`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#iCMS@__video_story` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键 自增ID',
  `video_id` int(10) unsigned NOT NULL COMMENT '内容ID 关联video表',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '剧情标题',
  `content` mediumtext NOT NULL COMMENT '剧情内容',
  `addtime` int(10) unsigned NOT NULL COMMENT '添加时间',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'VIP',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Статус',
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `#iCMS@__video_spider` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` varchar(200) NOT NULL DEFAULT '',
  `data` varchar(200) NOT NULL DEFAULT '',
  `source` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `data` (`data`),
  KEY `video_id` (`video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','area','0','video','0','大陆','大陆',''),
('0','0','area','0','video','1','香港','香港',''),
('0','0','area','0','video','2','台湾','台湾',''),
('0','0','area','0','video','3','韩国','韩国',''),
('0','0','area','0','video','4','泰国','泰国',''),
('0','0','area','0','video','5','日本','日本',''),
('0','0','area','0','video','6','美国','美国',''),
('0','0','area','0','video','7','英国','英国',''),
('0','0','area','0','video','8','新加坡','新加坡',''),
('0','0','area','0','video','9','其他','其他','');

INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','from','0','video','0','kuyun','kuyun','无需安装任何插件,即可快速播放'),
('0','0','from','0','video','0','磁力链','magnet',''),
('0','0','from','0','video','0','迅雷','xunlei',''),
('0','0','from','0','video','0','酷云m3u8','kkm3u8',''),
('0','0','from','0','video','0','M3U8','m3u8','无需安装任何插件,即可快速播放'),
('0','0','from','0','video','0','ckm3u8','ckm3u8','无需安装任何插件,即可快速播放');

INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','genre','0','video','0','喜剧','喜剧',''),
('0','0','genre','0','video','1','爱情','爱情',''),
('0','0','genre','0','video','2','动作','动作',''),
('0','0','genre','0','video','3','恐怖','恐怖',''),
('0','0','genre','0','video','4','科幻','科幻',''),
('0','0','genre','0','video','5','惊悚','惊悚',''),
('0','0','genre','0','video','6','犯罪','犯罪',''),
('0','0','genre','0','video','7','奇幻','奇幻',''),
('0','0','genre','0','video','8','战争','战争',''),
('0','0','genre','0','video','9','悬疑','悬疑',''),
('0','0','genre','0','video','10','动画','动画',''),
('0','0','genre','0','video','11','文艺','文艺',''),
('0','0','genre','0','video','12','纪录','纪录',''),
('0','0','genre','0','video','13','传记','传记',''),
('0','0','genre','0','video','14','歌舞','歌舞',''),
('0','0','genre','0','video','15','古装','古装',''),
('0','0','genre','0','video','16','警匪','警匪',''),
('0','0','genre','0','video','17','其他','其他','');

INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','publish','0','video','0','2019','2019',''),
('0','0','publish','0','video','1','2018','2018',''),
('0','0','publish','0','video','2','2017','2017',''),
('0','0','publish','0','video','3','2016','2016',''),
('0','0','publish','0','video','4','2015','2015',''),
('0','0','publish','0','video','5','2014','2014',''),
('0','0','publish','0','video','6','2013','2013',''),
('0','0','publish','0','video','7','2012','2012',''),
('0','0','publish','0','video','8','2011','2011','');

INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','version','0','video','0','预告片','预告片','');

INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','year','0','video','0','2019','2019',''),
('0','0','year','0','video','1','2018','2018',''),
('0','0','year','0','video','2','2017','2017',''),
('0','0','year','0','video','3','2016','2016',''),
('0','0','year','0','video','4','2015','2015',''),
('0','0','year','0','video','5','2014','2014',''),
('0','0','year','0','video','6','2013','2013',''),
('0','0','year','0','video','7','2012','2012',''),
('0','0','year','0','video','8','2011','2011',''),
('0','0','year','0','video','9','2010','2010',''),
('0','0','year','0','video','10','2009','2009',''),
('0','0','year','0','video','11','2008','2008',''),
('0','0','year','0','video','12','更早','更早','');
