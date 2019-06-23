<?php
/**iFormer Form Builder**/

class former {
    public static $html     = array();
    public static $layout   = array();
    public static $validate = null;
    public static $script   = null;
    public static $error    = null;

    public static $prefix   = null;
    public static $config   = array();

    public static $callback   = array();
    public static $variable   = array();

    public static $template   = array(
        'widget'=>array(
            'text' => 'form-control',
        ),
        'class'=>array(
            'group'    => 'input-group',
            'input'    => 'form-control',
            'label'    => 'input-group-addon',
            'label2'   => 'input-group-addon',
            'help'     => 'help-inline',
            'radio'    => 'form-control',
            'checkbox' => 'form-control',
        ),
        'group'=>'
            <div id="{{id}}" class="{{class_group}} {{class}}">
                {{label}}
                {{input}}
                {{label2}}
            </div>
            {{help}}
            {{script}}
        ',
        'input'    => '{{content}}',
        'label'    => '<span class="{{class_label}}">{{content}}</span>',
        'label2'   => '<span class="{{class_label2}}">{{content}}</span>',
        'help'     => '<span class="{{class_help}}">{{content}}</span>',
        'radio'    => '<span class="{{class_radio}}">{{content}}</span>',
        'checkbox' => '<span class="{{class_checkbox}}">{{content}}</span>',
    );
    /**
     * [Создать форму]
     * @param  [type]  $app        [Данные приложения]
     * @param  [type]  $rs         [Данные]
     * @return [type]              [description]
     */
    public static function create($app,$rs=null){
        self::$config['app'] = $app;
        self::render($app,$rs);
    }
    public static function multi_value($rs,$fieldArray) {
        foreach ($fieldArray as $key => $field) {
            if(in_array($field['type'], array('category','multi_category','prop','multi_prop'))){
                $value = iSQL::values($rs,$field['name'],'array',null);
                $value = iSQL::explode_var($value);
                $call  = self::$callback[$field['type']];
                if($call && is_callable($call)){
                    self::$variable[$field['name']] = call_user_func_array($call, array($value));
                }
            }
        }
        return self::$variable;
    }
    /**
     * Преобразовать массив строк запроса в двумерный массив
     * @param  [type]  $data
     * @param  boolean $ui
     * @return [type]        [description]
     */
    public static function fields($data,$ui=false) {
        $array = array();
        foreach ($data as $key => $value) {
          $output = array();
          if($value=='UI:BR'){
              $ui && $output = array('type'=>'br');
          }else{
              parse_str($value,$output);
          }
          $output && $array[$key] = $output;
        }
        return $array;
    }
    public static function base_fields_merge(&$app, array $data_table) {
        // if($data_table===null){
        //     $dtn = apps_mod::data_table_name($app['app']);
        //     $data_table = $app['table'][$dtn];
        // }
        $data_fields = apps_mod::data_base_fields($app['app']);
        $primary_key = $data_table['primary'];
        $union_key   = $data_table['union'];
        $fpk = $data_fields[$primary_key];
        $fpk && $app['fields']+= array($primary_key=>$fpk);
        $fuk = $data_fields[$union_key];
        $fuk && $app['fields']+= array($union_key=>$fuk);
    }

    public static function widget($name,$attr=null) {
        return former_helper::widget($name,$attr);
    }

