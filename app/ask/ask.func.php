<?php

defined('iPHP') OR exit('What are you doing?');
class askFunc{
	public static $interface = array();

	public static function ask_widget($vars=null){
		$name = $vars['name'];
		iView::$app = 'ask';
		iView::display("iCMS://widget/{$name}.htm");
	}

	public static function ask_ui($vars=null){
		iView::assign(__FUNCTION__,$vars);
		iView::$app = 'ask';
		iView::display("iCMS://ask.ui.htm");
	}

	public static function ask_answer($vars) {
		$iid = (int) self::$interface['iid'];

		$status = '1';
		isset($vars['status']) && $status = (int) $vars['status'];

		$where_sql = "WHERE `status`='{$status}' AND `iid`='{$iid}'";
		$maxperpage = isset($vars['row']) ? (int) $vars['row'] : 10;
		$cache_time = isset($vars['time']) ? (int) $vars['time'] : -1;
		isset($vars['userid']) && $where_sql .= " AND `userid`='{$vars['userid']}'";

		$vars['id'] && $where_sql .= iSQL::in($vars['id'], 'id');
		$vars['id!'] && $where_sql .= iSQL::in($vars['id!'], 'id', 'not');
		$by = $vars['by'] == "DESC" ? "DESC" : "ASC";

		switch ($vars['orderby']) {
	        case "new":      $order_sql = " ORDER BY `id` $by"; break;
			default:$order_sql = " ORDER BY `id` $by";
		}

		isset($vars['startdate']) && $where_sql .= " AND `pubdate`>='" . strtotime($vars['startdate']) . "'";
		isset($vars['enddate']) && $where_sql .= " AND `pubdate`<='" . strtotime($vars['enddate']) . "'";
		isset($vars['where']) && $where_sql .= $vars['where'];

		$offset = (int)$vars['offset'];
		$limit = "LIMIT {$maxperpage}";
		if ($vars['page']) {
			$total_type = $vars['total_cache'] ? 'G' : null;
			$total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__ask_data` {$where_sql}", $total_type,iCMS::$config['cache']['page_total']);
			$pagenav    = isset($vars['pagenav']) ? $vars['pagenav'] : "pagenav";
			$pnstyle    = isset($vars['pnstyle']) ? $vars['pnstyle'] : 3;
			$multi      = iPagination::make(array(
				'total_type' => $total_type,
				'total'      => $total,
				'perpage'    => $maxperpage,
				'pnstyle'    => $pnstyle,
				'unit'       => iUI::lang('iCMS:page:list'),
				'nowindex'   => $GLOBALS['page']
			));
			$offset = $multi->offset;
			iView::assign("ask_data_total", $total);
		}
		$limit = "LIMIT {$offset},{$maxperpage}";

		$hash = md5(json_encode($vars) . $order_sql . $limit);

		if ($vars['cache']) {
			$cache_name = iPHP_DEVICE . '/ask_data/' . $hash;
			$resource = iCache::get($cache_name);
			if(is_array($resource)) return $resource;
		}

		if (empty($resource)) {
			$resource = iDB::all("SELECT * FROM `#iCMS@__ask_data` {$where_sql} {$order_sql} {$limit}");
			$ln = ($GLOBALS['page']-1)<0?0:$GLOBALS['page']-1;

    		$plugin = array(
				'markdown'         =>true,
				'htmlspecialchars' =>false,
    		);

			if($resource)foreach ($resource as $key => $value) {
				$value['content'] = userApp::at_content($value['content']);
				$value['content'] = preg_replace('@^&gt;\s@ism', '> ', $value['content']);
				// $value['content'] = iPHP::callback(array("plugin_markdown","HOOK"),array($value['content'],&$plugin));

				$value['user']    = user::info($value['userid'],$value['username'],$vars['facesize']);
				$value['total']   = $total;
				$vars['page'] && $value['page']  = array('total'=>$multi->totalpage,'perpage'=>$multi->perpage);

				if($vars['by']=='ASC'){
					$value['lou'] = $total-($key+$ln*$maxperpage);
				}else{
					$value['lou'] = $key+$ln*$maxperpage+1;
				}

		        $value['param'] = array(
					"appid"    => iCMS_APP_ASK,
					"id"       => $value['id'],
					"iid"      => $iid,
					"cid"      => 0,
					"rootid"   => $value['rootid'],
					"userid"   => $value['userid'],
					"username" => $value['username'],
					"title"    => $value['title'],
		        );
				$resource[$key] = $value;
			}
			$vars['cache'] && iCache::set($cache_name, $resource, $cache_time);
		}
		return $resource;
	}
	public static function ask_list($vars) {
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
		$hidden = categoryApp::get_cahce('hidden');
		$hidden && $where_sql .= iSQL::in($hidden, 'cid', 'not');
		$maxperpage = isset($vars['row']) ? (int) $vars['row'] : 10;
		$cache_time = isset($vars['time']) ? (int) $vars['time'] : -1;
		isset($vars['userid']) && $where_sql .= " AND `userid`='{$vars['userid']}'";
		isset($vars['weight']) && $where_sql .= " AND `weight`='{$vars['weight']}'";

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
				iMap::init('category', iCMS_APP_ASK,'cid');
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
		if (isset($vars['pids']) && !isset($vars['pid'])) {
			iMap::init('prop', iCMS_APP_ASK,'pid');
			$map_where += iMap::where($vars['pids']);
		}

		if (isset($vars['tids'])) {
			iMap::init('tag', iCMS_APP_ASK,($vars['field']?$vars['field']:'tags'));
			$_map_where = (array)iMap::where($vars['tids']);
			$map_where  = array_merge($map_where,$_map_where);
			unset($_map_where);
		}

		if (isset($vars['keywords'])) {
	//最好使用 iCMS:ask:search
			if (empty($vars['keywords'])) {
				return;
			}

			if (strpos($vars['keywords'], ',') === false) {
				$vars['keywords'] = str_replace(array('%', '_'), array('\%', '\_'), $vars['keywords']);
				$where_sql .= " AND CONCAT(title,keywords,description) like '%" . addslashes($vars['keywords']) . "%'";
			} else {
				$kws = explode(',', $vars['keywords']);
				foreach ($kws AS $kwv) {
					$keywords .= addslashes($kwv) . "|";
				}
				$keywords = substr($keywords, 0, -1);
				$where_sql .= " AND CONCAT(title,keywords,description) REGEXP '$keywords' ";
			}
		}

		$vars['id'] && $where_sql .= iSQL::in($vars['id'], 'id');
		$vars['id!'] && $where_sql .= iSQL::in($vars['id!'], 'id', 'not');
		$by = $vars['by'] == "ASC" ? "ASC" : "DESC";

		switch ($vars['orderby']) {
	        case "last":     $order_sql = " ORDER BY `lastpost` $by"; break;
	        case "new":      $order_sql = " ORDER BY `id` $by"; break;
	        case "id":       $order_sql = " ORDER BY `id` $by"; break;
	        case "score":    $order_sql = " ORDER BY `score` $by"; break;
	        case "hot":      $order_sql = " ORDER BY `hits` $by"; break;
	        case "week":     $order_sql = " ORDER BY `hits_week` $by"; break;
	        case "month":    $order_sql = " ORDER BY `hits_month` $by"; break;
	        case "comment":  $order_sql = " ORDER BY `comments` $by"; break;
	        case "pubdate":  $order_sql = " ORDER BY `pubdate` $by"; break;
	        case "sort": $order_sql = " ORDER BY `sortnum` $by"; break;
	        case "weight":   $order_sql = " ORDER BY `weight` $by"; break;
			default:$order_sql = " ORDER BY `id` $by";
		}
		isset($vars['startdate']) && $where_sql .= " AND `pubdate`>='" . strtotime($vars['startdate']) . "'";
		isset($vars['enddate']) && $where_sql .= " AND `pubdate`<='" . strtotime($vars['enddate']) . "'";
		isset($vars['where']) && $where_sql .= $vars['where'];

		if ($map_where) {
			$map_sql = iSQL::select_map($map_where, 'join');
			//join
			//empty($vars['cid']) && $map_order_sql = " ORDER BY map.`iid` $by";
			$map_table = 'map';
			$vars['map_order_table'] && $map_table = $vars['map_order_table'];
			$map_order_sql = " ORDER BY {$map_table}.`iid` $by";
			//$map_order_sql = " ORDER BY `icms_ask`.`id` $by";
			//
			$where_sql .= ' AND ' . $map_sql['where'];
			$where_sql = ",{$map_sql['from']} {$where_sql} AND `#iCMS@__ask`.`id` = {$map_table}.`iid`";
			//derived
			// $where_sql = ",({$map_sql}) map {$where_sql} AND `id` = map.`iid`";
		}
		$offset = 0;
		$limit = "LIMIT {$maxperpage}";
		if ($vars['page']) {
			$total_type = $vars['total_cache'] ? 'G' : null;
			$total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__ask` {$where_sql}", $total_type,iCMS::$config['cache']['page_total']);
			$pagenav    = isset($vars['pagenav']) ? $vars['pagenav'] : "pagenav";
			$pnstyle    = isset($vars['pnstyle']) ? $vars['pnstyle'] : 0;
			$multi      = iPagination::make(array('total_type' => $total_type, 'total' => $total, 'perpage' => $maxperpage, 'unit' => iUI::lang('iCMS:page:list'), 'nowindex' => $GLOBALS['page']));
			$offset     = $multi->offset;
			$limit      = "LIMIT {$offset},{$maxperpage}";
			iView::assign("ask_list_total", $total);
		}
		//随机特别处理
		if ($vars['orderby'] == 'rand') {
			$ids_array = iSQL::get_rand_ids('#iCMS@__ask', $where_sql, $maxperpage, 'id');
			if ($map_order_sql) {
				$map_order_sql = " ORDER BY `#iCMS@__ask`.`id` $by";
			}
		}
		$hash = md5($where_sql . $order_sql . $limit);
		if ($offset) {
			if ($vars['cache']) {
				$map_cache_name = iPHP_DEVICE . '/ask_page/' . $hash;
				$ids_array = iCache::get($map_cache_name);
			}
			if (empty($ids_array)) {
				$ids_order_sql = $map_order_sql ? $map_order_sql : $order_sql;
				$ids_array = iDB::all("SELECT `#iCMS@__ask`.`id` FROM `#iCMS@__ask` {$where_sql} {$ids_order_sql} {$limit}");
				$vars['cache'] && iCache::set($map_cache_name, $ids_array, $cache_time);
			}
		} else {
			if ($map_order_sql) {
				$order_sql = $map_order_sql;
			}
		}
		if ($ids_array) {
			$ids = iSQL::values($ids_array);
			$ids = $ids ? $ids : '0';
			$where_sql = "WHERE `#iCMS@__ask`.`id` IN({$ids})";
			$limit = '';
		}
		if ($vars['cache']) {
			$cache_name = iPHP_DEVICE . '/ask/' . $hash;
			$resource = iCache::get($cache_name);
		}

