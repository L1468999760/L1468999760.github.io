<div class="comment-amount">



<div id="commnents" class="commentsorping">
			
				<div class="leavecom"></div>
			
				<div class="commentpart">Comment (<?php comments_popup_link( '',' 1', '% ','comment_num'); ?>)</div>
			</div>



</div>
<div id="comments">
<?php if ( post_password_required() )return;?>
<?php if(have_comments()):?>
<ul class="commentlist"><?php wp_list_comments('type=comment&callback=commentlist&max_depth=10000'); ?></ul>
<div class="commentnavi">
<span id="cp_post_id" style="display:none;"><?php echo $post->ID; //ajax评论翻页?></span>
<?php paginate_comments_links('');?>
</div>
<?php endif; ?>
<?php if (comments_open()): ?>
<div id="respond">
<div class="cancel_comment_reply">
<?php cancel_comment_reply_link('取消回复'); ?>
</div>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if($user_ID): ?>
<div>已登录<a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>&nbsp;，&nbsp;<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">注销？</a></div>
<?php else: ?>
<div class="welcome">
<?php if($comment_author): ?>欢迎回来，<?php echo $comment_author; ?>！<?php else: ?><div id="replytitle">评论是一种美德，说点什么吧，否则我会恨你的。。。</div><?php endif; ?>
</div>
<div>
<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" <?php if($req) echo "aria-required='true'"; ?> />
<label>昵称 <span class="red">*</span></label>
</div>
<div>
<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" <?php if($req) echo "aria-required='true'"; ?> />
<label>邮箱 <span class="red">*</span></label>
</div>
<div>	
<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" />
<label>网站</label>
</div>
<?php endif; ?>
<div>
<textarea name="comment" id="comment" tabindex="4" onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('submit').click();return false};"></textarea>
</div>
<div>
<input name="submit" type="submit" id="submit" tabindex="5" value="点击发表评论" />
<?php comment_id_fields(); ?>
</div>
<?php do_action('comment_form',$post->ID); ?>
</form>
</div>
<?php endif; ?>
</div>