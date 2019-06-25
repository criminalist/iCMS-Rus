<?php
@set_time_limit(0);
defined('iPHP') OR exit('What are you doing?');

return apps_store::setup_func(function(){

	iDB::query("
		UPDATE `#iCMS@__apps` SET
		`menu` = '[{\"id\":\"{app}\",\"caption\":\"{name}\",\"icon\":\"pencil-square-o\",\"children\":[{\"caption\":\"{name}系统配置\",\"href\":\"{app}&do=config\",\"icon\":\"cog\"},{\"caption\":\"-\"},{\"caption\":\"更新栏目缓存\",\"href\":\"{app}_category&do=cache\",\"icon\":\"refresh\",\"target\":\"iPHP_FRAME\"},{\"caption\":\"-\"},{\"caption\":\"采集资源管理\",\"href\":\"{app}_spider&do=manage\",\"icon\":\"bug\"},{\"caption\":\"-\"},{\"caption\":\"栏目管理\",\"href\":\"{app}_category\",\"icon\":\"list-alt\"},{\"caption\":\"添加栏目\",\"href\":\"{app}_category&do=add\",\"icon\":\"edit\"},{\"caption\":\"-\"},{\"caption\":\"添加{name}\",\"href\":\"{app}&do=add\",\"icon\":\"edit\"},{\"caption\":\"{name}管理\",\"href\":\"{app}&do=manage\",\"icon\":\"list-alt\"},{\"caption\":\"草稿箱\",\"href\":\"{app}&do=inbox\",\"icon\":\"inbox\"},{\"caption\":\"回收站\",\"href\":\"{app}&do=trash\",\"icon\":\"trash-o\"},{\"caption\":\"-\"},{\"caption\":\"{name}评论管理\",\"href\":\"comment&appname={app}&appid={appid}\",\"icon\":\"comments\"}]}]'
		WHERE `app` = 'video'
	");

    apps::cache();
    menu::cache();
    $msg.='[video]应用更新菜单<iCMS>';

	iDB::query("
CREATE TABLE IF NOT EXISTS `#iCMS@__video_spider` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` varchar(200) NOT NULL DEFAULT '',
  `data` varchar(200) NOT NULL DEFAULT '',
  `source` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `data` (`data`),
  KEY `video_id` (`video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");

	iDB::query("
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
	");
	iDB::query("
INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','from','0','video','0','kuyun','kuyun','无需安装任何插件,即可快速播放'),
('0','0','from','0','video','0','磁力链','magnet',''),
('0','0','from','0','video','0','迅雷','xunlei',''),
('0','0','from','0','video','0','酷云m3u8','kkm3u8',''),
('0','0','from','0','video','0','M3U8','m3u8','无需安装任何插件,即可快速播放'),
('0','0','from','0','video','0','ckm3u8','ckm3u8','无需安装任何插件,即可快速播放');
	");
	iDB::query("
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
	");
	iDB::query("
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
	");
	iDB::query("
INSERT INTO `#iCMS@__prop` (`rootid`, `cid`, `field`, `appid`, `app`, `sortnum`, `name`, `val`, `info`) VALUES
('0','0','version','0','video','0','预告片','预告片','');
	");
	iDB::query("
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
	");

propAdmincp::cache();

    $msg.='更新属性<iCMS>';
	return $msg;
});

