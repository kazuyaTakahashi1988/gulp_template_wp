<?php
/*━━━━━━━━━━━━━━━
 ver.情報を消す
━━━━━━━━━━━━━━━*/
remove_action('wp_head','wp_generator');
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
/*━━━━━━━━━━━━━━━
 絵文字機能の削除
━━━━━━━━━━━━━━━*/
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );     
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/*━━━━━━━━━━━━━━━
 リンク文字列 /category/ の削除
━━━━━━━━━━━━━━━*/
function remcat_function($link) {
    return str_replace("/category/", "/", $link);
    }
    add_filter('user_trailingslashit', 'remcat_function');
    function remcat_flush_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
    }
    add_action('init', 'remcat_flush_rules');
    function remcat_rewrite($wp_rewrite) {
    $new_rules = array('(.+)/page/(.+)/?' => 'index.php?category_name='.$wp_rewrite->preg_index(1).'&paged='.$wp_rewrite->preg_index(2));
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
    add_filter('generate_rewrite_rules', 'remcat_rewrite');