<?php
//theme options
include( 'admin-option/theme-option.php' );

//seo
$DX_seo = get_option( 'other-2' );
if( $DX_seo[0] == 'on' ) include( 'includes/seo/seo.php' );

//谷歌字体
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans' );

//移除顶部多余信息
remove_action( 'wp_head', 'wp_enqueue_scripts', 1 ); //Javascript的调用
remove_action( 'wp_head', 'feed_links', 2 ); //移除feed
remove_action( 'wp_head', 'feed_links_extra', 3 ); //移除feed
remove_action( 'wp_head', 'rsd_link' ); //移除离线编辑器开放接口
remove_action( 'wp_head', 'wlwmanifest_link' );  //移除离线编辑器开放接口
remove_action( 'wp_head', 'index_rel_link' );//去除本页唯一链接信息
remove_action('wp_head', 'parent_post_rel_link', 10, 0 );//清除前后文信息
remove_action('wp_head', 'start_post_rel_link', 10, 0 );//清除前后文信息
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'locale_stylesheet' );
remove_action('publish_future_post','check_and_publish_future_post',10, 1 );
remove_action( 'wp_head', 'noindex', 1 );
remove_action( 'wp_head', 'wp_print_styles', 8 );//载入css
remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
remove_action( 'wp_head', 'wp_generator' ); //移除WordPress版本
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_footer', 'wp_print_footer_scripts' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
add_action('widgets_init', 'my_remove_recent_comments_style');
function my_remove_recent_comments_style() {
global $wp_widget_factory;
remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ,'recent_comments_style'));
}
//禁止加载WP自带的jquery.js
if ( !is_admin() ) { // 后台不禁止
function my_init_method() {
wp_deregister_script( 'jquery' ); // 取消原有的 jquery 定义
}
add_action('init', 'my_init_method'); 
}
wp_deregister_script( 'l10n' );

//注册导航
register_nav_menus(
      array(
       'cl' => __( '导航' )
      )
   );
   
//禁止代码标点转换
remove_filter('the_content', 'wptexturize');

//编辑器增强
 function enable_more_buttons($buttons) {
     $buttons[] = 'hr';
     $buttons[] = 'del';
     $buttons[] = 'sub';
     $buttons[] = 'sup'; 
     $buttons[] = 'fontselect';
     $buttons[] = 'fontsizeselect';
     $buttons[] = 'cleanup';   
     $buttons[] = 'styleselect';
     $buttons[] = 'wp_page';
     $buttons[] = 'anchor';
     $buttons[] = 'backcolor';
     return $buttons;
     }
add_filter("mce_buttons_3", "enable_more_buttons");

//给文章图片自动添加alt和title信息
add_filter('the_content', 'imagesalt');
function imagesalt($content) {
       global $post;
       $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
       $replacement = '<a$1href=$2$3.$4$5 alt="'.$post->post_title.'" title="'.$post->post_title.'"$6>';
       $content = preg_replace($pattern, $replacement, $content);
       return $content;
}

/*激活友情链接后台*/
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//评论作者新标签打开
function hu_popuplinks($text) {
	$text = preg_replace('/<a (.+?)>/i', "<a $1 target='_blank'>", $text);
	return $text;
}
add_filter('get_comment_author_link', 'hu_popuplinks', 6);

