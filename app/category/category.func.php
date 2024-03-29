<?php

class categoryFunc{
	public static function category_array($vars){
		$cid = (int)$vars['cid'];
		return categoryApp::category($cid,false);
	}
	public static function category_list($vars){
		$row        = isset($vars['row'])?(int)$vars['row']:"100";
		$cache_time = isset($vars['time'])?(int)$vars['time']:"-1";
		$status     = isset($vars['status'])?(int)$vars['status']:"1";
		$maxperpage = isset($vars['row'])?(int)$vars['row']:"10";
		$where_sql  = " WHERE `status`='$status'";
		$resource   = array();
		iMap::reset();

		if(isset($vars['apps'])){
			$apps = apps::get_app($vars['apps'],false);
			$apps['id'] && $where_sql.= iSQL::in($apps['id'],'appid');
		}
		if(!is_numeric($vars['appid'])){
			$apps = apps::get_app($vars['appid'],false);
			$apps['id'] && $vars['appid'] = $apps['id'];
		}
		isset($vars['appid'])  && $where_sql.= iSQL::in($vars['appid'],'appid');
		isset($vars['mode'])   && $where_sql.= iSQL::in($vars['mode'],'mode');
		isset($vars['dir'])    && $where_sql.= iSQL::in($vars['dir'],'dir');
		isset($vars['cid'])    && !isset($vars['stype']) && $where_sql.= iSQL::in($vars['cid'],'cid');
		isset($vars['cid!'])   && $where_sql.= iSQL::in($vars['cid!'],'cid','not');
		isset($vars['rootid']) && $where_sql.=" AND `rootid`='".(int)$vars['rootid']."'";
		isset($vars['rootid!'])&& $where_sql.=" AND `rootid`!='".(int)$vars['rootid!']."'";

		if($vars['stype']=='sub' && isset($vars['sub'])){
			$vars['stype']='suball';
		}
		switch ($vars['stype']) {
			case "top":
				$vars['cid'] && $where_sql.= iSQL::in($vars['cid'],'cid');
				$where_sql.=" AND rootid='0'";
			break;
			case "sub":
				$vars['cid'] && $where_sql.= iSQL::in($vars['cid'],'rootid');
			break;
			case "suball":
				$cids = categoryApp::get_cids($vars['cid'],true);
				$where_sql.= iSQL::in($cids,'cid');
			break;
			case "self":
				$parentid = categoryApp::get_cache('parent',$vars['cid']);
				$where_sql.=" AND `rootid`='$parentid'";
			break;
		}

		if (isset($vars['pid']) && !isset($vars['pids'])) {
			iSQL::$check_numeric = true;
			$where_sql .= iSQL::in($vars['pid'], 'pid');
		}
        if(isset($vars['pid!'])){
            iSQL::$check_numeric = true;
            $where_sql.= iSQL::in($vars['pid!'],'pid','not');
        }
		if (isset($vars['pids']) && !isset($vars['pid'])) {
			iMap::init('prop', iCMS_APP_CATEGORY,'pid');
			$where_sql.= iMap::exists($vars['pids'],'`#iCMS@__category`.cid'); //主表小 map表大
			// $map_where=iMap::where($vars['pids']); //主表大 map表大
		}
		isset($vars['where']) && $where_sql .= $vars['where'];
		isset($vars['where[]']) && $where_sql .= iSQL::where($vars['where[]'],true);

		$by = strtoupper($vars['by'])=='DESC'?"DESC":"ASC";

		switch ($vars['orderby']) {
			case "hot":		$order_sql=" ORDER BY `count` $by";		break;
			case "new":		$order_sql=" ORDER BY `cid` $by";			break;
			default:		$order_sql=" ORDER BY `sortnum` $by";
		}

		$offset = (int)$vars['offset'];
		if($vars['page']){
			$total	= iPagination::totalCache("SELECT count(*) FROM `#iCMS@__category` {$where_sql} ",null,iCMS::$config['cache']['page_total']);
			$multi  = iPagination::make(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>iUI::lang('iCMS:page:list'),'nowindex'=>$GLOBALS['page']));
			$offset = $multi->offset;
			iView::assign("category_list_total",$total);
		}
		$limit  = "LIMIT {$offset},{$maxperpage}";

	    if($vars['orderby']=='rand'){
	        $ids_array = iSQL::get_rand_ids('#iCMS@__category',$where_sql,$maxperpage,'cid');
	    }









		if($vars['cache']){
			$hash = md5(json_encode($vars) . $order_sql);
			$cache_name = iPHP_DEVICE . '/category/'.$hash.'/'.$offset.'_'.$maxperpage;
			isset($vars['cache_name']) && $cache_name = $vars['cache_name'];
			$c_resource = iCache::get($cache_name);
			if(is_array($c_resource)) return $c_resource;
		}

		if ($ids_array) {
			$ids = iSQL::values($ids_array,'cid');
			$ids = $ids ? $ids : '0';
			$where_sql = "WHERE `#iCMS@__category`.`cid` IN({$ids})";
			$limit = '';
		}

		$resource = iDB::all("SELECT `cid` FROM `#iCMS@__category` {$where_sql} {$order_sql} {$limit}");

		if($resource){
	        if($vars['meta']){
	            $cidArray = iSQL::values($resource,'cid','array',null);
				$cidArray && $meta_data = (array)apps_meta::data('category',$cidArray);
	            unset($cidArray);
	        }
			foreach ($resource as $key => $value) {
				$cate = categoryApp::get_cache_cid($value['cid']);
	            if($vars['meta'] && $meta_data){
	                $cate+= (array)$meta_data[$value['cid']];
	            }
				$cate && $resource[$key] = categoryApp::get_lite($cate);
			}
		}
		$vars['keys'] && iSQL::pickup_keys($resource,$vars['keys'],$vars['is_remove_keys']);
		$vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
		return $resource;
	}
	public static function category_select($vars){
		$selected  = $vars['selected'];
		$cid   = (int)$vars['cid'];
		$level = $vars['level'];
		empty($level) && $level =1;
		$rootid = categoryApp::get_cache('rootid');
		$html = null;
		foreach ((array) $rootid[$cid] AS $root => $_cid) {
			$C = categoryApp::get_cache_cid($_cid);
			if(isset($vars['appid']) && $C['appid']!=$vars['appid']){
				continue;
			}
			if($C['status']=='2'){
				continue;
			}
			if ($C['status'] && $C['config']['ucshow'] && $C['config']['send'] && empty($C['outurl'])) {
				$tag = ($level == '1' ? "" : "├ ");
				$selected = ($selected == $C['cid']) ? "selected" : "";
				$text = str_repeat("│　", $level - 1) . $tag . $C['name'] . "[cid:{$C['cid']}]" . ($C['outurl'] ? "[∞]" : "");
				$C['config']['examine'] && $text .= '[审核]';
				$option = "<option value='{$C['cid']}' $selected>{$text}</option>";
				if(isset($vars['echo'])){
					echo $option;
				}else{
					 $html.= $option;
				}
			}
			if($rootid[$C['cid']]){
				$option = self::category_select(array(
					'selected'  => $selected,
					'cid'   => $C['cid'],
					'level' => $level+1,
				));
				if(isset($vars['echo'])){
					echo $option;
				}else{
					 $html.= $option;
				}
			}
		}
		if(!isset($vars['echo'])){
			return $html;
		}
	}
}
