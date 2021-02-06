<?php get_header(); ?>
<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<?php if(have_posts()) : ?>
	<?php while(have_posts()) : the_post(); ?>				
<article id="post" class="post type-post status-publish format-standard hentry category-235 tag-reading">
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header>
	<div class="entry-content">
	<?php the_content("[Read more]"); ?>
	</div>
	<footer class="entry-meta">
		发表于<time class="entry-date"><?php the_time('Y-m-d'); ?></time>.<span class="cat-links"> 发表在<?php the_category(', ') ?></span><span class="sep"> | </span>
		<span class="tags-links"><?php the_tags('关键词:', ', ', ''); ?></span><span class="sep"> | </span>
		<span class="comments-link"><?php comments_popup_link ('0 评论','1 评论','% 评论'); ?> </span>
	</footer>
</article>
	<?php endwhile; ?>
<?php endif; ?>					
	<nav role="navigation" id="nav-below" class="site-navigation paging-navigation">
		<h1 class="assistive-text">Post navigation</h1>
		<div class="nav-previous"><?php next_posts_link('← 较早期的文章') ?></div>
		<div class="nav-next"><?php previous_posts_link('较新的文章 →') ?></div>
	</nav>
		</div>
	</div>
<?php get_sidebar();?>
</div>
<?php get_footer(); ?>