		if (empty($resource)) {
			$resource = iDB::all("SELECT `#iCMS@__ask`.*  FROM `#iCMS@__ask` {$where_sql} {$order_sql} {$limit}");
			$resource = askFunc::ask_array($vars, $resource);
			$vars['cache'] && iCache::set($cache_name, $resource, $cache_time);
		}
		return $resource;
	}
	public static function ask_search($vars) {
		if (empty(iCMS::$config['sphinx']['host'])) {
			return array();
		}

		$resource = array();
		$hidden = categoryApp::get_cahce('hidden');
		$hidden && $where_sql .= iSQL::in($hidden, 'cid', 'not');
		$SPH = iPHP::vendor('SPHINX',iCMS::$config['sphinx']['host']);
		$SPH->init();
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

		if (is_array($res["matches"])) {
			foreach ($res["matches"] as $docinfo) {
				$aid[] = $docinfo['id'];
			}
			$aids = implode(',', (array) $aid);
		}
		if (empty($aids)) {
			return;
		}

		$where_sql = " `id` in($aids)";
		$offset = 0;
		if ($vars['page']) {
			$total = $res['total'];
			iView::assign("ask_search_total", $total);
			$pagenav = isset($vars['pagenav']) ? $vars['pagenav'] : "pagenav";
			$pnstyle = isset($vars['pnstyle']) ? $vars['pnstyle'] : 0;
			$multi = iPagination::make(array('total' => $total, 'perpage' => $maxperpage, 'unit' => iUI::lang('iCMS:page:list'), 'nowindex' => $GLOBALS['page']));
			$offset = $multi->offset;
		}
		$resource = iDB::all("SELECT * FROM `#iCMS@__ask` WHERE {$where_sql} {$order_sql} LIMIT {$maxperpage}");
		$resource = askFunc::ask_array($vars, $resource);
		return $resource;
	}
	public static function ask_prev($vars) {
		$vars['order'] = 'p';
		return askFunc::ask_next($vars);
	}
	public static function ask_next($vars) {
		// if($vars['param']){
		//     $vars+= $vars['param'];
		//     unset($vars['param']);
		// }
		empty($vars['order']) && $vars['order'] = 'n';

		$cache_time = isset($vars['time']) ? (int) $vars['time'] : -1;
		if (isset($vars['cid'])) {
			$sql = " AND `cid`='{$vars['cid']}' ";
		}
		if ($vars['order'] == 'p') {
			$sql .= " AND `id` < '{$vars['id']}' ORDER BY id DESC LIMIT 1";
		} else if ($vars['order'] == 'n') {
			$sql .= " AND `id` > '{$vars['id']}' ORDER BY id ASC LIMIT 1";
		}
		$hash = md5($sql);
		if ($vars['cache']) {
			$cache = iPHP_DEVICE . '/ask/' . $hash;
			$array = iCache::get($cache);
		}
		if (empty($array)) {
			$rs = iDB::row("SELECT * FROM `#iCMS@__ask` WHERE `status`='1' {$sql}");
			if ($rs) {
				$category = categoryApp::get_cahce_cid($rs->cid);
				$array = array(
					'title' => $rs->title,
					'url' => iURL::get('ask', array((array) $rs, $category))->href,
				);
			}
			$vars['cache'] && iCache::set($cache, $array, $cache_time);
		}
		return $array;
	}
	public static function ask_array($vars, $resource) {
		if ($resource) {
	        if($vars['meta']){
	            $aidArray = iSQL::values($resource,'id','array',null);
				$aidArray && $meta_data = (array)apps_meta::data('ask',$aidArray);
	            unset($aidArray);
	        }

	        if($vars['tags']){
	            $tagArray = iSQL::values($resource,'tags','array',null,'id');
				$tagArray && $tags_data = (array)tagApp::multi_tag($tagArray);
	            unset($tagArray);
	            $vars['tag'] = false;
	        }

			foreach ($resource as $key => $value) {
				$value = askApp::value($value, false, $vars);

				if ($value === false) {
					continue;
				}

	            if($vars['tags'] && $tags_data){
	                $value+= (array)$tags_data[$value['id']];
	            }

	            if($vars['meta'] && $meta_data){
	                $value+= (array)$meta_data[$value['id']];
	            }

				if ($vars['page']) {
					$value['page'] = $GLOBALS['page'] ? $GLOBALS['page'] : 1;
					$value['total'] = $total;
				}

				$resource[$key] = $value;
			}
		}
		return $resource;
	}
}
