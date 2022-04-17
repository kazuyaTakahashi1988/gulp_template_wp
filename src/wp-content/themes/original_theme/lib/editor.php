<?php


/*

                 ########         ######## ########  #### ########  #######  ########
                    ##            ##       ##     ##  ##     ##    ##     ## ##     ##
                    ##            ##       ##     ##  ##     ##    ##     ## ##     ##
 ####### #######    ##    ####### ######   ##     ##  ##     ##    ##     ## ########  ####### #######
                    ##            ##       ##     ##  ##     ##    ##     ## ##   ##
                    ##            ##       ##     ##  ##     ##    ##     ## ##    ##
                    ##            ######## ########  ####    ##     #######  ##     ##

テキストエディタ
*/

/*
 ######  ##    ## ##  ###### ##   ## ########  #####   ######
##    ## ##    ## ## ##      ##  ##     ##    ##   ## ##
##    ## ##    ## ## ##      #####      ##    ####### ##   ###
## ▄▄ ## ##    ## ## ##      ##  ##     ##    ##   ## ##    ##
 ######   ######  ##  ###### ##   ##    ##    ##   ##  ######
    ▀▀
*/
/*==============================
クイックタグ
==============================*/
function default_quicktags($qtInit)
{	//ボタン削除
    $qtInit['buttons'] = '　';	//指定しなかったIDのデフォルトボタンを削除
    return $qtInit;
}
add_filter('quicktags_settings', 'default_quicktags', 10, 1);

if (!function_exists('add_quicktags_to_text_editor')):
function add_quicktags_to_text_editor()
{	//ボタン追加
    //スクリプトキューにquicktagsが保存されているかチェック
    if (wp_script_is('quicktags')): ?>
<script>
QTags.addButton('qt-bold', 'b', '<b>', '</b>');
QTags.addButton('qt-uline', 'u', '<span class="te-uline">', '</span>');
QTags.addButton('qt-p', 'p', '<p>', '</p>');
QTags.addButton('qt-div', 'div', '<div class="">', '</div>');
QTags.addButton('qt-br', 'br', '<br>', '');
QTags.addButton('qt-h2', 'h2', '<h2>', '</h2>\n');
QTags.addButton('qt-h3', 'h3', '<h3>', '</h3>\n');
QTags.addButton('qt-h4', 'h4', '<h4>', '</h4>\n');
QTags.addButton('qt-ol', 'ol', '<ol class="">\n', '</ol>\n');
QTags.addButton('qt-ul', 'ul', '<ul class="">\n', '</ul>\n');
QTags.addButton('qt-li', 'li', '<li>', '</li>');
QTags.addButton('qt-table', 'table', '<table>\n<thead>\n</thead>\n<tbody>\n', '</tbody>\n</table>\n');
QTags.addButton('qt-tr', 'tr', '<tr>', '</tr>');
QTags.addButton('qt-th', 'th', '<th>', '</th>');
QTags.addButton('qt-td', 'td', '<td>', '</td>');
</script>
<?php endif;
}
endif;
add_action('admin_print_footer_scripts', 'add_quicktags_to_text_editor');


/*

                 ##     ##         ######## ########  #### ########  #######  ########
                 ##     ##         ##       ##     ##  ##     ##    ##     ## ##     ##
                 ##     ##         ##       ##     ##  ##     ##    ##     ## ##     ##
 ####### ####### ##     ## ####### ######   ##     ##  ##     ##    ##     ## ########  ####### #######
                  ##   ##          ##       ##     ##  ##     ##    ##     ## ##   ##
                   ## ##           ##       ##     ##  ##     ##    ##     ## ##    ##
                    ###            ######## ########  ####    ##     #######  ##     ##

ビジュアルエディタ
*/

// 見出しタグの調整
function custom_editor_settings($initArray)
{
    $initArray['block_formats'] = "見出し1=h2; 見出し2=h3; 見出し3=h4; 段落=p; グループ=div;";
    return $initArray;
}
add_filter('tiny_mce_before_init', 'custom_editor_settings');