    public static function render($app,$rs=null) {
        $fields = self::fields($app['fields'],true);
        $rs = (array)$rs;
        foreach ($fields as $fkey => $field) {
            $value = $rs[$field['name']];
            $rs===null && $value = null;
            self::html($field,$value,$fkey);
            self::validate($field);
        }
    }
    public static function html($field,$value=null,$fkey=null) {
        if($field['type']=='br'){
            $id  = $fkey;
            $div = self::widget("div")->addClass("clearfloat mt10");
        }else{
            $id      = $field['id'];
            $name    = $field['name'];
            $class   = $field['class'];
            $default = $field['default'];

            list($type,$_type) = explode(':', $field['type']);
            if($value!==null){
                // isset($value) OR $value = $default;
                if(!isset($value)){
                    if(in_array($field['field'], array('BIGINT','INT','MEDIUMINT','SMALLINT','TINYINT'))){
                        $value ='0';
                    }else{
                        $value ='';
                    }
                }
            }
            if($default && $value===null){
                $value = $default;
            }
            $value = self::field_value($value,$type);

            $field['label']      && $label  = self::display($field['label'],'label');
            $field['help']       && $help   = self::display($field['help'],'help');
            $field['label-after']&& $label2 = self::display($field['label-after'],'label2');

            $attr = compact(array('id','name','type','class','value'));
            // $attr['id']   = self::$prefix.'_'.$id.'';
            // $attr['name'] = self::$prefix.'['.$name.']';
            // $orig_name = self::$prefix.'[_orig_'.$name.']';
            $attr['id']   = self::id($id);
            $attr['name'] = self::name($name);
            $orig_name    = self::name($name,'_orig_');

            $field['holder'] && $attr['placeholder'] = $field['holder'];
            self::$template['class']['input'] && $attr['class'].=' '.self::$template['class']['input'];

            if ($type == 'multi_image' || $type == 'multi_file') {
                unset($attr['value']);
                $input = self::widget('input',$attr);
            }else{
                $input = self::widget('input',$attr);
                $input->val($value);
            }

            switch ($type) {
                case 'multi_image':
                case 'multi_file':
                    // $form_group.=' input-append';
                    $input->attr('type','text');
                    $input->attr('placeholder','Мультизагрузка файлов');
                    $input->attr('readonly','readonly');
                    if(self::$config['gateway']=='admincp'){
                        $picbtn = filesAdmincp::set_opt($value, false)->pic_btn($attr['id'],null,($type=='multi_file'?'Файл':'Изображение'),true,true);
                    }
                    $input.= $picbtn;
                break;
                case 'image':
                case 'file':
                    // $form_group.=' input-append';
                    $input->attr('type','text');
                    if(self::$config['gateway']=='admincp'){
                        $picbtn = filesAdmincp::set_opt($value)->pic_btn($attr['id'],null,($type=='file'?'Файл':'Изображение'),true);
                    }
                    $input.= $picbtn;
                break;
                case 'tpldir':
                case 'tplfile':
                    // $form_group.=' input-append';
                    $input->attr('type','text');
                    if(self::$config['gateway']=='admincp'){
                        $click ='file';
                        $type=='tpldir' && $click = 'dir';
                        $modal = filesAdmincp::modal_btn($name,$attr['id'],$click);
                    }
                    $input.= $modal;
                break;
                case 'txt_prop':
                    // $form_group.=' input-append';
                    $input->attr('type','text');
                    if(self::$config['gateway']=='admincp'){
                        $prop = propAdmincp::btn_group($name,$attr['id'],self::$config['app']['app']);
                        $prop.= propAdmincp::btn_add('Добавить'.$field['label'],$name,self::$config['app']['app']);
                    }
                    $input.= $prop;
                break;
                case 'prop':
                case 'multi_prop':
                    unset($attr['type']);
                    $attr['data-placeholder']= 'Выберите '.$field['label'].'...';
                    if(strpos($type,'multi')!==false){
                        $attr['name']     = $attr['name'].'[]';
                        $attr['multiple'] = 'true';
                        $attr['data-placeholder']= 'Выберите '.$field['label'].' (множественный выбор)...';
                        $orig = self::widget('input',array('type'=>'hidden','name'=>$orig_name,'value'=>$value));
                    }
                    $btn = propAdmincp::btn_add('Добавить'.$field['label'],$name,self::$config['app']['app']);
                    $select = self::widget('select',$attr)->addClass('chosen-select');
                    if(self::$config['gateway']=='admincp'){
                        $option='<option value="0">По умолчанию '.$field['label'].'['.$name.'=\'0\']</option>';
                    }else{
                        $option='<option value="0">По умолчанию '.$field['label'].'</option>';
                    }
                    if(propAdmincp::$default['option'][$name]){
                        $option.= propAdmincp::$default['option'][$name];
                    }
                    $option.= propAdmincp::get($name,null,'option',null,self::$config['app']['app'],self::$config['option']);
                    $value===null OR $script = self::script('iCMS.FORMER.select("'.$attr['id'].'","'.$value.'");',true);
                    $input = $select->html($option).$orig.$btn;
                break;
                case 'date':
                case 'datetime':
                    $attr['class'].= ' ui-datepicker';
                    $attr['type'] = 'text';
                    $input = self::widget('input',$attr);
                    $input->val($value);
                break;
                case 'user_category':
                    if(self::$config['gateway']=='admincp'){
                        $form_group=' former_hide';
                        $input->attr('type','hidden');
                    }
                break;
                case 'PRIMARY':
                case 'union':
                case 'hidden':
                    $form_group=' former_hide';
                    $input->attr('type','hidden');
                break;
                case 'userid':
                    $form_group=' former_hide';
                    $value OR $value = self::$config['value']['userid'];
                    $input->attr('type','text');
                    $input->val($value);
                break;
                case 'nickname':
                case 'username':
                    $value OR $value = self::$config['value'][$type];
                    $input->attr('type','text');
                    $input->val($value);
                break;
                case 'tag':
                    $input = $input->attr('type','text')->attr('onkeyup',"javascript:this.value=this.value.replace(/,/ig,',');");
                    $orig = self::widget('input',array('type'=>'hidden','name'=>$orig_name,'value'=>$value));
                    $input.= $orig;
                break;
                case 'number':
                    $input->attr('type','text');
                break;
                case 'text':break;
                case 'seccode':
                    $input->addClass('seccode')->attr('maxlength',"4")->attr('type','text');
                    $help = publicApp::seccode();
                break;
                case 'editor':
                    if(self::$config['gateway']=='admincp'){
                        // $label         = null;
                        $attr['class'] = 'editor-body';
                        $attr['type']  = 'text/plain';
                        $attr['id']    = 'editor-body-'.$attr['id'];
                        $form_group    = 'editor-container';
                        $script        = editorAdmincp::ueditor_script($attr['id'],self::$config);
                    }
                    $input = self::widget('textarea',$attr);
                break;
                case 'markdown':
                    if(self::$config['gateway']=='admincp'){
                        $attr['class'] = 'editor-body';
                        $attr['type']  = 'text/plain';
                        // $attr['id']    = 'editor-body-'.$attr['id'];
                        $form_group    = 'editor-container';
                        $form_group_id = 'editor-body-'.$attr['id'];
                        $script        = editorAdmincp::markdown_script($form_group_id,self::$config);
                    }
                    $input = self::widget('textarea',$attr);
                break;
                case 'multitext':
                    unset($attr['type']);
                    $input = self::widget('textarea',$attr);
                    $input->css('height','300px');
                break;
                case 'textarea':
                    unset($attr['type']);
                    $input = self::widget('textarea',$attr);
                    $input->css('height','150px');
                break;
                case 'switch':
                case 'radio':
                case 'checkbox':
                case 'radio_prop':
                case 'checkbox_prop':
                    if($type=='checkbox'||$type=='checkbox_prop'){
                        $attr['name'] = $attr['name'].'[]';
                    }
                    $btn = '';
                    if($type=='radio_prop'||$type=='checkbox_prop'){
                        $attr['type']    = str_replace('_prop', '', $type);
                        $propArray       = propAdmincp::get($name,null,'array',null,self::$config['app']['app'],self::$config['option']);
                        if($propArray){
                            $field['option'] = array_flip($propArray);
                        }else{
                            $field['option'] = 'По умолчанию '.$field['label'].'=0;';
                        }
                        $btn = propAdmincp::btn_add('Добавить'.$field['label'],$name,self::$config['app']['app']);
                    }

                    $field['option'] && $input  = former_helper::option($field['option'],$attr);
                    $input = self::display($input,$attr['type']);
                    $value===null OR $script= self::script('iCMS.FORMER.checked(".'.$attr['id'].'","'.$value.'");',true);
                    if($type=='switch'){
                        $attr['type'] = 'checkbox';
                        $input = self::widget('input',$attr);
                        $input ='<div class="switch">'.$input.'</div>';
                    }
                    $input.= $btn;
                break;
                case 'multi_category':
                case 'category':
                    unset($attr['type']);
                    $attr['data-placeholder']= 'Выберите '.$field['label'].'...';
                    if(strpos($type,'multi')!==false){
                        $attr['name']     = $attr['name'].'[]';
                        $attr['multiple'] = 'true';
                        $attr['data-placeholder']= 'Выберите '.$field['label'].'  (множественный выбор)...';
                        $orig = self::widget('input',array('type'=>'hidden','name'=>$orig_name,'value'=>$value));
                    }
                    $select = self::widget('select',$attr)->addClass('chosen-select');
                    $option = category::appid(category::$appid,'cs')->select();
                    $value===null OR $script = self::script('iCMS.FORMER.select("'.$attr['id'].'","'.$value.'");',true);
                    $input = $select->html($option).$orig;
                break;
                case 'multiple':
                case 'select':
                    // $attr['class'] = $field['class'];
                    $attr['data-placeholder']= 'Выберите '.$field['label'].'...';
                    if(strpos($type,'multi')!==false){
                        unset($attr['type']);
                        $attr['multiple'] = 'true';
                        $attr['name']     = $attr['name'].'[]';
                        $attr['data-placeholder']= 'Выберите '.$field['label'].' (множественный выбор)...';
                        $_input = self::widget('input',array('type'=>'hidden','name'=>$orig_name,'value'=>$value));
                    }
                    $input  = self::widget('select',$attr)->addClass('chosen-select');
                    $option = '';
                    if(self::$config['gateway']=='admincp'){
                        $option.='<option value=""></option>';
                    }
                    if($field['option']){
                        $option = former_helper::s_option($field['option'],$name);
                        $input->html($option);
                        $value===null OR $script = self::script('iCMS.FORMER.select("'.$attr['id'].'","'.$value.'");',true);
                    }
                    $input.= $_input;
                break;
                case 'device':
                    $value = iPHP_MOBILE?'1':'0';
                    $input->val($value);
                break;
                case 'postype':
                    $value = self::$config['gateway']=='admincp'?'1':'0';
                    $input->val($value);
                break;
                default:
                    $input->attr('type','text');
                break;
            }
            if($_type=='hidden'){
                $form_group =' former_hide';
                $input->attr('type','hidden');
            }

            $div = self::display(array(
                'class'  => 'former_'.$type.' '.$form_group,
                'id'     => $form_group_id?:'fg-'.random(5),
                'label'  => $label,
                'label2' => $label2,
                'input'  => self::display($input,'input'),
                'help'   => $help,
                'script' => $script,
            ));
        }
        self::$html[$id]= $div;
    }
    public static function display($html,$key="group") {
        $output = $html;
        if(self::$template[$key]){
            $output = self::template_class(self::$template[$key]);
            if(is_array($html)){
                foreach ($html as $k => $value) {
                    $output = str_replace('{{'.$k.'}}', $value, $output);
                }
            }else{
                $output = str_replace('{{content}}', $html, $output);
            }
        }
        return $output;
    }
    public static function set_template_class(Array $class) {
        self::$template['class'] = array_merge(self::$template['class'],$class);
    }
    public static function template_class($output) {
        if(self::$template['class'])foreach (self::$template['class'] as $k => $value) {
            $search[]  = '{{class_'.$k.'}}';
            $replace[] = $value;
        }
        $output = str_replace($search, $replace, $output);
        return $output;
    }

