<?php
// require_once('lib/aws-fix.php');             // AWS向けの調整

require_once('lib/const.php');               // 定数

require_once('lib/login.php');               // ログイン画面カスタマイズ

require_once('lib/img.php');                 // 画像
require_once('lib/post-custom.php');         // 投稿改変
require_once('lib/editor.php');              // エディタ改変
require_once('lib/func.php');                // 関数
require_once('lib/shortcode.php');           // ショートコード
require_once('lib/cache.php');               // キャッシュ

// require_once('lib/add-custompost.php');      // カスタム投稿宣言
// require_once('lib/smart-custom-fields.php'); // SmartCustomFieldsプラグイン設定
// require_once('lib/mw-wp-form.php');          // MW-WP-Form
// require_once('lib/head-custom.php');         // ヘッダー改変
// require_once('lib/admin-layout.php');        // 管理画面レイアウト
// require_once('lib/ssl.php');                 // SSL強制対応


// アーカイブページ「全○○件中　○○件の表示」箇所の処理（不必要なら決してかまわない
function my_result_count() {
  global $wp_query;

  $paged = get_query_var( 'paged' ) - 1;
  $ppp   = get_query_var( 'posts_per_page' );
  $count = $total = $wp_query->post_count;
  $from  = 0;
  if ( 0 < $ppp ) {
    $total = $wp_query->found_posts;
    if ( 0 < $paged )
      $from  = $paged * $ppp;
  }
  printf(
    '該当数 <span>%1$s</span> 件中        %2$s%3$s件 を表示',
    $total,
    ( 1 < $count ? ($from + 1 . '〜') : '' ),
    ($from + $count )
  );
}


//新着情報サイドバー出力箇所	（不必要なら決してかまわない　
//※必ず「require_once（）」をこの後に書かないこと
add_filter( 'wp_list_categories', 'my_list_categories', 10, 2 );
function my_list_categories( $output, $args ) {
  $output = preg_replace('/<\/a>\s*\((\d+)\)/',' ($1)</a>',$output);
  return str_replace("/category/", "/", $output);
}


