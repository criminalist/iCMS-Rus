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
	return $msg;
});