    public static function script($code=null,$script=false,$ready=true) {
        if($code){
            $code = '+function(){'.$code.'}();';
            $ready && $code = '$(function(){'.$code.'});';
            return $script?'<script>'.$code.'</script>':$code;
        }
    }
    public static function js_test($id,$label,$msg,$pattern) {
        $script = '
            var '.$id.'_msg = "'.$msg.'",pattern = '.$pattern.';
            if(!pattern.test('.$id.'_value)){
                iCMS.UI.alert('.$id.'_error||"['.$label.']"+'.$id.'_msg+", введите еще раз!");
                '.$id.'.focus();
                return false;
            }';
        return $script;
    }
    public static function id($name,$pre=null) {
        if(self::$prefix){
            return self::$prefix.'_'.$pre.$name;
        }else{
            return $pre.$name;
        }
    }
    public static function name($name,$pre=null) {
        if(self::$prefix){
            return self::$prefix.'['.$pre.$name.']';
        }else{
            return $pre.$name;
        }
    }
    public static function post() {
        if(self::$prefix){
            return $_POST[self::$prefix];
        }else{
            return $_POST;
        }
    }
    public static function validate($field_array,$lang='js',$value='') {
        if(empty($field_array['validate'])) return;

        $id    = self::id($field_array['id']);
        $name  = self::name($field_array['name']);
        $label = $field_array['label'];
        $type  = $field_array['type'];
        $error = $field_array['error'];

        if($lang=='js'){
            $javascript = 'var '.$id.' = $("#'.$id.'"),'.$id.'_value = '.$id.'.val(),'.$id.'_error="'.$error.'";';
            if($type=='editor'){
                // $script = 'var '.$id.' = iCMS.editor.get("editor-body-'.$id.'"),'.$id.'_value = '.$id.'.hasContents()';
                $javascript = 'var '.$id.' = iCMS.editor.get("editor-body-'.$id.'"),'.$id.'_value = '.$id.'.getContent()';
            }
            if($type=='markdown'){
                $javascript = 'var '.$id.' = iCMS.editor.get("editor-body-'.$id.'"),'.$id.'_value = '.$id.'.getMarkdown()';
            }

        }

        foreach ($field_array['validate'] as $key => $vd) {
            $code = null;
            switch ($vd) {
                case 'zipcode':
                    $msg = "Неправильный почтовый индекс";
                    $pattern = "/^[1-9]{1}(\d+){5}$/";
                break;
                case 'idcard':
                    $msg = "身份证有误";
                    $pattern = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
                break;
                case 'telphone':
                    $msg = "Неверный формат номера телефона";
                    $pattern = "/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$/";
                break;
                case 'mobphone':
                    $msg = "Неверный формат мобильного номера телефона";
                    $pattern = "/^1[34578]\d{9}$/";
                break;
                case 'phone':
                    $msg = "Неверный формат номера телефона";
                    $pattern = "/(^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$)|(^1[34578]\d{9}$)/";
                break;
                case 'url':
                    $msg = "Неверный формат URL";
                    $pattern = "/^[a-zA-z]+:\/\/[^\s]*/";
                break;
                case 'email':
                    $msg = "Неверный формат почтового адреса";
                    $pattern = "/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
                break;
                case 'number':
                    $msg = "Разрешено использовать только цифры";
                    $pattern = "/^\d+(\.\d+)?$/";
                break;
                case 'russian':
                    $msg = "Можно вводить только китайские иероглифы";
                    $pattern = "/^[\u4e00-\u9fa5]*$/";
                    if($lang=='php'){
                        $pattern = "/^[\x{4e00}-\x{9fa5}]*$/u";
                    }
                break;
                case 'character':
                    $msg = "Разрешено использовать только буквы";
                    $pattern = "/^[A-Za-z]+$/";
                break;
                case 'empty':
                    $msg = $label.'Поле не может быть пустым!';
                    if($lang=='php'){
                        empty($value) && iUI::alert($msg);
                    }else{
                        $code = '
                            if(!'.$id.'_value){
                                iCMS.UI.alert('.$id.'_error||"'.$msg.'");
                                '.$id.'.focus();
                                return false;
                            }
                        ';
                    }
                break;
                case 'minmax':
                    $min  = $field_array['minmax'][0];
                    $max  = $field_array['minmax'][1];
                    $msg_min = '您填写的'.$label.'小于'.$min.',введите еще раз!';
                    $msg_max = '您填写的'.$label.'大于'.$max.',введите еще раз!';
                    if($lang=='php'){
                        ($value<$min) && iUI::alert($msg_min);
                        ($value>$max) && iUI::alert($msg_max);
                    }else{
                        $code = '
                            var value = parseInt('.$id.'_value)||0;
                            // console.log(value);
                            if (value < '.$min.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_min.'");
                                '.$id.'.focus();
                                return false;
                            }
                            if (value > '.$max.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_max.'");
                                '.$id.'.focus();
                                return false;
                            }
                        ';
                    }

                break;
                case 'count':
                    $min  = $field_array['count'][0];
                    $max  = $field_array['count'][1];
                    $msg_min = '您填写的'.$label.'小于'.$min.'字符,введите еще раз!';
                    $msg_max = '您填写的'.$label.'大于'.$max.'字符,введите еще раз!';
                    if($lang=='php'){
                        (strlen($value)<$min) && iUI::alert($msg_min);
                        (strlen($value)>$max) && iUI::alert($msg_max);
                    }else{
                        $code = '
                            var value = '.$id.'_value.replace(/[^\x00-\xff]/g, \'xx\').length;
                            // console.log(value);
                            if (value < '.$min.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_min.'");
                                '.$id.'.focus();
                                return false;
                            }
                            if (value > '.$max.') {
                                iCMS.UI.alert('.$id.'_error||"'.$msg_max.'");
                                '.$id.'.focus();
                                return false;
                            }
                        ';
                    }

                break;
                case 'defined':
                    $field_array['defined'] && $code = stripcslashes($field_array['defined']);
                break;
                default:
                    # code...
                    break;
            }
            if($lang=='php'){
                if($pattern && $msg){
                    preg_match($pattern, $value) OR iUI::alert($msg);
                }
            }else{
                if(empty($code)){
                    $code = self::js_test($id,$label,$msg,$pattern);
                }
                $javascript.= $code;
            }
        }
        $javascript = preg_replace('/\s{3,}/is', '', $javascript);
        self::$validate.= $javascript;
        self::$script.= self::script(stripcslashes($field_array['javascript']));

        return $javascript;
    }
    public static function field_value($value,$type) {
        if($value){
        //Преобразование времени
            $type =='date'     && $value = get_date($value,'Y-m-d');
            $type =='datetime' && $value = get_date($value,'Y-m-d H:i:s');
            $type =='json'     && $value = htmlspecialchars($value);
        }
        return $value;
    }
    //Обработка вывода данных (пользовательская форма)
    public static function field_output($value,$fields,$vArray=null) {
        //Тип данных поля
        $field = $fields['field'];

        //Тип поля
        list($type,$_type) = explode(':', $fields['type']);

        $value = self::field_value($value,$type);

        if(in_array($type, array('category','multi_category'))){
            $variable = self::$variable[$fields['name']];
            $valArray = explode(",", $value);
            $value = '';
            foreach ($valArray as $i => $val) {
                $array = $variable[$val];
                $value.= '<a href="'.APP_DOURI.'&cid='.$val.'&'.$uri.'">'.$array->name.'</a>';
            }
        }
        //Множественный выбор преобразования поля
        if(!empty($fields['multiple'])){
          $value = explode(',',$value);
        }

        return $value;
    }
    //Обработка данных полей
    public static function field_input($value,$fields) {
        //Тип данных поля
        $field = $fields['field'];

        //Тип поля
        list($type,$_type) = explode(':', $fields['type']);


        //Преобразование времени
        if(in_array($type, array('date','datetime'))){
          $value = str2time($value);
          if($_type=='hidden'){
            $value = time();
          }
        }
        if(in_array($type, array('ip'))){
          $value = iPHP::get_ip();
        }
        if(in_array($type, array('referer'))){
          $value = $_SERVER['HTTP_REFERER'];
        }
        //Множественный выбор преобразования поля
        if(!empty($fields['multiple'])){
          is_array($value) && $value = implode(',',$value);
        }
        if($type=='image'){
            $name = $fields['name'];
            if(iFS::checkHttp($value) && !isset($_POST[$name.'_http'])){
                $value  = iFS::http($value);
            }
        }
        
        if(in_array($field, array('BIGINT','INT','MEDIUMINT','SMALLINT','TINYINT'))){
          $value = (int)$value;
        }
        if($type=='seccode'){
            $seccode = iSecurity::escapeStr($value);
            if(!iSeccode::check($seccode, true)){
                self::$error = iUI::code(0,'iCMS:seccode:error',$fields,'array');
                return false;
            }
        }
        
        if($type=='editor'){
            if(self::$config['gateway']=='usercp'){
                $value = iPHP::vendor('CleanHtml', array($value));
            }
            isset($_POST['dellink'])    && $value = preg_replace("/<a[^>].*?>(.*?)<\/a>/si", "\\1",$value);
            isset($_POST['iswatermark'])&& files::$watermark_enable = false;
            if($_POST['remote']){
                $value = filesAdmincp::remotepic($value,true);
                $value = filesAdmincp::remotepic($value,true);
                $value = filesAdmincp::remotepic($value,true);
                // files::set_file_iid($body,$aid,self::$appid);
            }
        }elseif($type=='json'){
            $value = json_decode(stripcslashes($value));
            $value = addslashes(json_encode($value));
        }else{
            $value = iSecurity::escapeStr($value);
        }

        return $value;
    }
    /**
     * Обработка данных формы
     * @param  [type] $app [Данные приложения]
     * @return [array]     [description]
     */
    public static function post_data($app,$post=null) {

        if($post===null) $post = self::post();
        // if($post===null) $post = $_POST;
        if(empty($post)||self::$error)
            return array(false,false,false,false,false);
        // if(empty($_POST)) return array(false,false,false,false,false);

        $field_post  = array();
        $orig_post   = $data_post = array();
        $imap_array  = array();
        // $data_table  = next($app['table']);
        $data_table  = apps_mod::get_data_table($app['table']);
        $data_table && former::base_fields_merge($app,$data_table);
        $field_array = self::fields($app['fields']);

        // foreach ($_POST as $key => $value) {
        foreach ($post as $key => $value) {
            $fields = $field_array[$key];
            //Необработанные данные
            if(strpos($key,'_orig_')!==false){
              $orig_post[$key] = $value;
              continue;
            }
            if(empty($fields)){
                continue;
            }
            
            $fields['func'] && $value = self::func($fields['func'],$value);
            $value = self::field_input($value,$fields);
            self::validate($fields,'php',$value);
            $field_post[$key] = $value;
            // if(strpos($key,'_orig_')!==false){
            //   $orig_post[$key] = $value;
            //   unset($post[$key]);
            // }
            if($fields['field']=='MEDIUMTEXT'){
              $data_post[$key] = $value;
              unset($field_post[$key]);
            }

            if(in_array($fields['type'], array('multi_image','multi_file'))){
                if (is_array($value)) {
                    $field_post[$key] = $value = json_encode($value);
                }
            }
            if(in_array($fields['type'], array('category','multi_category'))){
                $imap_array[$key] = array('category',$value);
            }
            if(in_array($fields['type'], array('prop','multi_prop'))){
                $imap_array[$key] = array('prop',$value);
            }
            if(in_array($fields['type'], array('tag'))){
                $tag_array[$key] = array($value,$field_post['cid']);
            }

            if($data_table){
                if($data_table['primary']==$key||$data_table['union']==$key){
                  $data_post[$key] = $value;
                  unset($field_post[$key]);
                }
            }
        }
        
        foreach ($field_array as $key => $fields) {
            $value = self::field_input(null,$fields);
            if($value && !isset($field_post[$key])){
                $field_post[$key] = $value;
            }
        }

        $tb = reset($app['table']);
        $tables = array($tb['name']);
        if($data_table){
            $values = compact('field_post','data_post');
            array_push($tables,$data_table['name']);
        }else{
            $values = compact('field_post');
        }
// print_R($tables);
// print_R($values);
// exit;
        $variable = array_combine($tables,$values);
// var_dump($variable,$tables,$orig_post,$imap_array,$tag_array);
// exit;
       
        return array($variable,$tables,$orig_post,$imap_array,$tag_array);
    }
    /**
     * Обработка данных формы
     * @param  [type] $func  [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function func($func,$value,$type='input') {
        return $value;
    }
    public static function layout_publish() {
        return is_array(self::$layout['publish'])?implode('',self::$layout['publish']):'';
    }
    public static function layout($id=null,$func='submit') {
        $pieces = array();
        if(self::$html){
            if(self::$layout['publish']){
                self::$layout['publish'] = array();
                $publish = array(
                    'pubdate','sortnum','weight',
                    'hits','hits_today','hits_yday','hits_week','hits_month',
                    'favorite','comments','good','bad',
                    'tpl','clink','url'
                );
                foreach (self::$html as $key => $value) {
                    if(in_array($key, $publish)||($is_publish && is_object($value))){
                        self::$layout['publish'][$key] = $value;
                        unset(self::$html[$key]);
                        $is_publish = true;
                        is_object($value) && $is_publish = false;
                    }
                }
            }
            self::$html && $pieces[] = implode('',self::$html);
        }

        if(self::$validate||self::$script){
            $pieces[]= '<script type="text/javascript">';
            $pieces[]= '$(function(){';
            if(self::$validate){
                if($is===null && defined('APP_FORMID')){
                    $id = '#'.APP_FORMID;
                }
                $pieces[]= '$("'.$id.'").'.$func.'(function(){';
                $pieces[]= self::$validate;
                $pieces[]= '});';
            }
            $pieces[]= self::$script;
            $pieces[]= '});';
            $pieces[]= '</script>';
        }
        if(empty($pieces)){
            $pieces[] = '<a href="https://www.icmsdev.com/docs/add-custom-fields.html" target="_blank">Посмотрите, как добавить пользовательские поля</a>';
        }
        return implode(PHP_EOL, $pieces);
    }
}
