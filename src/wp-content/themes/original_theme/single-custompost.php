<?php
$postName = esc_html(get_post_type_object(get_post_type())->label); // カスタム投稿名
$args = [
    'pageTtl' => get_the_title() . '｜' . $postName . '｜' . SITE_NAME, // タイトル
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
        <div class="wysiwyg">
            <?php the_content(); ?>
        </div>

        <?php /* wysiwygの出力 ------------ */ ?>
        <div class="wysiwyg">
            <b>wysiwygによる出力：</b><br><br>
            <?php echo post_custom('contents'); ?>
        </div>

        <?php /* loop処理の出力 ---------------- */ ?>
        <div class="loop">
            <b>ループ出力：</b><br><br>
            <?php $cf_group = SCF::get('slides'); ?>
            <?php if (!empty($cf_group[0]["slide_img"])) : ?>
                <?php foreach ($cf_group as $field_name => $field_value) : ?>
                    <?php $cf_sample = wp_get_attachment_image_src($field_value['slide_img'], 'large'); ?>
                    <div class="img">
                        <img src="<?php echo esc_url($cf_sample[0]); ?>" alt="">
                        <?php echo $field_value['slide_ttl']; ?><br>
                        <?php echo $field_value['slide_caption']; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                投稿なし。
            <?php endif; ?>
        </div>

        <?php /* その他テキストの出力 ----------- */ ?>
        <div class="txt">
            <b>その他テキストの出力：</b><br><br>
            <?php echo post_custom('company'); ?><br><br>
            <?php echo post_custom('addres'); ?><br><br>
        </div>
    <?php endwhile;
    /* --------------------------
        詳細 ▲ end ▲ 
    ----------------------------------- */ ?>

    <?php
    /* --------------------------
        ページャー ▽ start ▽
    ----------------------------------- */ ?>
    <ul>
        <li><?php previous_post_link('%link', '&lsaquo;', false, ''); ?></li>
        <li><a href="<?php echo get_post_type_archive_link(get_post_type()); ?>">一覧へ戻る</a>
        </li>
        <li><?php next_post_link('%link', '&rsaquo;', false, ''); ?></li>
    </ul>
    <?php
    /* --------------------------
        ページャー ▲ end ▲ 
    ----------------------------------- */ ?>
</div>

<?php get_footer(null, $args); ?>