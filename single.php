<?php get_header();?>
<div id="main" class="site-main">
<?php while( have_posts() ): the_post(); $p_id = get_the_ID(); ?>	
<div id="primary" class="content-area">
<div id="content" class="site-content" role="main">
<article id="post" class="post type-post status-publish format-standard hentry category-511 tag-378 tag-badminton">
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>
<div class="entry-content">
	<?php the_content(); ?>
<?php if ( is_single() ) { ?>	
<h2>站内相关文章：</h2>
<ul id="cat_related">
<?php
global $post;
$cats = wp_get_post_categories($post->ID);
if ($cats) {
    $args = array(
          'category__in' => array( $cats[0] ),
          'post__not_in' => array( $post->ID ),
          'showposts' => 6,
          'caller_get_posts' => 1
      );
  query_posts($args);
  if (have_posts()) {
    while (have_posts()) {
      the_post(); update_post_caches($posts); ?>
  <li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
<?php
    }
  }
  else {
    echo '<li>暂无相关文章</li>';
  }
  wp_reset_query();
}
else {
  echo '<li>暂无相关文章</li>';
}
?>
</ul>
<?php } ?>
</div>
<?php if ( is_single() ) { ?>
<footer class="entry-meta">
	发表于<time class="entry-date"><?php the_time('Y-m-d'); ?></time>.<span class="cat-links"> 发表在<?php the_category(', ') ?></span><span class="sep"> | </span>
	<span class="tags-links"><?php the_tags('关键词:', ', ', ''); ?></span><span class="sep"> | </span>
	<span class="comments-link"><?php comments_popup_link ('0 评论','1 评论','% 评论'); ?> </span>
</footer>
<?php } ?>
</article>
<?php if ( is_single() ) { ?>
<nav role="navigation" id="nav-below" class="site-navigation post-navigation">
	<h1 class="assistive-text">Post navigation</h1>
	<div class="nav-previous"><?php previous_post_link( '←%link', '%title', true );?></div>
	<div class="nav-next"><?php next_post_link( '%link→', '%title', true );?></div>
</nav>
<?php } ?>
<?php comments_template(); ?>
</div>
<?php endwhile; ?>
<?php get_sidebar();?>
</div>
</div>
<?php get_footer(); ?>