// テーブル機能追加
function mce_external_plugins_table($plugins) {
    $plugins['table'] = '//cdn.tinymce.com/4/plugins/table/plugin.min.js';
    $plugins['contextmenu'] = '//cdn.tinymce.com/4/plugins/contextmenu/plugin.min.js';
    return $plugins;
}
add_filter( 'mce_external_plugins', 'mce_external_plugins_table' );

function mce_buttons_table($buttons) {
    $buttons[] = 'table';
    return $buttons;
}
add_filter( 'mce_buttons', 'mce_buttons_table' );

/*
####### ########  #####  ######## ##  ######
##         ##    ##   ##    ##    ## ##
#######    ##    #######    ##    ## ##
     ##    ##    ##   ##    ##    ## ##
#######    ##    ##   ##    ##    ##  ######
*/
/*━━━━━━━━━━━━━━━━━━━━━
基本編集不要エリア
━━━━━━━━━━━━━━━━━━━━━*/

/*
 ###### ####### #######
##      ##      ##
##      ####### #######
##           ##      ##
 ###### ####### #######
*/
add_editor_style('assets/css/editor-style.min.css');//エディタのスタイル設定
function editor_class($initArray)
{
    $initArray['body_class'] = 'wp-post visual-editor';
    return $initArray;
}
add_filter('tiny_mce_before_init', 'editor_class');

/*
######  ## #######       ##    ## ##       ####### ######  ## ########  ######  ######
##   ## ## ##            ##    ## ##       ##      ##   ## ##    ##    ##    ## ##   ##
##   ## ## ####### ##### ##    ## ## ##### #####   ##   ## ##    ##    ##    ## ######
##   ## ##      ##        ##  ##  ##       ##      ##   ## ##    ##    ##    ## ##   ##
######  ## #######         ####   ##       ####### ######  ##    ##     ######  ##   ##
*/

/*------------------------------
指定投稿タイプのビジュアルエディタ無効化
------------------------------*/
function disable_visual_editor()
{
    global $typenow;
    if ($typenow == 'page' || $typenow == 'mw-wp-form') {//固定ページとMW WP Formで無効化
        add_filter('user_can_richedit', 'disable_visual_editor_filter');
    }
}
function disable_visual_editor_filter()
{
    return false;
}
add_action('load-post.php', 'disable_visual_editor');
add_action('load-post-new.php', 'disable_visual_editor');

/*
######  ## #######        ######  ######  ###    ##  ###### ####### ###    ## ########
##   ## ## ##            ##      ##    ## ####   ## ##      ##      ####   ##    ##
##   ## ## ####### ##### ##      ##    ## ## ##  ## ##      #####   ## ##  ##    ##
##   ## ##      ##       ##      ##    ## ##  ## ## ##      ##      ##  ## ##    ##
######  ## #######        ######  ######  ##   ####  ###### ####### ##   ####    ##
*/
/*------------------------------
集中執筆モード無効化
------------------------------*/
function do_disable_editor_expand($user_id)
{
    global $wpdb;
    $meta_key = $wpdb->prefix . 'user-settings';
    $_prev_user_settings = get_user_meta($user_id, $meta_key, true);
    $_user_settings = explode('&', $_prev_user_settings);
    $_is_override = false;
    foreach ($_user_settings as $_i => $_buff) {
        list($_key, $_val) = explode('=', $_buff);
        if ('editor_expand' === $_key) {
            $_val = 'off';
            $_is_override = true;
        }
        $_user_settings[$_i] = $_key . '=' . $_val;
    }
    if (! $_is_override) {
        $_user_settings[] = 'editor_expand=off';
    }
    update_user_meta($user_id, $meta_key, implode('&', $_user_settings), $_prev_user_settings);
}
function custom_login_redirect($redirect_to, $requested_redirect_to, $user)
{
    if (! is_wp_error($user)) {
        global $user;
        if (isset($user->ID)) {
            do_disable_editor_expand($user->ID);
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);
function custom_user_register($user_id)
{
    do_disable_editor_expand($user_id);
}
add_action('user_register', 'custom_user_register');