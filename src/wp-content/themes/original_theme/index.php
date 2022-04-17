<?php
$args = [
    // 'pageTtl' => 'TOPのタイトル', // タイトル
    // 'pageDes' => 'TOPのディスクリプション', // ディスクリプション
    // 'pageKey' => 'TOPのキーワード', // キーワード
    // 'ogpImg' => '', // OGP画像
    'pageCss' => 'top.css', // cssファイル
    'pageJs' => 'top.min.js', // jsファイル
    // 'queryParam' => $_GET['query'], // クエリーパラメーター
];
get_header(null, $args);
?>

<div class="contswrap">
    <h3>テーマの説明</h3><br>
    <?php
    /* --------------------------
        テーマの説明 ▽ start ▽
    ----------------------------------- */ ?>
    <p>
        <b>☆概要 -------------------</b><br>
        今テーマはアクセス集中時にサーバー処理の負荷（遅延）を並べく生じさせない作りを心がけています。<br>
        そのためカスタマイズ作業の際、以下2〜3つほど縛り・制約があります。<br>
        <h5><a href="http://theme.empty-service.com/wp-theme.zip" target="_blank" style="color: crimson; text-align: center;">☆Downloadはこちらから☆</a></h5>
        <br>
        <b>☆1 -------------------</b><br>
        使用頻度の高いWP関数は負荷が大きいのでそのままでは使えないようになっており、<br>
        下記の通り<b>定数</b>にしてあります。<br>
        （詳しくは「 テーマディレクトリ/lib/const.php 」ファイルの記述を参照ください）<br>
        <div class="bg-gray">
        // サイトネーム<br>
        <span style="color: crimson;">get_bloginfo( 'name' )</span> >>> <b style="color: blue;">SITE_NAME</b> <br>
        // ホームURL<br>
        <span style="color: crimson;">home_url()</span> >>> <b style="color: blue;">HOME_URL</b> <br>
        // テーマディレクトリ<br><span style="color: crimson;">get_template_directory_uri()</span> >>> <b style="color: blue;">THEME_URL</b> <br>
        // テーマサーバーパス<br><span style="color: crimson;">get_template_directory()</span> >>> <b style="color: blue;">THEME_PATH</b> <br>
        // デフォルトOGP・Thum画像<br><span style="color: crimson;">get_template_directory_uri().'/assets/images/common/ogp.png'</span> >>> <b style="color: blue;">DEFAULT_IMG</b> <br>
        // TOPページフラグ<br><span style="color: crimson;">is_home()</span> >>> <b style="color: blue;">IS_HOME</b> <br>
        // FRONTページフラグ<br><span style="color: crimson;">is_front_page()</span> >>> <b style="color: blue;">IS_FRONT_PAGE</b>
        </div>
        <br>
        <span style="color: crimson;">home_url()</span> 等は <b style="color: blue;">HOME_URL</b> とまんま書き換えて記述できます。<br>
        （例： echo home_url(); → echo HOME_URL; として利用できる ）<br>
        他 <span style="color: crimson;">if ( is_home() || is_front_page() )</span> としたい場合は <b style="color: blue;">if ( IS_HOME || IS_FRONT_PAGE )</b> として利用できます。<br>
        <br>
        <b>☆2 -------------------</b><br>
        header.php / footer.php / function.phpファイル内でのif文負荷を防ぐため、<br>
        固有な値（meta情報 や CSS＆JS）は各テンプレートファイルの文頭にある <b>$args</b> 変数に格納し<br>
        「 <b style="color: blue;">get_header(null, $args); / get_footer(null, $args);</b> 」として受け渡しています。<br><br>
        <span style="color: red;">PHPを書くとき変数名 <b>$args</b> はこれ以外で用いないよう注意ください。</span><span style="font-size: 12px;">※拾い物のコードにほんとよく着いてきます</span><br>
        もしコンテンツ内で変数名 $args を用い、値が再代入及び上書きされてしまうと<br>
        ソース下部にあるget_footer(null, $args); に誤った設定値が届いてしまいます。
        <br>
        <br>
        <b>☆3 -------------------</b><br>
        パフォーマンスにあまり関係なく必須でもないですが、カスタム投稿やMW（コンタクトフォーム）の編集にプラグイン依存をしたくない人向けに、function.phpにそれ用の記述があります。<br>
        それぞれコメントインすることで下記のファイル群を利用できます。<br>
        <span style="font-size: 12px;">※このテストサイトではコメントインしています。</span>
        <div class="bg-gray">
        ・テーマディレクトリ/lib/add-custompost.php<br>
        ・テーマディレクトリ/lib/smart-custom-fields.php<br>
        ・テーマディレクトリ/lib/mw-wp-form.php
        </div>
        
        <br>
    </p>
    <?php
    /* --------------------------
        テーマの説明 ▲ end ▲ 
    ----------------------------------- */ ?>
</div>

<div class="contswrap">
    <?php
    /* --------------------------
        固定ページ一覧 ▽ start ▽
    ----------------------------------- */
        wp_list_pages();
    /* --------------------------
        固定ページ一覧  ▲ end ▲ 
    ----------------------------------- */ ?>
</div>

<div class="contswrap">
    <p>記事</p><br>
    <?php
    /* --------------------------
        記事一覧 ▽ start ▽
    ----------------------------------- */
    $paged = (int) get_query_var('paged');
    $setting = array(
        'posts_per_page' => 5,
        'paged' => $paged,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish'
    );
    $the_query = new WP_Query($setting);
    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <a href="<?php the_permalink(); ?>">
                <h3><?php the_title(); ?></h3>
                <?php echo wp_trim_words(get_the_content(), 135, ''); ?>
            </a><br>
        <?php endwhile;
        wp_reset_postdata();
    else : ?>
        <!-- 投稿が無い場合の処理 -->
        <p>投稿はまだありません。</p>
    <?php endif;
    /* --------------------------
        記事一覧 ▲ end ▲ 
    ----------------------------------- */ ?>
</div>

<?php /* ?>
<div class="contswrap">
    <p>カスタム記事</p><br>
    
    <?php
    $paged = (int) get_query_var('paged');
    $setting = array(
        'posts_per_page' => 5,
        'paged' => $paged,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'custompost',
        'post_status' => 'publish'
    );
    $the_query = new WP_Query($setting);
    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <a href="<?php the_permalink(); ?>">
                <h3><?php the_title(); ?></h3>
                <?php echo wp_trim_words(get_the_content(), 135, ''); ?>
            </a><br>
        <?php endwhile;
        wp_reset_postdata();
    else : ?>
        <!-- 投稿が無い場合の処理 -->
        <p>投稿はまだありません。</p>
    <?php endif; ?>
</div>
<?php */ ?>

<?php get_footer(null, $args); ?>