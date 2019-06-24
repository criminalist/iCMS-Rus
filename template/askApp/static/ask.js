var utils = iCMS.require("utils");

function SIMPLEMDE_UPLOAD_INPUT(c) {
    if (c.code) {
        iCMS.$('SIMPLEMDE_UPLOAD_INPUT').val(c.url);
    }
}

function md_editor_init(e) {
    return new SimpleMDE({
        autoDownloadFontAwesome: false,
        autofocus: false,
        spellChecker: false,
        status: false,
        promptURLs: true,
        element: e[0],
        // mobile:true,
        onDrawImage: function(ed, cb) {
            var title = '上传图片/附件',
                box = document.getElementById("iCMS-SIMPLEMDE-DIALOG"),
                $box = $(box),
                dialog = iCMS.UI.dialog({
                    id: 'iCMS-MD-DIALOG',
                    title: title,
                    content: box,
                    quickClose: false,
                    width: "auto",
                    height: "auto",
                    okValue: "确 认",
                    ok: function() {
                        var input = iCMS.$('SIMPLEMDE_UPLOAD_INPUT', $box);
                        cb(input.val());
                        input.val("");
                        iCMS.$('SIMPLEMDE_UPLOAD_FORM', $box)[0].reset();
                    },
                    cancelValue: "取 消",
                    cancel: function() {
                        return true;
                    }
                });

            iCMS.$('SIMPLEMDE_UPLOAD_BTN', $box).click(function() {
                $("input[name=upfile]", $box).click();
            })
            $("input[name=upfile]", $box).change(function() {
                iCMS.$('SIMPLEMDE_UPLOAD_FORM', $box).submit();
            })
        }
    });
}

