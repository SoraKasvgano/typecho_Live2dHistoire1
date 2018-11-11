<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Live2d——伊斯特瓦尔雷姆整合版
 * 
 * @package Live2dHistoire
 * @author 广树
 * @version 1.1.3
 * @link http://www.wikimoe.com
 */
class Live2dHistoire_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array('Live2dHistoire_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('Live2dHistoire_Plugin', 'footer');
		Helper::addRoute("route_Live2dHistoire","/Live2dHistoire","Live2dHistoire_Action",'action');
		//Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Live2dHistoire_Plugin', 'setak');
		//Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Live2dHistoire_Plugin', 'setak');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
		Helper::removeRoute("route_Live2dHistoire");
	}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
       /**表单设置 */
       $live2d_type = new Typecho_Widget_Helper_Form_Element_Radio(
        'live2d_type', array ('0' => '伊斯特瓦尔', '1' => '雷姆'), 0,
        '设置live2d人物', '');
        $form->addInput($live2d_type);
		$appkey = new Typecho_Widget_Helper_Form_Element_Text('appkey', NULL, NULL, _t('图灵机器人APIkey'));
        $form->addInput($appkey);
		$talk1 = new Typecho_Widget_Helper_Form_Element_Text('talk1', NULL, '真理惟一可靠的标准就是永远自相符合。', _t('伊斯特瓦尔要说的话其一'));
        $form->addInput($talk1);
		$talk2 = new Typecho_Widget_Helper_Form_Element_Text('talk2', NULL, '相信谎言的人必将在真理之前毁灭。', _t('伊斯特瓦尔要说的话其二'));
        $form->addInput($talk2);
		$talk3 = new Typecho_Widget_Helper_Form_Element_Text('talk3', NULL, '一件事实是一条没有性别的真理。', _t('伊斯特瓦尔要说的话其三'));
        $form->addInput($talk3);
		$talk4 = new Typecho_Widget_Helper_Form_Element_Text('talk4', NULL, '躯体总是以惹人厌烦告终。除思想以外，没有什么优美和有意思的东西留下来，因为思想就是生命。', _t('伊斯特瓦尔要说的话其四'));
        $form->addInput($talk4);
		$talk5 = new Typecho_Widget_Helper_Form_Element_Text('talk5', NULL, '你可以从别人那里得来思想，你的思想方法，即熔铸思想的模子却必须是你自己的。', _t('伊斯特瓦尔要说的话其五'));
        $form->addInput($talk5);
		$bgm1 = new Typecho_Widget_Helper_Form_Element_Text('bgm1', NULL, NULL, _t('播放的背景音乐第一首'));
        $form->addInput($bgm1);
		$bgm2 = new Typecho_Widget_Helper_Form_Element_Text('bgm2', NULL, NULL, _t('播放的背景音乐第二首'));
        $form->addInput($bgm2);
		$bgm3 = new Typecho_Widget_Helper_Form_Element_Text('bgm3', NULL, NULL, _t('播放的背景音乐第三首'));
        $form->addInput($bgm3);
		$bgm4 = new Typecho_Widget_Helper_Form_Element_Text('bgm4', NULL, NULL, _t('播放的背景音乐第四首'));
        $form->addInput($bgm4);
		$bgm5 = new Typecho_Widget_Helper_Form_Element_Text('bgm5', NULL, NULL, _t('播放的背景音乐第五首'));
        $form->addInput($bgm5);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 页头输出CSS
     *
     * @access public
     * @param unknown header
     * @return unknown
     */
    public static function header() {
        $Path = Helper::options()->pluginUrl . '/Live2dHistoire/';
        echo '<link rel="stylesheet" type="text/css" href="' . $Path . 'css/live2d.css" />';
    }
	/**
     * 页脚输出代码
     *
     * @access public
     * @param unknown footer
     * @return unknown
     */
    public static function footer() {
        $Options = Helper::options()->plugin('Live2dHistoire');
        $Path = Helper::options()->pluginUrl . '/Live2dHistoire/';
        $siteUrl = Helper::options()->siteUrl;
        $live2d_type = $Options->live2d_type;
       echo '
	   <div id="landlord" style="left:5px;bottom:0px;"><div class="message" style="opacity:0"></div><canvas id="live2d" width="500" height="560" class="live2d"></canvas><div class="live_talk_input_body"><div class="live_talk_input_name_body"><input name="name" type="text" class="live_talk_name white_input" id="AIuserName" autocomplete="off" placeholder="你的名字" /></div><div class="live_talk_input_text_body"><input name="talk" type="text" class="live_talk_talk white_input" id="AIuserText" autocomplete="off" placeholder="要和我聊什么呀？"/><button type="button" class="live_talk_send_btn" id="talk_send">发送</button></div></div><input name="live_talk" id="live_talk" value="1" type="hidden" /><div class="live_ico_box"><div class="live_ico_item type_info" id="showInfoBtn"></div><div class="live_ico_item type_talk" id="showTalkBtn"></div><div class="live_ico_item type_quit" id="hideButton"></div><div class="live_ico_item type_music" id="musicButton"></div><audio src="" style="display:none;" id="live2d_bgm" data-bgm="0" preload="none"></audio><input name="live_statu_val" id="live_statu_val" value="0" type="hidden" /></div></div>';
	   echo '<div id="open_live2d">召唤看板娘</div>';	
	if(!empty($Options->talk1)){
		echo '<div class="live2d_weiyu_cache" style="display:none;">'.$Options->talk1.'</div>';
	};
	if(!empty($Options->talk2)){
		echo '<div class="live2d_weiyu_cache" style="display:none;">'.$Options->talk2.'</div>';
	};
	if(!empty($Options->talk3)){
		echo '<div class="live2d_weiyu_cache" style="display:none;">'.$Options->talk3.'</div>';
	};
	if(!empty($Options->talk4)){
		echo '<div class="live2d_weiyu_cache" style="display:none;">'.$Options->talk4.'</div>';
	};
	if(!empty($Options->talk5)){
		echo '<div class="live2d_weiyu_cache" style="display:none;">'.$Options->talk5.'</div>';
	};
	
	if(!empty($Options->bgm1)){
		echo '<input name="live2dBGM" value="'.$Options->bgm1.'" type="hidden">';
	};
	if(!empty($Options->bgm2)){
		echo '<input name="live2dBGM" value="'.$Options->bgm2.'" type="hidden">';
	};
	if(!empty($Options->bgm3)){
		echo '<input name="live2dBGM" value="'.$Options->bgm3.'" type="hidden">';
	};
	if(!empty($Options->bgm4)){
		echo '<input name="live2dBGM" value="'.$Options->bgm4.'" type="hidden">';
	};
	if(!empty($Options->bgm5)){
		echo '<input name="live2dBGM" value="'.$Options->bgm5.'" type="hidden">';
	};
	
	echo "<script>
		var message_Path = '".$Path."';
        var home_Path = '".$siteUrl."';
        var live2d_type = ".$live2d_type.";
	</script>";
	echo '<script src="'. $Path .'js/live2d.js?ver0.2"></script>';
	echo '<script src="'. $Path .'js/message.js?ver0.9"></script>';
    }
	/*public static function setak($data,$widget,$last) {
		$aaa = Helper::options()->plugin('Live2dHistoire')->appkey;
		file_put_contents(dirname(__FILE__).'/live2d.com.php','<?php die; ?>'.serialize(array(
			'ak'=>$aaa,
		)));
	}*/
}