//评论列表
function commentlist($comment,$args,$depth){
	$GLOBALS['comment']=$comment; 
	//主评论计数器 by zwwooooo
	global $commentcount, $page, $wpdb;
	if ( (int) get_option('page_comments') === 1 && (int) get_option('thread_comments') === 1 ) { //开启嵌套评论和分页才启用
		if(!$commentcount) { //初始化楼层计数器
			$page = ( !empty($in_comment_loop) ) ? get_query_var('cpage') : get_page_of_comment( $comment->comment_ID, $args ); //获取当前评论列表页码
			$cpp = get_option('comments_per_page'); //获取每页评论显示数量
			if ( get_option('comment_order') === 'desc' ) { //倒序
				$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = 'all' AND comment_approved = '1' AND !comment_parent");
				$cnt = count($comments); //获取主评论总数量
				if (ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) { //如果评论只有1页或者是最后一页，初始值为主评论总数
					$commentcount = $cnt + 1;
				} else {
					$commentcount = $cpp * $page + 1;
				}
			} else {
				$commentcount = $cpp * ($page - 1);
			}
		}
		if ( !$parent_id = $comment->comment_parent ) {
			$commentcountText = '<div class="floor">';
			if ( get_option('comment_order') === 'desc' ) { //倒序
				$commentcountText .= '#' . ++$commentcount;
			} else {
				$commentcountText .= '#' . ++$commentcount;
			}
			$commentcountText .= '</div>';
		}
	}
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="author"><?php echo get_avatar($comment,'32'); ?></div>
			<div class="comment-meta">
				<div style="float:left;"><span class="name"><?php printf(__('%s'), get_comment_author_link()) ?></span>
				<div class="reply">
					<?php comment_reply_link(array_merge($args,array('reply_text' =>'回复','depth' =>$depth,'max_depth'=>$args['max_depth']))) ?>
				</div>
				</div>
				<?php if($comment->comment_approved=='0'): ?>
					<em><span class="moderation"><?php _e('您的评论正在等待审核.') ?></span></em>
				<?php endif; ?>
				<br>
				<div class="text">
					<?php comment_text() ?>
				</div>
			</div>
			<?php echo $commentcountText;?>
		</div>
<?php 
}

//冒充评论检验
function CheckEmailAndName(){
	global $wpdb;
	$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
	$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
	if(!$comment_author || !$comment_author_email){
		return;
	}
	$result_set = $wpdb->get_results("SELECT display_name, user_email FROM $wpdb->users WHERE display_name = '" . $comment_author . "' OR user_email = '" . $comment_author_email . "'");
	if ($result_set) {
		if ($result_set[0]->display_name == $comment_author){
			err(__('警告: 您不能使用博主的昵称！'));
		}else{
			err(__('警告: 您不能使用博主的邮箱！'));
		}
		fail($errorMessage);
	}
}
add_action('pre_comment_on_post', 'CheckEmailAndName');

/* 评论必须有中文和禁止某些字段出现 */    
function lianyue_comment_post( $incoming_comment ) {    
$pattern = '/[一-龥]/u';    
$http = '/[.|<|妈|逼|滚|贱|淫|互|娘|爹|孙|友|夜|ッ|の|ン|優|業|グ|貿|]/u';  
// 禁止全英文评论  
if(!preg_match($pattern, $incoming_comment['comment_content'])) {  
wp_die( "请认真评论好吗?您这样随意打乱码对站长也太不尊敬了吧,你觉得呢?" );  
}elseif(preg_match($http, $incoming_comment['comment_content'])) {  
wp_die( "万恶的发贴机,这里不允许出现连点号,更请您文明用语!" );    
}    
return( $incoming_comment );    
}    
add_filter('preprocess_comment', 'lianyue_comment_post');

//评论邮件自动通知
function comment_mail_notify($comment_id) {
  $admin_email = get_bloginfo ('admin_email');
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $to = $parent_id ? trim(get_comment($parent_id)->comment_author_email) : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam') && ($to != $admin_email) && ($comment_author_email == $admin_email)) {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    $subject = '您在 [' . get_option("blogname") . '] 的评论有新的回复';
    $message = '
    <div style="font: 13px Microsoft Yahei;padding: 0px 20px 0px 20px;border: #ccc 1px solid;border-left-width: 4px; max-width: 600px;margin-left: auto;margin-right: auto;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在 [' . get_option("blogname") . '] 的文章 《' . get_the_title($comment->comment_post_ID) . '》 上发表评论：<br />'
       . nl2br(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回复如下:<br>'
       . nl2br($comment->comment_content) . '</p>
      <p style="color:#f00">您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id, array('type' => 'comment'))) . '">查看回复的完整內容</a></p>
      <p style="color:#f00">欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p style="color:#999">(此邮件由系统自动发出，请勿回复。)</p>
    </div>';
	$message = convert_smilies($message);
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post', 'comment_mail_notify');