function question_dialog(title, callback) {
    var $box = $("#iCMS-QUESTION-DIALOG");
    var ask_question_ed = md_editor_init(iCMS.$('ask_question_content', $box));
    var dialog = iCMS.UI.dialog({
        title: title || '提问?',
        content: $box[0],
        quickClose: false,
        width: "auto",
        height: "auto"
    }, function(type) {
        //console.log(type);
        if (type == 'remove') {
            ask_question_ed.toTextArea();
            ask_question_ed = null;
        }
    });
    if (typeof(callback) === "function") {
        callback($box, ask_question_ed);
    }
    $box.on('click', '[i="ask_question_cancel"]', function(event) {
        event.preventDefault();
        dialog.remove();
    }).on('click', '[i="ask_question_add"]', function(event) {
        event.preventDefault();
        if (!iUSER.CHECK.LOGIN()) return;

        var param = {
            'id': iCMS.$('ask_question_id', $box).val(),
            '_tags': iCMS.$('ask_question__tags', $box).val(),
            '_cid': iCMS.$('ask_question__cid', $box).val(),
            "auth": iCMS.$('ask_question_auth', $box).val()
        };

        param.cid = iCMS.$('ask_question_cid', $box).val();

        if (param.cid == "0") {
            iCMS.UI.alert("请选择分类");
            return false;
        }
        var ask_title = iCMS.$('ask_question_title', $box);
        param.title = ask_title.val();
        if (!param.title) {
            iCMS.UI.alert("请内容的主题");
            ask_title.focus();
            return false;
        }
        var ask_tags = iCMS.$('ask_question_tags', $box);
        param.tags = ask_tags.val();

        // var ask_content = iCMS.$('ask_question_content', $box);
        // param.content = ask_content.val();
        // if (param.content=="") {
        //     iCMS.UI.alert("写下你的问题…");
        //     ask_content.focus();
        //     return false;
        // }
        //
        param.content = ask_question_ed.value();
        if (param.content == "") {
            iCMS.UI.alert("写下你的问题…");
            ask_question_ed.focus();
            return false;
        }
        var ask_seccode = iCMS.$('ask_question_seccode', $box);
        param.seccode = ask_seccode.val();
        if (param.seccode == "") {
            iCMS.UI.alert("请输入验证码");
            ask_seccode.focus();
            return false;
        }

        var refresh = function(ret) {
            if (ret.forward != 'seccode') {
                ask_question_ed.value('');
                ask_title.val('');
                ask_tags.selectpicker('deselectAll');
                dialog.remove();
            }
            if (typeof(ask_seccode) !== "undefined") {
                ask_seccode.val('');
                iCMS.UI.seccode();
            }
        }
        console.log(param);
        var me = this;
        param.action = 'add_question';
        $.post(iCMS.API.url('ask'), param, function(ret) {
            refresh(ret);
            utils.callback(ret, function() {
                if (ret.forward) {
                    window.location.href = ret.forward;
                } else {
                    window.location.reload();
                }
            }, function() {
                iCMS.UI.alert(ret.msg);
            }, me);
        }, 'json');
    });
    return $box;
}
$(function() {
    var doc = $(document),
        $form = $('#askApp-answer-form');
    var ask_answer_ed = md_editor_init(iCMS.$('ask_answer_content', $form));

    doc.on('click', '[i="ask_answer_reply"]', function(event) {
        event.preventDefault();
        if (!iUSER.CHECK.LOGIN()) return;

        // var ask_content = iCMS.$('ask_answer_content', $form);
        // var content = ask_content.val();
        // var param = iCMS.API.param(this);
        // if(typeof(param.username)!=='undefined'){
        //     ask_content.val(content+' @'+param.username+' ');
        // }
        // var pos = ask_content.offset();
        // window.scrollTo(0, pos.top);
        // ask_content.focus();
        //

        var param = iCMS.API.param(this);
        var at = '';
        if (typeof(param.username) !== 'undefined') {
            at = ' @' + param.username + ' ';
        }
        ask_answer_ed.insert(at);
        var ask_anchor = $('a[name="_ask_anchor"]', $form).offset();
        window.scrollTo(0, ask_anchor.top);

    }).on('click', '[i="ask_answer_edit"]', function(event) {
        event.preventDefault();
        if (!iUSER.CHECK.LOGIN()) return;

        var me = this,
            param = iCMS.API.param(this);
        if (param.rootid > 0) {
            var ask_anchor = $('a[name="_ask_anchor"]', $form).offset();
            window.scrollTo(0, ask_anchor.top);
            ask_answer_ed.value("内容加载中.....");
            param.action = 'get_answer';
        } else {
            param.action = 'get_question';
        }

        $.post(iCMS.API.url('ask'), param, function(ret) {
            if (ret.code) {
                if (param.rootid > 0) {
                    ask_answer_ed.value(ret.content);
                    iCMS.$('ask_answer_iid', $form).val(ret.iid);
                    iCMS.$('ask_answer_id', $form).val(param.id);
                } else {
                    question_dialog('编辑问题', function($box, ed) {
                        iCMS.$('ask_question_id', $box).val(param.iid);
                        iCMS.$('ask_question_cid', $box).val(ret.cid);
                        iCMS.$('ask_question__cid', $box).val(ret.cid);
                        iCMS.$('ask_question_title', $box).val(ret.title);
                        iCMS.$('ask_question_tags', $box).selectpicker('val', ret.tags.split(','));
                        iCMS.$('ask_question__tags', $box).val(ret.tags);
                        ed.value(ret.content);
                    });
                }
            }
        }, 'json');

    }).on('click', '[i="ask_answer_delete"]', function(event) {
        event.preventDefault();
        if (!iUSER.CHECK.LOGIN()) return;
        if (!confirm('确定要删除?')) return;

        var me = this,
            param = iCMS.API.param(this);
        param.action = 'delete';
        $.post(iCMS.API.url('ask'), param, function(ret) {
            utils.callback(ret, function(ret) {
                if (ret.forward && param.rootid) {
                    window.location.href = ret.forward;
                } else {
                    $('.answer-item[data-id="' + param.id + '"]').remove();
                }
            }, function() {
                iCMS.UI.alert(ret.msg);
            }, me);
        }, 'json');

    }).on('click', '[i="ask_answer_up"]', function(event) {
        event.preventDefault();
        if (!iUSER.CHECK.LOGIN()) return;

        var me = this,
            param = iCMS.API.param(this);
        param.action = 'vote';
        $.post(iCMS.API.url('ask'), param, function(ret) {
            utils.callback(ret, function(ret) {
                var p = $(me).parent(),
                    num = iCMS.$('ask_answer_up_num', p).text();

                num = parseInt(num) + 1;
                iCMS.$('ask_answer_up_label', p).show();
                iCMS.$('ask_answer_up_num', p).text(num);
            }, function() {
                iCMS.UI.alert(ret.msg);
            }, me);
        }, 'json');
    }).on('click', '[i="ask_answer_cancel"]', function(event) {
        // 取消
        var pp = $(this).parent().parent();
        iCMS.$('ask_answer_content', pp).val("").focus();
    }).on('click', '[i="ask_answer_add"]', function(event) {
        //提交评论
        event.preventDefault();
        if (!iUSER.CHECK.LOGIN()) return;

        var param = {
            "id": iCMS.$('ask_answer_id', $form).val(),
            "iid": iCMS.$('ask_answer_iid', $form).val(),
            "title": iCMS.$('ask_answer_title', $form).val(),
            "auth": iCMS.$('ask_answer_auth', $form).val()
        };

        var ask_seccode = iCMS.$('ask_answer_seccode', $form);
        param.seccode = ask_seccode.val();
        if (!param.seccode) {
            iCMS.UI.alert("请输入验证码");
            ask_seccode.focus();
            return false;
        }
        // var ask_content = iCMS.$('ask_answer_content', $form);
        // param.content = ask_content.val();
        param.content = ask_answer_ed.value();
        if (!param.content) {
            iCMS.UI.alert("写下你的评论…");
            ask_answer_ed.focus();
            // ask_content.focus();
            return false;
        }
        var refresh = function(ret) {
            if (ret.forward != 'seccode') {
                // ask_content.val('');
            }
            if (typeof(ask_seccode) !== "undefined") {
                ask_seccode.val('');
                iCMS.UI.seccode();
            }
        }

        var me = this;
        param.action = 'add_answer';
        $.post(iCMS.API.url('ask'), param, function(ret) {
            refresh(ret);
            utils.callback(ret, function() {
                // console.log('SUCCESS');
                window.location.reload();
            }, function() {
                iCMS.UI.alert(ret.msg);
            }, me);
        }, 'json');
    }).on('click', '[i="ask_add_question"]', function(event) {
        event.preventDefault();
        if (!iUSER.CHECK.LOGIN()) return;

        question_dialog();
    });
})
