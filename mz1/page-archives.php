<?php
/*
Template Name: 归档模板
*/
?>
<?php get_header();?>
	<div id="main" class="site-main">
		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">		
				<div class="archives">
        <div id="archives">      
    <div id="archives-content">      
    <?php       
        $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' );      
        $year=0; $mon=0; $i=0; $j=0;      
        $all = array();      
        $output = '';      
        while ( $the_query->have_posts() ) : $the_query->the_post();      
            $year_tmp = get_the_time('Y');      
            $mon_tmp = get_the_time('n');      
            $y=$year; $m=$mon;      
            if ($mon != $mon_tmp && $mon > 0) $output .= '</div></div>';      
            if ($year != $year_tmp) { // 输出年份      
                $year = $year_tmp;      
                $all[$year] = array();      
            }      
            if ($mon != $mon_tmp) { // 输出月份      
                $mon = $mon_tmp;      
                array_push($all[$year], $mon);      
                $output .= "<div class='archive-title' id='arti-$year-$mon'><h3 class='m-title'>$year-$mon</h3><div class='archives-monthlisting archives-$mon' data-date='$year-$mon'>";      
            }      
            $output .= '<li><a href="'.get_permalink() .'"><span class="time">'.get_the_time('Y-n-d').'</span><div class="atitle">'.get_the_title() .'</div></a></li>';      
        endwhile;      
        wp_reset_postdata();      
        $output .= '</div></div>';      
        echo $output;      
     
        $html = "";      
    
    ?>     
    </div>      
    <div id="archive-nav">      
        <ul class="archive-nav"><?php echo $html;?></ul>      
    </div>      
</div><!-- #archives -->
				</div>
			</div>
		</div>
		<?php get_sidebar();?>
	</div>
<?php get_footer(); ?>