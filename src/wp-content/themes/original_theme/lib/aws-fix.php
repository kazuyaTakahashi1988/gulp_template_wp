<?php
/*
 ####  ######          ##     ##  #######  ########  #### ##       ########
  ##  ##               #### #### ##     ## ##     ##  ##  ##       ##
  ##   ######          ## ### ## ##     ## ########   ##  ##       ######
  ##        ##         ##     ## ##     ## ##     ##  ##  ##       ##
 ####  ######  ####### ##     ##  #######  ########  #### ######## ########
*/
/*
wp_is_mobileをAmazon CloudFrontに対応させる
CloudFrontの管理画面から以下のカスタムヘッダを有効化する必要あり
CloudFront-Is-Mobile-Viewer
CloudFront-Is-Tablet-Viewer
wp_is_mobileのフィルターフックはWP4.9.0以降からの実装なので4.9.0必須
*/
add_filter('wp_is_mobile', function( $is_mobile ) {
    // CloudFront でスマートフォンと判定された場合、true を返す
    if ( isset($_SERVER['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER']) && "true" === $_SERVER['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER'] ) {
        $is_mobile = true;
    }
    // CloudFront でタブレットと判定された場合、true を返す
    // （タブレットはPCと同じ扱いにしたい場合は、$is_mobile を false にする
    if ( isset($_SERVER['HTTP_CLOUDFRONT_IS_TABLET_VIEWER']) && "true" === $_SERVER['HTTP_CLOUDFRONT_IS_TABLET_VIEWER'] ) {
        $is_mobile = true;
    }
    return $is_mobile;
});