//ajax评论翻页
function AjaxCommentsPage(){
	if( isset($_GET['action'])&& $_GET['action'] == 'AjaxCommentsPage'  ){
		global $post,$wp_query, $wp_rewrite;
		$postid = isset($_GET['post']) ? $_GET['post'] : null;
		$pageid = isset($_GET['page']) ? $_GET['page'] : null;
		if(!$postid || !$pageid){
			fail(__('Error post id or comment page id.'));
		}
		// get comments
		$comments = get_comments('post_id='.$postid);
		$post = get_post($postid);
		if(!$comments){
			fail(__('Error! can\'t find the comments'));
		}
		//if( 'desc' != get_option('comment_order') ){
		//	$comments = array_reverse($comments);
		//}
		$comments = array_reverse($comments);//?有点不明白
		// set as singular (is_single || is_page || is_attachment)
		$wp_query->is_singular = true;
		// base url of page links
		$baseLink = '';
		if ($wp_rewrite->using_permalinks()) {
			$baseLink = '&base=' . user_trailingslashit(get_permalink($postid) . 'comment-page-%#%', 'commentpaged');
		}
		// response 注意修改callback为你自己的，没有就去掉callback
		wp_list_comments('callback=commentlist&type=comment&max_depth=10000&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);
		echo '<!--winysky-AJAX-COMMENT-PAGE-->';
		echo '<span id="cp_post_id" style="display:none;">
			'.$post->ID.'
		</span>';
		paginate_comments_links('current=' . $pageid . $baseLink);
		die;
	}
}
add_action('init', 'AjaxCommentsPage');

//压缩html代码
function wp_compress_html()
{
function wp_compress_html_main ($buffer)
{
	$initial=strlen($buffer);
	$buffer=explode("<!--wp-compress-html-->", $buffer);
	$count=count ($buffer);
	for ($i = 0; $i <= $count; $i++)
	{
		if (stristr($buffer[$i], '<!--wp-compress-html no compression-->'))
		{
			$buffer[$i]=(str_replace("<!--wp-compress-html no compression-->", " ", $buffer[$i]));
		}
		else
		{
			$buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
			$buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
			$buffer[$i]=(str_replace("\n", "", $buffer[$i]));
			$buffer[$i]=(str_replace("\r", "", $buffer[$i]));

			while (stristr($buffer[$i], '  '))
			{
			$buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
			}
		}
		$buffer_out.=$buffer[$i];
	}
	//$final=strlen($buffer_out);
	//$savings=($initial-$final)/$initial*100;
	//$savings=round($savings, 2);
	//$buffer_out.="\n<!--压缩前的大小: $initial bytes; 压缩后的大小: $final bytes; 节约：$savings% -->";
	return $buffer_out;
}
ob_start("wp_compress_html_main");
}
add_action('get_header', 'wp_compress_html');

//本地加载GV头像  
function my_avatar($avatar) {  
  $tmp = strpos($avatar, 'http');  
  $g = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);  
  $tmp = strpos($g, 'avatar/') + 7;  
  $f = substr($g, $tmp, strpos($g, "?", $tmp) - $tmp);  
  $w = get_bloginfo('wpurl');  
  $e = ABSPATH .'avatar/'. $f .'.jpg';  
  $t = 604800; //設定7天, 單位:秒  
  if ( !is_file($e) || (time() - filemtime($e)) > $t ) {   
    copy(htmlspecialchars_decode($g), $e);  
  } else  $avatar = strtr($avatar, array($g => $w.'/avatar/'.$f.'.jpg'));  
  if (filesize($e) < 500) copy($w.'/avatar/default.png', $e);  
  return $avatar;  
}  
add_filter('get_avatar','my_avatar'); 