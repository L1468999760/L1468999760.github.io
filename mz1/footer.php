<footer id="colophon" class="site-footer" role="contentinfo">
<div class="site-info">
<span style="color: #800000;">
			这里是<?php bloginfo('name'); ?>的个人网站，已经运行了<?php echo floor((time()-strtotime("2012-02-10"))/86400);?>天，共发表了<?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?>篇文章，共收到了<?php echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");?> 条有效评论，本站自豪的采用<span style="color: #666699;"><a href="http://creativecommons.org/licenses/by-nc-nd/3.0/cn/" target="_blank"><span style="color: #666699;">署名-非商业性使用-禁止演绎许可协议3.0</span></a></span>
</div>
</footer>
</div>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/images/small-menu.js"></script>
<p class="link-back2top" style="display: none;"><a href="#" title="Back to top">Back to top</a></p>
<script>
$(".link-back2top").hide();
$(window).scroll(function() {
	if ($(this).scrollTop() > 100) {
		$(".link-back2top").fadeIn();
	} else {
		$(".link-back2top").fadeOut();
	}
});
$(".link-back2top a").click(function() {
	$("body,html").animate({
		scrollTop: 0
	},
	800);
	return false;
});
</script>
<?php echo stripslashes( get_option( 'foot-2' ) ); ?>
<?php wp_footer(); ?>
</body></html>
