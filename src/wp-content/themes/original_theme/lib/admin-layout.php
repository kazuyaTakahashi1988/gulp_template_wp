<?php
/*==============================
左メニュー削除
==============================*/
function remove_menu()
{
    remove_menu_page('edit-comments.php');//コメント
}
add_action('admin_menu', 'remove_menu');

/*==============================
左メニュー並び替え
==============================*/
function custom_menu_order($menu_ord)
{
    if (!$menu_ord) {
        return true;
    }
    return array(
        'index.php',//ダッシュボード
        'edit.php',//投稿
        'edit.php?post_type=',//カスタム投稿
        'edit.php?post_type=page',//固定ページ
        'upload.php',//メディア
        'edit-comments.php',//コメント
        'separator2',//仕切り
        'themes.php',//外観
        'plugins.php',//プラグイン
        'users.php',//ユーザー
        'admin.php?page=all-in-one-seo-pack/aioseop_class.php',//All in One SEO Pack
        'tools.php',//ツール
        'options-general.php',//設定
        'separator-last',//仕切り
    );
}
add_filter('custom_menu_order', 'custom_menu_order');
add_filter('menu_order', 'custom_menu_order');

/*==============================
投稿一覧カラム
==============================*/
function add_page_columns_name($columns)
{//固定ページ一覧にスラッグ欄を追加
    $columns['slug'] = "スラッグ";
    return $columns;
}
function add_page_column($column_name, $post_id)
{
    if ($column_name == 'slug') {
        $post = get_post($post_id);
        $slug = $post->post_name;
        echo attribute_escape($slug);
    }
}
add_filter('manage_pages_columns', 'add_page_columns_name');
add_action('manage_pages_custom_column', 'add_page_column', 10, 2);


// function add_HOGEPOST_columns_name($columns){//カスタム投稿一覧に表示欄を追加
// 	$columns['hoge'] = "勤務地";
// 	return $columns;
// }
// function add_HOGEPOST_column($column_name,$post_id){
// 	if($column_name == 'hoge'){
// 		echo attribute_escape(get_post_meta($post_id,'hoge-field',true));
// 	}
// }
// add_filter('manage_HOGEPOST_posts_columns','add_HOGEPOST_columns_name');
// add_action('manage_HOGEPOST_posts_custom_column','add_HOGEPOST_column',10,2);

/*==============================
テーマ編集にJS追加
==============================*/
add_filter('wp_theme_editor_filetypes', function ($default_types) {
    $default_types[] = 'js';
    return $default_types;
});

/*==============================
フッター変更
==============================*/
function custom_admin_footer()
{
    echo '';
}
add_filter('admin_footer_text', 'custom_admin_footer');