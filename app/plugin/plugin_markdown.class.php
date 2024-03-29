<?php

class plugin_markdown {
    /**
     * [Плагин: визуальный редактор markdown]
     * @param [type] $content  [Параметры]
     */
    public static function HOOK($content,&$resource=null) {
        plugin::init(__CLASS__);
        if($resource['markdown']==="1"){
            $content = plugin_download::markdown($content);

            plugin::library('Parsedown');
            $Parsedown = new Parsedown();
            $Parsedown->setBreaksEnabled(true);
            $content = str_replace(array(
                '#--' . iPHP_APP . '.Markdown--#',
                '#--' . iPHP_APP . '.PageBreak--#',
            ), array('', '@--' . iPHP_APP . '.PageBreak--@'), $content);
            $content = $Parsedown->text($content);
            $content = str_replace('@--' . iPHP_APP . '.PageBreak--@', '#--' . iPHP_APP . '.PageBreak--#', $content);
        }
        return $content;
    }
}

