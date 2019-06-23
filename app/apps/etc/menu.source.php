<?php

defined('iPHP') OR exit('What are you doing?');

return '[{
    "id": "{app}",
    "caption": "{name}",
    "icon": "pencil-square-o",
    "children": [{
        "caption": "Обновить кеш категорий",
        "href": "{app}_category&do=cache",
        "icon": "refresh",
        "target":"iPHP_FRAME"
    }, {
        "caption": "-"
    }, {
        "caption": "Управление категориями",
        "href": "{app}_category",
        "icon": "list-alt"
    }, {
        "caption": "Добавить категорию",
        "href": "{app}_category&do=add",
        "icon": "edit"
    }, {
        "caption": "-"
    }, {
        "caption": "Добавить {title}",
        "href": "{app}&do=add",
        "icon": "edit"
    }, {
        "caption": "Управление {title}",
        "href": "{app}&do=manage",
        "icon": "list-alt"
    }, {
        "caption": "Пользовательские публикации",
        "href": "{app}&do=inbox",
        "icon": "inbox"
    }, {
        "caption": "Корзина",
        "href": "{app}&do=trash",
        "icon": "trash-o"
    }, {
        "caption": "-"
    }, {
        "caption": "用户{title}管理",
        "href": "{app}&do=user",
        "icon": "check-circle"
    }, {
        "caption": "审核用户{title}",
        "href": "{app}&do=examine",
        "icon": "minus-circle"
    }, {
        "caption": "淘汰的{title}",
        "href": "{app}&do=off",
        "icon": "times-circle"
    }, {
        "caption": "-"
    }, {
        "caption": "{title}评论管理",
        "href": "comment&appname={app}&appid={appid}",
        "icon": "comments"
    }]
}]';
