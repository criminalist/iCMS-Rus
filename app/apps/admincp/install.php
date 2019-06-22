<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style>
#log{color: #999;font-size: 14px;line-height: 24px;}
</style>
<div class="iCMS-container">
  <div class="well iCMS-well iCMS-patch">
    <div id="log"></div>
    <?php if($_GET['do']=="download"){?>
        <div class="form-actions">
        <?php if(isset($_GET['git'])){?>
            <a class="btn btn-success btn-sm" href="<?php echo APP_URI; ?>&do=install&release=<?php echo patch::$release; ?>&zipname=<?php echo $_GET['zipname']; ?>&git=true"><i class="fa fa-wrench"></i>Начать обновление</a>
        <?php }else{ ?>
            <a class="btn btn-success btn-sm" href="<?php echo APP_URI; ?>&do=install"><i class="fa fa-wrench"></i>Начать обновление</a>
        <?php } ?>
        </div>
    <?php } ?>
  </div>
</div>
<script type="text/javascript">
var log = "<?php echo $this->msg; ?>";
var n = 0;
var timer = 0;
log = log.split('<iCMS>');
setIntervals();
function GoPlay(){
	if (n > log.length-1) {
		n=-1;
		clearIntervals();
	}
	if (n > -1) {
		postcheck(n);
		n++;
	}
}
function postcheck(n){
	log[n]=log[n].replace('#','<br />');
	document.getElementById('log').innerHTML += log[n] + '<br /><a name="last"></a>';
	document.getElementById('log').scrollTop = document.getElementById('log').scrollHeight;
}
function setIntervals(){
	timer = setInterval('GoPlay()',100);
}
function clearIntervals(){
	clearInterval(timer);
	finish();
}
</script>
<?php admincp::foot();?>
