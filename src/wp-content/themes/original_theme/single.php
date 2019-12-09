<?php get_header(); ?>
<br>
<br>
<br>
<p style="text-align: center;"><?php echo '<b>記事詳細ページだよ</b>' ?></p>
<br>
<br>
<br>

<?php
    $cat = get_the_category()[count(get_the_category()) - 1]; // 情報取得
    $catId = $cat->cat_ID; // ID取得
    $catName = $cat->name; // 名称取得
    $catSlug = $cat->category_nicename; // スラッグ取得
    $link = get_category_link($catId);
?>

<?php while ( have_posts() ) : the_post(); ?>
    <?php the_title(); ?>
    <?php echo $catName; ?>
    <?php the_time('Y.m月d日'); ?>
    <?php the_content(); ?>
<?php endwhile; ?>

<ul class="pegeBottom-Navi">
    <li class="prev-link"><?php previous_post_link('%link', '&lsaquo;', TRUE, ''); ?></li>
    <li class="return-link"><a href="<?php echo $link; ?>">一覧へ戻る</a>
    </li>
    <li class="next-link"><?php next_post_link('%link', '&rsaquo;', TRUE, ''); ?></li>
</ul>

<?php include('_sidebar.php'); ?>

<?php get_footer(); ?>
