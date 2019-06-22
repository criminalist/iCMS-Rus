<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head(false);
?>
<script type="text/javascript">
var settings = {
    pick: {
        id: '#filePicker',
        label: 'Выбрать изображение'
    },
    fileVal:'upfile',
    formData: {"udir":"<?php echo $_GET['dir']; ?>"},
    dnd: '#dndArea',
    paste: '#uploader',
    swf: './app/admincp/ui/webuploader/Uploader.swf',
    chunked: false,
    chunkSize: 512 * 1024,
    server: '<?php echo APP_URI; ?>&do=upload&format=json&CSRF_TOKEN=<?php echo iPHP_WAF_CSRF_TOKEN;?>',
    callback:{
        "uploadSuccess":function(a,b){
            // console.log(b);
            if(b.state=='SUCCESS'){
                var state = window.top.modal_<?php echo $this->callback;?>('<?php echo $this->target;?>',b,false);
                // console.log(state);
                if(!state){
                    window.top.iCMS_MODAL.destroy();
                }
            }else{
                return iCMS.alert(b.state);
            }

        },
        "startUpload":function(uploader){
            var formData  = uploader.option( 'formData');
            var checked = $("#unwatermark").prop("checked");
            if(!checked) formData['unwatermark'] = true;
            uploader.option( 'formData', formData);
            return false;
        }
    },
    // runtimeOrder: 'flash',

    // accept: {
    //     title: 'Images',
    //     extensions: 'gif,jpg,jpeg,bmp,png',
    //     mimeTypes: 'image/*'
    // },

    // 禁掉全局的拖拽功能.这样不会出现图片拖进页面的时候,把图片打开.
    disableGlobalDnd: true,
    fileNumLimit: 300,
    fileSizeLimit: 200 * 1024 * 1024,    // 200 M
    fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
}

</script>
<link rel="stylesheet" href="./app/admincp/ui/webuploader/webuploader.css" type="text/css" />
<link rel="stylesheet" href="./app/admincp/ui/webuploader/style.css" type="text/css" />
<script type="text/javascript" src="./app/admincp/ui/webuploader/webuploader.min.js"></script>
<script type="text/javascript" src="./app/admincp/ui/webuploader/upload.js"></script>

<div class="widget-box  widget-plain">
  <div class="widget-title"> <span class="icon">
    <input type="checkbox" class="checkAll" data-target="#files-swfupload" />
    </span>
    <h5>Список файлов </h5>
    <span class="label label-success" id="FilesUploaded" num="0" style="margin-top: 10px;">0 файлов было загружено</span> </div>
    <div class="widget-content nopadding">
        <div id="uploader">
            <div class="queueList">
                <div id="dndArea" class="placeholder">
                    <div id="filePicker"></div>
                    <p>Или перетащите сюда, не более 300 файлов одновременно.</p>
                    
                </div>
            </div>
            <div class="statusBar" style="display:none;">
                <div class="progressBar">
                    <span class="text">0%</span>
                    <span class="percentage"></span>
                </div>
                <div class="info"></div>
                <div class="btns">
                    <div class="input-prepend"><span class="add-on">Водяной знак</span>
                      <div class="switch" data-on-label="添加" data-off-label="不添加">
                        <input type="checkbox" data-type="switch" name="unwatermark" id="unwatermark" <?php echo iCMS::$config['watermark']['enable']?'checked':''; ?>/>
                      </div>
                    </div>
                    <div id="filePicker2"></div>
                    <div class="uploadBtn"><i class="fa fa-upload"></i> Начать загрузку </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<?php admincp::foot();?>
