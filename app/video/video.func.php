<?php

defined('iPHP') OR exit('What are you doing?');
class videoFunc{
	public static $app   = 'video';
	public static $appid = iCMS_APP_VIDEO;
	public static $table = '#iCMS@__video';
	public static function video_list($vars) {
		if ($vars['loop'] === "rel" && empty($vars['id'])) {
			return false;
		}
		iMap::reset();

		$resource  = array();
		$map_where = array();
		$status    = '1';
		isset($vars['status']) && $status = (int) $vars['status'];
		$where_sql = "WHERE `status`='{$status}'";
		$vars['call'] == 'user' && $where_sql .= " AND `postype`='0'";
		$vars['call'] == 'admin' && $where_sql .= " AND `postype`='1'";
		$hidden = categoryApp::get_cache('hidden');
		$hidden && $where_sql .= iSQL::in($hidden, 'cid', 'not');
		$maxperpage = isset($vars['row']) ? (int) $vars['row'] : 10;
		$cache_time = isset($vars['time']) ? (int) $vars['time'] : -1;
		isset($vars['userid']) && $where_sql .= " AND `userid`='{$vars['userid']}'";
		isset($vars['weight']) && $where_sql .= " AND `weight`='{$vars['weight']}'";

		$vars['area'] && $where_sql .= " AND `area`='{$vars['area']}'";
		$vars['year'] && $where_sql .= " AND `year`='{$vars['year']}'";

		if (isset($vars['ucid']) && $vars['ucid'] != '') {
			$where_sql .= " AND `ucid`='{$vars['ucid']}'";
		}

		if (isset($vars['cid!'])) {
			$ncids = explode(',', $vars['cid!']);
			$vars['sub'] && $ncids += categoryApp::get_cids($ncids, true);
			$where_sql .= iSQL::in($ncids, 'cid', 'not');
		}
		if ($vars['cid'] && !isset($vars['cids'])) {
			$cid = explode(',', $vars['cid']);
			$vars['sub'] && $cid += categoryApp::get_cids($cid, true);
			$where_sql .= iSQL::in($cid, 'cid');
		}
		if (isset($vars['cids']) && !$vars['cid']) {
			$cids = explode(',', $vars['cids']);
			$vars['sub'] && $cids += categoryApp::get_cids($vars['cids'], true);

			if ($cids) {
				iMap::init('category', self::$appid,'cid');
				$map_where += iMap::where($cids);
			}
		}
		if (isset($vars['pid']) && !isset($vars['pids'])) {
			iSQL::$check_numeric = true;
			$where_sql .= iSQL::in($vars['pid'], 'pid');
		}
        if(isset($vars['pid!'])){
            iSQL::$check_numeric = true;
            $where_sql.= iSQL::in($vars['pid!'],'pid','not');
        }
		$distinct_stack = -1;
		if (isset($vars['pids']) && !isset($vars['pid'])) {
			iMap::init('prop', self::$appid,'pid');
			$map_where += iMap::where($vars['pids']);
			$distinct_stack++;
		}
		if($vars['genre']){
			iMap::init('prop', self::$appid,'genre');
			$_map_where = (array)iMap::where($vars['genre']);
			$map_where  = array_merge($map_where,$_map_where);
			unset($_map_where);
			$distinct_stack++;
		}
		if($vars['director']){
			iMap::init('tag', self::$appid,'director');
			$_map_where = (array)iMap::where($vars['director']);
			$map_where  = array_merge($map_where,$_map_where);
			unset($_map_where);
			$distinct_stack++;
		}
		if($vars['actor']){
			iMap::init('tag', self::$appid,'actor');
			$_map_where = (array)iMap::where($vars['actor']);
			$map_where  = array_merge($map_where,$_map_where);
			unset($_map_where);
			$distinct_stack++;
		}

		if (isset($vars['tids'])) {
			iMap::init('tag', self::$appid,($vars['field']?$vars['field']:'tags'));
			$_map_where = (array)iMap::where($vars['tids']);
			$map_where  = array_merge($map_where,$_map_where);
			unset($_map_where);
			$distinct_stack++;
		}
        if (isset($vars['keywords'])) {
			if (empty($vars['keywords'])) {
				return;
			}

			if (strpos($vars['keywords'], ',') === false) {
				$vars['keywords'] = str_replace(array('%', '_'), array('\%', '\_'), $vars['keywords']);
				$where_sql .= " AND CONCAT(title,keywords,description) like '%" . addslashes($vars['keywords']) . "%'";
			} else {
                $pieces   = explode(',', $vars['keywords']);
                $pieces   = array_filter ($pieces);
                $pieces   = array_map('addslashes', $pieces);
                $keywords = implode('|', $pieces);
				$where_sql .= " AND CONCAT(title,keywords,description) REGEXP '$keywords' ";
			}
		}

		$vars['id'] && $where_sql .= iSQL::in($vars['id'], 'id');
		$vars['id!'] && $where_sql .= iSQL::in($vars['id!'], 'id', 'not');
		$by = strtoupper($vars['by']) == "ASC" ? "ASC" : "DESC";
		isset($vars['pic']) && $where_sql .= " AND `haspic`='1'";
		isset($vars['nopic']) && $where_sql .= " AND `haspic`='0'";

		switch ($vars['orderby']) {
	        case "new":      $order_sql = " ORDER BY `id` $by"; break;
			case "id":       $order_sql = " ORDER BY `id` $by"; break;
	        case "score":    $order_sql = " ORDER BY `score` $by"; break;
			case "hot":      $order_sql = " ORDER BY `hits` $by"; break;
			case "today":    $order_sql = " ORDER BY `hits_today` $by"; break;
			case "yday":     $order_sql = " ORDER BY `hits_yday` $by"; break;
			case "week":     $order_sql = " ORDER BY `hits_week` $by"; break;
			case "month":    $order_sql = " ORDER BY `hits_month` $by"; break;
			case "good":  	 $order_sql = " ORDER BY `good` $by"; break;
			case "comment":  $order_sql = " ORDER BY `comments` $by"; break;
			case "pubdate":  $order_sql = " ORDER BY `pubdate` $by"; break;
			case "sort": 	 $order_sql = " ORDER BY `sortnum` $by"; break;
			case "weight":   $order_sql = " ORDER BY `weight` $by"; break;
			default:$order_sql = " ORDER BY `id` $by";
		}
		isset($vars['startdate']) && $where_sql .= " AND `pubdate`>='" . strtotime($vars['startdate']) . "'";
		isset($vars['enddate']) && $where_sql .= " AND `pubdate`<='" . strtotime($vars['enddate']) . "'";
		isset($vars['where']) && $where_sql .= $vars['where'];

        if($map_where){
			$map_sql = iSQL::select_map($map_where, 'join');
			//join
			//empty($vars['cid']) && $map_order_sql = " ORDER BY map.`iid` $by";
			$map_table = 'map';
			$vars['map_order_table'] && $map_table = $vars['map_order_table'];
			$map_order_sql = " ORDER BY {$map_table}.`iid` $by";
			//$map_order_sql = " ORDER BY `".self::$table."`.`id` $by";
			//
			$where_sql .= ' AND ' . $map_sql['where'];
			$where_sql = ",{$map_sql['from']} {$where_sql} AND `".self::$table."`.`id` = {$map_table}.`iid`";
			//derived
			// $where_sql = ",({$map_sql}) map {$where_sql} AND `id` = map.`iid`";
		}
		$offset = (int)$vars['offset'];
		if ($vars['page']) {
            $countField = '*';
            $distinct && $countField = "DISTINCT `".self::$table."`.id";
			$total_type = $vars['total_cache'] ? 'G' : null;
			$total      = (int)$vars['total'];
			if(isset($vars['pageNum'])){
				$total = (int)$vars['pageNum']*$maxperpage;
			}
			if(!isset($vars['total']) && !isset($vars['pageNum'])){
				$total = iPagination::totalCache(
					"SELECT count(".$countField.") FROM `".self::$table."` {$where_sql}",
					$total_type,
					iCMS::$config['cache']['page_total']
				);
			}

			$pagenav    = isset($vars['pagenav']) ? $vars['pagenav'] : "pagenav";
			$pnstyle    = isset($vars['pnstyle']) ? $vars['pnstyle'] : 0;
			$multi      = iPagination::make(array(
				'total_type' => $total_type,
				'total'      => $total,
				'perpage'    => $maxperpage,
				'unit'       => iUI::lang('iCMS:page:list'),
				'nowindex'   => $GLOBALS['page']
			));
			$offset     = $multi->offset;
            iView::assign(self::$app."_list_total", $total);
		}
		$limit = "LIMIT {$offset},{$maxperpage}";
		//随机特别处理
		if ($vars['orderby'] == 'rand') {
			$ids_array = iSQL::get_rand_ids(self::$table, $where_sql, $maxperpage, 'id');
			if ($map_order_sql) {
				$map_order_sql = " ORDER BY `".self::$table."`.`id` $by";
			}
		}

		$hash = md5(json_encode($vars) . $order_sql . $limit);

		if ($vars['cache']) {
			$cache_name = iPHP_DEVICE . '/'.self::$app.'/' . $hash;
			$resource = iCache::get($cache_name);
			if(is_array($resource)) return $resource;
		}

		if ($offset) {
			if ($vars['cache']) {
                $page_cache_name = iPHP_DEVICE . '/'.self::$app.'_page/' . $hash;
				$ids_array = iCache::get($page_cache_name);
			}
		}
		$map_order_sql && $order_sql = $map_order_sql;
		if (empty($ids_array)) {
			$sql = "SELECT ".$distinct." `".self::$table."`.`id` FROM `".self::$table."` {$where_sql} {$order_sql} {$limit}";
			if (strpos($sql, '`cid` IN')!==false && empty($map_order_sql) && iCMS::$config['debug']['db_optimize_in']){
				$_sql = iSQL::optimize_in($sql);
				$_sql && $sql = $_sql;
			}
			$ids_array = iDB::all($sql);


			if($vars['cache'] && $page_cache_name){
				iCache::set($page_cache_name, $ids_array, $cache_time);
			}
		}

		if ($ids_array) {
			$ids = iSQL::values($ids_array);
			$ids = $ids ? $ids : '0';
			$where_sql = "WHERE `".self::$table."`.`id` IN({$ids})";
			$order_sql = "ORDER BY FIELD(`id`,{$ids})";
			$limit = '';
		}

		if (empty($resource)) {
			$resource = iDB::all("SELECT `".self::$table."`.* FROM `".self::$table."` {$where_sql} {$order_sql} {$limit}");
			$resource = videoFunc::video_array($vars, $resource);
			$vars['cache'] && iCache::set($cache_name, $resource, $cache_time);
		}
		return $resource;
	}
	public static function video_story($vars) {
        $where_sql  = "WHERE `status`='1'";
        $maxperpage = isset($vars['row'])?(int)$vars['row']:"100";
        $cache_time = isset($vars['time'])?(int)$vars['time']:"-1";

        $video = array();
        if($vars['video']){
	        $video = $vars['video'];
        	$vars['video_id'] = $video['id'];
        }

        $vars['video_id']&& $where_sql .= iSQL::in($vars['video_id'], 'video_id');
        $vars['id']     && $where_sql .= iSQL::in($vars['id'], 'id');
        $vars['id!']    && $where_sql .= iSQL::in($vars['id!'], 'id', 'not');
        $vars['vip']    && $where_sql .= " AND `vip`>'0'";
        $vars['free']   && $where_sql .= " AND `vip`='0'";

        $by=strtoupper($vars['by'])=="ASC"?"ASC":"DESC";
        switch ($vars['orderby']) {
            case "id":      $order_sql =" ORDER BY `id` $by"; break;
            default:        $order_sql =" ORDER BY `id` ASC";
        }
        $hash = md5(json_encode($vars) . $order_sql . $limit);
        if($vars['cache']){
            $cache_name = iPHP_DEVICE.'/video_story/'.$hash;
            $resource   = iCache::get($cache_name);
            if(is_array($resource)) return $resource;
        }
        if(empty($resource)){
            $resource = iDB::all("SELECT ".video::story_fields(false,$vars['data'],true)." FROM `#iCMS@__video_story` {$where_sql} {$order_sql} LIMIT $maxperpage");
            videoApp::setData('video',$video);
            $resource && $resource = array_map(array("videoApp","story_value"), $resource);
            videoApp::unData();
            $vars['cache'] && iCache::set($cache_name,$resource,$cache_time);
        }
        return $resource;
	}
	public static function video_play($vars) {
		unset($vars['app'],$vars['method']);
		$vars['type'] = 'play';
		return self::video_res($vars);
	}
	public static function video_down($vars) {
		unset($vars['app'],$vars['method']);
		$vars['type'] = 'down';
		return self::video_res($vars);
	}
	public static function video_res($vars) {
		$resource   = array();
		$table      = self::$table."_resource";
		$maxperpage = isset($vars['row']) ? (int) $vars['row'] : 1000;
		$cache_time = isset($vars['time']) ? (int) $vars['time'] : -1;

        $video = array();
        if($vars['video']){
	        $video = $vars['video'];
        	$vars['video_id'] = $video['id'];
        }
        $vars['video_id'] OR iUI::warning('iCMS&#x3a;video&#x3a;'.$vars['type'].' 标签出错! 缺少"(video_id,video)"属性或"(video_id,video)"值为空.');

        $vid        = (int) $vars['video_id'];
		$where_sql  = "WHERE `status`='1' AND `video_id`='{$vid}'";
		$vars['type'] == 'play' && $where_sql .= " AND `type`='0'";
		$vars['type'] == 'down' && $where_sql .= " AND `type`='1'";

		$vars['from']&& $where_sql .= " AND `from`='{$vars['from']}'";
		$vars['id']  && $where_sql .= iSQL::in($vars['id'], 'id');
		$vars['id!'] && $where_sql .= iSQL::in($vars['id!'], 'id', 'not');

		$by = strtoupper($vars['by']) == "ASC" ? "ASC" : "DESC";
		isset($vars['pic']) && $where_sql .= " AND `haspic`='1'";
		isset($vars['nopic']) && $where_sql .= " AND `haspic`='0'";

		switch ($vars['orderby']) {
			case "id":       $order_sql = " ORDER BY `id` $by"; break;
			case "update": 	 $order_sql = " ORDER BY `update` $by"; break;
			case "sort": 	 $order_sql = " ORDER BY `sortnum` $by"; break;
			default:$order_sql = " ORDER BY `id` $by";
		}
		isset($vars['where']) && $where_sql .= $vars['where'];

		$offset = (int)$vars['offset'];
		if ($vars['page']) {
            $countField = 'id';
			$total_type = $vars['total_cache'] ? 'G' : null;
			$total      = (int)$vars['total'];
			if(isset($vars['pageNum'])){
				$total = (int)$vars['pageNum']*$maxperpage;
			}
			if(!isset($vars['total']) && !isset($vars['pageNum'])){
				$total = iPagination::totalCache(
					"SELECT count(".$countField.") FROM `".$table."` {$where_sql}",
					$total_type,
					iCMS::$config['cache']['page_total']
				);
			}

			$pagenav    = isset($vars['pagenav']) ? $vars['pagenav'] : "pagenav";
			$pnstyle    = isset($vars['pnstyle']) ? $vars['pnstyle'] : 0;
			$multi      = iPagination::make(array(
				'total_type' => $total_type,
				'total'      => $total,
				'perpage'    => $maxperpage,
				'unit'       => iUI::lang('iCMS:page:list'),
				'nowindex'   => $GLOBALS['page']
			));
			$offset     = $multi->offset;
            iView::assign(self::$app."_res_total", $total);
		}
		$limit = "LIMIT {$offset},{$maxperpage}";
		$hash  = md5(json_encode($vars) . $order_sql . $limit);

		if ($vars['cache']) {
			$cache_name = iPHP_DEVICE . '/'.self::$app.'_res/' . $hash;
			$resource = iCache::get($cache_name);
			if(is_array($resource)) return $resource;
		}

		if ($offset) {
			if ($vars['cache']) {
                $page_cache_name = iPHP_DEVICE . '/'.self::$app.'_res_page/' . $hash;
				$ids_array = iCache::get($page_cache_name);
			}
		}
		if (empty($ids_array)) {
			$sql = "SELECT `".$table."`.`id` FROM `".$table."` {$where_sql} {$order_sql} {$limit}";
			$ids_array = iDB::all($sql);
			if($vars['cache'] && $page_cache_name){
				iCache::set($page_cache_name, $ids_array, $cache_time);
			}
		}

		if ($ids_array) {
			$ids = iSQL::values($ids_array);
			$ids = $ids ? $ids : '0';
			$where_sql = "WHERE `".$table."`.`id` IN({$ids})";
			$order_sql = "ORDER BY FIELD(`id`,{$ids})";
			$limit = '';
		}

		if (empty($resource)) {
			$resource = iDB::all("SELECT `".$table."`.* FROM `".$table."` {$where_sql} {$order_sql} {$limit}");
			videoApp::setData('video',$video);
            $resource && $resource = array_map(array("videoApp","res_value"), $resource);
            videoApp::unData();
			$vars['cache'] && iCache::set($cache_name, $resource, $cache_time);
		}
		return $resource;
	}
	public static function video_search($vars) {
		if(empty(iCMS::$config['sphinx']['host'])) return array();

		$resource = array();
		$hidden = categoryApp::get_cache('hidden');
		$hidden && $where_sql .= iSQL::in($hidden, 'cid', 'not');
		$SPH = iPHP::vendor('SphinxClient',iCMS::$config['sphinx']['host']);
		$SPH->SetArrayResult(true);
		if (isset($vars['weights'])) {
			//weights='title:100,tags:80,keywords:60,name:50'
			$wa = explode(',', $vars['weights']);
			foreach ($wa AS $wk => $wv) {
				$waa = explode(':', $wv);
				$FieldWeights[$waa[0]] = $waa[1];
			}
			$FieldWeights OR $FieldWeights = array("title" => 100, "tags" => 80, "name" => 60, "keywords" => 40);
			$SPH->SetFieldWeights($FieldWeights);
		}

		$page = (int) $_GET['page'];
		$maxperpage = isset($vars['row']) ? (int) $vars['row'] : 10;
		$start = ($page && isset($vars['page'])) ? ($page - 1) * $maxperpage : 0;
		$SPH->SetMatchMode(SPH_MATCH_EXTENDED);
		if ($vars['mode']) {
			$vars['mode'] == "SPH_MATCH_BOOLEAN" && $SPH->SetMatchMode(SPH_MATCH_BOOLEAN);
			$vars['mode'] == "SPH_MATCH_ANY" && $SPH->SetMatchMode(SPH_MATCH_ANY);
			$vars['mode'] == "SPH_MATCH_PHRASE" && $SPH->SetMatchMode(SPH_MATCH_PHRASE);
			$vars['mode'] == "SPH_MATCH_ALL" && $SPH->SetMatchMode(SPH_MATCH_ALL);
			$vars['mode'] == "SPH_MATCH_EXTENDED" && $SPH->SetMatchMode(SPH_MATCH_EXTENDED);
		}

		isset($vars['userid']) && $SPH->SetFilter('userid', array($vars['userid']));
		isset($vars['postype']) && $SPH->SetFilter('postype', array($vars['postype']));

		if (isset($vars['cid'])) {
			$cids = $vars['sub'] ? categoryApp::get_cids($vars['cid'], true) : (array) $vars['cid'];
			$cids OR $cids = (array) $vars['cid'];
			$cids = array_map("intval", $cids);
			$SPH->SetFilter('cid', $cids);
		}
		if (isset($vars['cid!'])) {
			$cids = $vars['sub'] ? categoryApp::get_cids($vars['cid!'], true) : (array) $vars['cid!'];
			$cids OR $cids = (array) $vars['cid!'];
			$cids = array_map("intval", $cids);
			$SPH->SetFilter('cid', $cids, true);
		}
		if (isset($vars['startdate'])) {
			$startime = strtotime($vars['startdate']);
			$enddate = empty($vars['enddate']) ? time() : strtotime($vars['enddate']);
			$SPH->SetFilterRange('pubdate', $startime, $enddate);
		}
		$SPH->SetLimits($start, $maxperpage, 10000);

		$orderby = '@id DESC, @weight DESC';
		$order_sql = ' order by id DESC';

		$vars['orderby'] && $orderby = $vars['orderby'];
		$vars['ordersql'] && $order_sql = ' order by ' . $vars['ordersql'];

		$vars['pic'] && $SPH->SetFilter('haspic', array(1));
		$vars['id!'] && $SPH->SetFilter('@id', array($vars['id!']), true);

		$SPH->setSortMode(SPH_SORT_EXTENDED, $orderby);

		$query = str_replace(',', '|', $vars['q']);
		$vars['acc'] && $query = '"' . $vars['q'] . '"';
		$vars['@'] && $query = '@(' . $vars['@'] . ') ' . $query;

		$res = $SPH->Query($query, iCMS::$config['sphinx']['index']);

		if($res===false){
			$msg = array();
			$SPH->_error    && $msg[] = '[ERROR]'.$SPH->GetLastError();
			$SPH->_warning  && $msg[] = '[WARNING]'.$SPH->GetLastWarning();
			$SPH->_connerror&& $msg[] = '[connerror]'.$SPH->connerror;
			iUI::warning(implode('<hr />', $msg));
		}

		$ids_array = array();
		if (is_array($res["matches"])) {
			foreach ($res["matches"] as $docinfo) {
				$ids_array[] = $docinfo['id'];
			}
			$ids = implode(',', (array) $ids_array);
		}
		if (empty($ids)) {
			return;
		}

		$where_sql = " `id` in($ids)";
		$offset = 0;
		if ($vars['page']) {
			$total = $res['total'];
			iView::assign(self::$app."_search_total", $total);
			$pagenav = isset($vars['pagenav']) ? $vars['pagenav'] : "pagenav";
			$pnstyle = isset($vars['pnstyle']) ? $vars['pnstyle'] : 0;
			$multi = iPagination::make(array('total' => $total, 'perpage' => $maxperpage, 'unit' => iUI::lang('iCMS:page:list'), 'nowindex' => $GLOBALS['page']));
			$offset = $multi->offset;
		}
		$resource = iDB::all("SELECT * FROM `".self::$table."` WHERE {$where_sql} {$order_sql} LIMIT {$maxperpage}");
		$resource = videoFunc::video_array($vars, $resource);
		return $resource;
	}
	public static function video_data($vars) {
		$vars['vid'] OR iUI::warning('iCMS&#x3a;video&#x3a;data 标签出错! 缺少"vid"属性或"vid"值为空.');
		$data = iDB::row("SELECT body,subtitle FROM `#iCMS@__video_data` WHERE `video_id`='" . (int) $vars['vid'] . "' LIMIT 1;", ARRAY_A);
		videoApp::hooked($data);
		return $data;
	}
	public static function video_prev($vars) {
		$vars['order'] = 'p';
		return videoFunc::video_next($vars);
	}
	public static function video_next($vars) {
		// if($vars['param']){
		//     $vars+= $vars['param'];
		//     unset($vars['param']);
		// }
		empty($vars['order']) && $vars['order'] = 'n';

		$cache_time = isset($vars['time']) ? (int) $vars['time'] : -1;
		if (isset($vars['cid'])) {
			$sql = " AND `cid`='{$vars['cid']}' ";
		}
		$field = '`id`';
		if ($vars['order'] == 'p') {
			// $field = 'max(id)'; //INNODB
			// $sql .= " AND `id` < '{$vars['id']}'";
			$sql .= " AND `id` < '{$vars['id']}' ORDER BY id DESC LIMIT 1";//MyISAM
		} else if ($vars['order'] == 'n') {
			// $field = 'min(id)';//INNODB
			// $sql .= " AND `id` > '{$vars['id']}'";
			$sql .= " AND `id` > '{$vars['id']}' ORDER BY id ASC LIMIT 1";//MyISAM
		}
		$hash = md5($sql);
		if ($vars['cache']) {
            $cache = iPHP_DEVICE . '/'.self::$app.'/' . $hash;
			$array = iCache::get($cache);
		}
		if (empty($array)) {
			$rs = iDB::row("
				SELECT * FROM `".self::$table."` WHERE `id` =(SELECT {$field} FROM `".self::$table."` WHERE `status`='1' {$sql})
			");
			if ($rs) {
				$category = categoryApp::get_cache_cid($rs->cid);
				$array = array(
					'id'    => $rs->id,
					'title' => $rs->title,
					'pic'   => filesApp::get_pic($rs->pic),
					'url'   => iURL::get(self::$app, array((array) $rs, $category))->href,
				);
			}
			$vars['cache'] && iCache::set($cache, $array, $cache_time);
		}
		return $array;
	}
	public static function video_array($vars, $variable) {
		$resource = array();
		if ($variable) {
	        if($vars['data']||$vars['pics']){
	            $idArray = iSQL::values($variable,'id','array',null);
	            $idArray && $video_data = (array) videoApp::data($idArray);
	            unset($idArray);
	        }
	        if($vars['meta']){
	            $idArray = iSQL::values($variable,'id','array',null);
				$idArray && $meta_data = (array)apps_meta::data('video',$idArray);
	            unset($idArray);
	        }

	        if($vars['tags']){
	            $tagArray = iSQL::values($variable,'tags','array',null,'id');
				$tagArray && $tags_data = (array)tagApp::multi_tag($tagArray);
	            unset($tagArray);
	            $vars['tag'] = false;
	        }
	        if($vars['actor']){
	            $actorArray = iSQL::values($variable,'actor','array',null,'id');
				$actorArray && $actor_data = (array)tagApp::multi_tag($actorArray,'actor');
	            unset($actorArray);
	            $vars['_actor'] = false;
	        }
	        if($vars['director']){
	            $directorArray = iSQL::values($variable,'director','array',null,'id');
				$directorArray && $director_data = (array)tagApp::multi_tag($directorArray,'director');
	            unset($directorArray);
	            $vars['_director'] = false;
	        }
	        if($vars['attrs']){
	            $attrsArray = iSQL::values($variable,'attrs','array',null,'id');
				$attrsArray && $attrs_data = (array)tagApp::multi_tag($attrsArray,'attrs');
	            unset($attrsArray);
	            $vars['_attrs'] = false;
	        }

			foreach ($variable as $key => $value) {
				$value = videoApp::value($value, false, $vars);

				if ($value === false) {
					continue;
				}
	            if(($vars['data']||$vars['pics']) && $video_data){
	                $value['data']  = (array)$video_data[$value['id']];
	                if($vars['pics']){
						$value['pics'] = filesApp::get_content_pics($value['data']['body']);
						if(!$value['data']){
							unset($value['data']);
						}
	                }
	            }

	            if($vars['tags'] && $tags_data){
	                $value+= (array)$tags_data[$value['id']];
	            }
	            if($vars['actor'] && $actor_data){
	                $value+= (array)$actor_data[$value['id']];
	            }
	            if($vars['director'] && $director_data){
	                $value+= (array)$director_data[$value['id']];
	            }
	            if($vars['attrs'] && $attrs_data){
	                $value+= (array)$attrs_data[$value['id']];
	            }
	            if($vars['meta'] && $meta_data){
	                $value+= (array)$meta_data[$value['id']];
	            }

				if ($vars['page']) {
					$value['page'] = $GLOBALS['page'] ? $GLOBALS['page'] : 1;
					$value['total'] = $total;
				}
				if ($vars['archive'] == "date") {
					$_date = archive_date($value['postime']);
					$resource[$_date][$key] = $value;
				} else {
					$resource[$key] = $value;
				}
				unset($variable[$key]);
			}
			$vars['keys'] && iSQL::pickup_keys($resource,$vars['keys'],$vars['is_remove_keys']);
		}
		return $resource;
	}
}
