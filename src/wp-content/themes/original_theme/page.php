<?php get_header(); ?>
<br>
<br>
<br>
<p style="text-align: center;"><?php echo '<b>固定ページだよ</b>' ?></p>
<br>
<br>
<br>

<?php while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
<?php endwhile; ?>

<?php get_footer(); ?>
