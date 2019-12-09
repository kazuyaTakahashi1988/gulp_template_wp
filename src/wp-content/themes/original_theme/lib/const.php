<?php
/*
パス
*/
define('H_URL', home_url('/'));                        //ホームURL
define('T_URL', get_template_directory_uri() . '/');   //テーマディレクトリ
define('T_PATH', get_template_directory() . '/');      //テーマディレクトリ　サーバーパス
// define('A_URL', get_theme_root_uri() . '/assets/'); //共用ファイルテーマディレクトリ
// define('A_PATH', get_theme_root() . '/assets/');    //共用ファイルテーマディレクトリ　サーバーパス
define('U_URL', content_url() . '/uploads/');          //メディアアップロードディレクトリ

/*
フラグ
*/
define('IS_ADMIN', is_admin());                         //管理ページフラグ
define('IS_LOGIN', is_user_logged_in());                //ログインユーザーフラグ
define('IS_MOBILE', wp_is_mobile());                    //モバイルフラグ

add_action('wp', 'get_type_flag');                      //投稿タイプが確定してからフラグを取る
function get_type_flag()
{
    define('IS_HOME', is_home());
    define('IS_FRONT_PAGE', is_front_page());                       //TOPページフラグ
}


global $_inputID;
$_inputID = 0;
