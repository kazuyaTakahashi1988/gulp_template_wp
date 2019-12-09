<?php
/*━━━━━━━━━━━━━━━
絵文字除去
━━━━━━━━━━━━━━━*/
//通常ページ絵文字
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

//管理画面絵文字
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

//RSS絵文字
remove_action('the_content_feed', 'wp_staticize_emoji');
remove_action('comment_text_rss', 'wp_staticize_emoji');

/*━━━━━━━━━━━━━━━
バージョン情報除去
━━━━━━━━━━━━━━━*/
remove_action('wp_head', 'wp_generator');

/*
 #####  ######  ###    ### ## ###    ##
##   ## ##   ## ####  #### ## ####   ##
####### ##   ## ## #### ## ## ## ##  ##
##   ## ##   ## ##  ##  ## ## ##  ## ##
##   ## ######  ##      ## ## ##   ####
*/
/*------------------------------
ヘッド追加処理
------------------------------*/
function AdminPlus()
{    //CSS/JS追記
    echo '
    <link href="https//fonts.googleapis.com/earlyaccess/notosansjp.css">
    <style>
    button,input,select,textarea,textarea.wp-editor-area{
        font-family: "Noto Sans JP","游ゴシック体","Yu Gothic",YuGothic,"Hiragino Kaku Gothic ProN","ヒラギノ角ゴ ProN W3","Meiryo","メイリオ","sans-serif";
    }
    </style>
    ';
    //if(get_post_type() === 'post'){}
    echo '
    <script>
    </script>
    ';
}
add_action('admin_enqueue_scripts', 'AdminPlus');

/*━━━━━━━━━━━━━━━
JS改変無効化
━━━━━━━━━━━━━━━*/
if (is_admin()) {
    define('CONCATENATE_SCRIPTS', false);    //JS統合無効化
    define('COMPRESS_SCRIPTS', false);        //JS圧縮無効化
}

/*━━━━━━━━━━━━━━━
管理バー
━━━━━━━━━━━━━━━*/
add_theme_support('admin-bar', array('callback' => '__return_false'));//管理バーの高さをレイアウトに影響させない

//アイテム削除
add_action('admin_bar_menu', 'admin_bar_remove_item', 1000);
function admin_bar_remove_item($wp_admin_bar)
{//管理バーの不要項目削除
    $wp_admin_bar->remove_node('wp-logo');//WPロゴ
    $wp_admin_bar->remove_node('customize');//カスタマイズ
    $wp_admin_bar->remove_node('updates');//更新通知
    $wp_admin_bar->remove_node('comments');//コメント
//    $wp_admin_bar->remove_node('new-content');//新規
    $wp_admin_bar->remove_node('new-media');//新規メディア
    $wp_admin_bar->remove_node('new-user');//新規ユーザー
//    $wp_admin_bar->remove_node('user-info');//ユーザー情報
//    $wp_admin_bar->remove_node('edit-profile');//プロフィール編集
    $wp_admin_bar->remove_node('search');//検索
}

//アイテム追加
add_action('admin_bar_menu', 'admin_bar_add_item', 9999);
function admin_bar_add_item($wp_admin_bar)
{
    $url = get_the_permalink($post_id);
    $url = preg_replace('!' . H_URL . '!im', '', $url);
    $phplink  = '&lt;?php echo H_URL; ?&gt;' . $url;
    $wplink .= '[h_url]' . $url;
    if (is_page() || is_single()) {
        $wp_admin_bar->add_menu(array(    //リンク用アイテム
            'id'    => 'link-code',
            'meta'  => array(),
            'title' => 'Link Code',
            'href'  => ''
        ));
        $wp_admin_bar->add_menu(array(    //リンク用アイテム
            'parent' => 'link-code',
            'id'     => 'link-code-php',
            'meta'   => array(),
            'title'  => $phplink,
            'href'   => ''
        ));
        $wp_admin_bar->add_menu(array(    //リンク用アイテム
            'parent' => 'link-code',
            'id'     => 'link-code-wp',
            'meta'   => array(),
            'title'  => $wplink,
            'href'   => ''
        ));
    }
}