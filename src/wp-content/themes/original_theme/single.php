<?php
$cate = get_the_category()[0]; // カテゴリー
$args = [
    'pageTtl' => get_the_title() . '｜' . $cate->name . '｜' . SITE_NAME, // タイトル
    'pageDes' => wp_trim_words(get_the_content(), 135, ''), // ディスクリプション
    'pageKey' => get_the_title() . 'のキーワード', // キーワード
    'ogpImg' => get_the_post_thumbnail_url(), // OGP画像
    // 'pageCss' => 'xxxx.css', // cssファイル
    // 'pageJs' => 'xxxx.min.js', // jsファイル
    // 'queryParam' => $_GET['query'], // クエリーパラメーター
];
get_header(null, $args);
?>

<div class="contswrap">
    <?php
    /* --------------------------
        詳細 ▽ start ▽
    ----------------------------------- */
    while (have_posts()) : the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo $cate->name.'：'; ?></p>
        <?php the_content(); ?>
    <?php endwhile;
    /* --------------------------
        詳細 ▲ end ▲ 
    ----------------------------------- */ ?>
    <br><br>
    <?php
    /* --------------------------
        ページャー ▽ start ▽
    ----------------------------------- */ ?>
    <ul>
        <li><?php previous_post_link('%link', '&lsaquo;', TRUE, ''); ?></li>
        <li><a href="<?php echo get_category_link($cate->cat_ID); ?>">一覧へ戻る</a>
        </li>
        <li><?php next_post_link('%link', '&rsaquo;', TRUE, ''); ?></li>
    </ul>
    <?php
    /* --------------------------
        ページャー ▲ end ▲ 
    ----------------------------------- */ ?>
</div>

<?php get_footer(null, $args); ?>