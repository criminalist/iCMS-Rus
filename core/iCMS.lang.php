<?php
defined('iPHP') OR exit('Oops, something went wrong');

return array(
	'not_found' =>'Не найдено соответствий<b>%s:%s</b>',
	'!login'    =>'Требуется авторизация на сайте!',
	'error'     =>'哎呀呀呀!非常抱歉,居然出错了!<br />请稍候再试试,我们的程序猿正在努力修复中...',
	'clicknext' =>'Нажмите на картинку, чтобы перейти на следующую страницу',
	'first'     =>'已经是第一篇',
	'last'      =>'已经是最后一篇',
	'empty_id'  =>'ID不能为空',
	'!good'     =>'Ваш голос уже зачтен!',
	'good'      =>'谢谢您的赞,我会更加努力的',
	'!bad'      =>'您已经过踩了啦!',
	'bad'       =>'您已经过踩了啦!',

	'page'   =>array(
		'index'        =>'Главная',
		'prev'         =>'Предыдущая страница',
		'next'         =>'Следующая страница',
		'last'         =>'Последняя страница',
		'other'        =>'Всего',
		'unit'         =>'页',
		'list'         =>'篇文章',
		'sql'          =>'条记录',
		'tag'          =>'个标签',
		'comment'      =>'条评论',
		'item'  	   =>'',
		'di'           =>'第',
	),
	'report'=>array(
		'empty'   =>'Напишите о нарушении!',
		'success' =>'Спасибо за ваше обращение!',
	),
	'pm'=>array(
		'empty'   =>'请填写私信内容.',
		'success' =>'发送成功!',
		'nofollow'=>'发送失败!您无法给对方发送私信!',
	),
	'favorite'=>array(
		'create_empty'   =>'请输入标题!',
		'create_filter'  =>'您输入的内容中包含被系统屏蔽的字符,введите еще раз!',
		'create_max'     =>'最多只能创建10个收藏夹!',
		'create_success' =>'添加成功!',
		'create_failure' =>'添加失败!',
		'update'  =>'更新成功!',
		'url'     =>'URL不能为空',
		'success' =>'收藏成功!',
		'failure' =>'您已经收藏过了!',
		'error' =>'收藏失败!',
	),
	'comment'=> array(
		'empty_id'=>'ID не может быть пустым',
		'close'   =>'Комментарии закрыты!',
		'empty'   =>'请输入内容!',
		'success' =>'感谢您的评论!',
		'examine' =>'您的评论已经提交,请等待管理审核通过后方可显示 !',
		'!like'   =>'Ваш голос уже зачтен!',
		'like'    =>'谢谢您的赞',
		'filter'  =>'评论内容中包含被系统屏蔽的字符,请重新填写.',
	),
	'seccode'=> array(
		'empty'=>'Введите защитный код!',
		'error'=>'Неверный защитный код, попробуйте еще раз.',
	),
	'file'=> array(
		'invaild'=>'Запрещенные символы',
		'failure'=>'Тип файла не поддерживается системой',
	),
	
	'navTag'=>'»',
);
