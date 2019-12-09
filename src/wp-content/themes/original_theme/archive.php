<?php get_header(); ?>
<br>
<br>
<br>
<p style="text-align: center;"><?php echo '<b>記事一覧ページだよ</b>' ?></p>
<br>
<br>
<br>


<?php echo $wp_query->found_posts; ?>件中<br>
<?php echo $wp_query->post_count; ?>件を表示


<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>
    <a href="<?php the_permalink(); ?>">
        <?php the_time('Y/m/d'); ?><br>
        <?php echo get_the_category()[count(get_the_category()) - 1]->cat_name;?><br>
        <?php the_title(); ?><br>
    </a>
<?php endwhile; endif; ?>
<?php // wp_pagenavi(); ?>


<?php include('_sidebar.php'); ?>

<?php get_footer(); ?>