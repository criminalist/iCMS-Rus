<?php

defined('iPHP') OR exit('Oops, something went wrong');

return array(
	'empty_id'=>'Идентификатор пользователя не может быть пустым!',
	'not_found'=>'Пользователь не найден [uid:%d]',
	'profile'=>array(
		'success'     =>'Успешно изменено!',
		'avatar'      =>'头像上传成功!',
		'custom'      =>'更新封面成功!',
		'personstyle' =>'多个标签之间请用逗号隔开',
		'slogan'      =>"随便写点什么,让大家了解你吧.",
		'pskin'       =>"请选择",
		'phair'       =>"请选择",
		'unickEdit'	  =>"你已经修改过昵称了!",
		'nickname'	  =>"昵称已存在,请换个再试试.",
	),
	'follow'=>array(
		'success' =>'已关注!',
		'failure' =>'关注失败!',
		'self'    =>'不能关注自己!',
		'text1'   =>' 也关注Ta',
		'text2'   =>' 等 %d 人也关注Ta',
	),
	'login'=>array(
		'def_uname' =>'Электронная почта или логин',
		'same_ip'  	=>'同一个IP 登陆间隔不能少于%s!',
		'interval'  =>'您的账号已经连续5次登陆错误,该账号已被暂时锁定!请%s后再试!',
		'error'     =>'Неверное имя пользователя или пароль!',
		'forbidden' =>'系统已关闭登陆功能!',
	),
	'category'=>array(
		'empty'   =>'请输入分类名称!',
		'filter'  =>'分类名称包含被系统屏蔽的字符,введите еще раз!',
		'max'     =>'最多只能创建10个分类!',
		'success' =>'添加成功!',
		'update'  =>'更新成功!',
	),
	'article'=>array(
		'add_success'    =>'文章发表完成!',
		'add_examine'    =>'文章发表完成!<br />本栏目需要审核后才能正常显示.',
		'update_success' =>'文章更新完成!',
		'update_examine' =>'文章更新完成!<br />本栏目需要审核后才能正常显示.',
	),
	'publish'=>array(
		'empty'    => array(
			'title' =>'Заголовок не может быть пустым!',
			'cid'   =>'Выберите категорию!',
			'body'  =>'文章内容不能为空!',
		),
		'filter_title' =>'标题中包含被系统屏蔽的字符,请重新填写.',
		'filter_desc'  =>'简介中包含被系统屏蔽的字符,请重新填写.',
		'filter_body'  =>'内容中包含被系统屏蔽的字符,请重新填写.',
		'interval'     =>'您发贴的速度太快了,请休息下吧.',
	),
	'findpwd'=>array(
		'subject' =>'[%s] 找回密码(重要)!',
		'body' =>'
            <p>尊敬的%s,您好:</p>
            <br />
            <p>您在%s申请找回密码,重设密码地址:</p>
            <a href="%s" target="_blank">%s</a>
            <p>本链接将在24小时后失效!</p>
            <p>如果上面的链接无法点击,您也可以复制链接,粘贴到您浏览器的地址栏内,然后按“回车”打开重置密码页面.</p>
            <p>如果您有其他问题,请联系我们:%s.</p>
            <p>如果您没有进行过找回密码的操作,请不要点击上述链接,并删除此邮件.</p>
            <p>谢谢!</p>',
		'success' =>'您的密码已经修改成功!请重新登陆.',
		'error'   =>'您的链接已经过时,请重新申请找回密码.',
		'same'    =>'您的新密码与旧密码一样.请重新设置新密码.',
		'send'    => array(
			'success' =>'重设密码的邮件发送成功!请登陆您的邮箱查收相关邮件.',
			'failure' =>'重设密码的邮件发送失败请稍后在重试.',
		),
		'username'=> array(
			'empty'   =>'Пожалуйста, заполните адрес электронной почты!',
			'noexist' =>'邮件地址不存在,请换个邮件再试试.',
		),
	),
	'register' => array(
		'forbidden'=>'系统已经关闭注册功能!',
		'interval' =>'同一个IP在{time}时间内只能注册一个账号!',
		'nickname'=> array(
			'filter'=>'昵称中包含被系统屏蔽的字符,请重新填写.',
			'empty'=>'Введите имя пользователя!',
			'error'=>'Имя пользователя может состоять от 4 до 20 символов.',
			'exist'=>'昵称已经被注册了,请换个再试试.',
		),
		'username'=> array(
			'empty'=>'Пожалуйста, заполните адрес электронной почты!',
			'error'=>'Неверный формат электронной почты!',
			'exist'=>'邮件地址已经注册过了,请直接登陆或者换个邮件再试试.',
		),
	),
	'password'=> array(
		'original'  =>'原密码错误!',
		'modified'  =>'Успешно изменено!',
		'empty'     =>'Введите пароль!',
		'new'       =>'请填写新的密码!',
		'rst_empty' =>'Введите ваш пароль еще раз!',
		'error'     =>'密码太短啦,至少要6位哦',
		'unequal'   =>'密码与确认密码不一致!',
	),
);