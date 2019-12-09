<?php

add_shortcode('h_url', 'HomeURL');//使用例：[h_url]contact/
add_shortcode('t_url', 'ThemeURL');//使用例：[t_url]img/
add_shortcode('u_url', 'UploadsURL');//使用例：[u_url]2015/
add_shortcode('a_url', 'AssetsURL');//使用例：[a_url]js/
add_shortcode('user_data', 'UserData');//使用例：[user_data type="IP"]
//使用例：[user_data type="HOST"]
//使用例：[user_data type="Browser"]
//使用例：[user_data type="now"]
add_shortcode('bread', 'Breadcrumbs');//使用例：[bread]
add_shortcode('p_content', 'PageContent');//使用例　[p_content slug="footer-diary" field="sp" type="" pbr=true]
add_shortcode('slug', 'get_the_currentslug');//使用例：[slug]
add_shortcode('rootslug', 'get_the_rootslug');//使用例：[rootslug]
add_shortcode('include', 'LoadPHP');//使用例：[include file="hoge.php" assets=true]

/*━━━━━━━━━━━━━━━
指定のタグの属性内でショートコードを展開可能にする
━━━━━━━━━━━━━━━*/
add_filter( 'wp_kses_allowed_html', 'my_wp_kses_allowed_html', 10, 2 );
function my_wp_kses_allowed_html( $tags, $context ) {
    $tags['div']['style'] = true;
    return $tags;
}


/*━━━━━━━━━━━━━━━
各種URL
━━━━━━━━━━━━━━━*/
function HomeURL()
{//ホームURLショートコード
    return H_URL;
}
function ThemeURL()
{//テーマURLショートコード
    return T_URL;
}
function UploadsURL()
{//アップロードURLショートコード
    return U_URL;
}
function AssetsURL()
{//AssetsURLショートコード
    return A_URL;
}

/*━━━━━━━━━━━━━━━
訪問者データ
━━━━━━━━━━━━━━━*/
function UserData($atts)
{//訪問者の関連データ出力
    extract(
        shortcode_atts(array(
            'type' => 'IP',	//フィールドタイプ（任意）
        ), $atts)
    );
    if ($type === 'IP') {
        $text = $_SERVER['REMOTE_ADDR'];
    } elseif ($type === 'HOST') {
        $text = getHostByAddr($_SERVER['REMOTE_ADDR']);
    } elseif ($type === 'Browser') {
        $text = $_SERVER["HTTP_USER_AGENT"];
    } elseif ($type === 'now') {
        date_default_timezone_set('Asia/Tokyo');
        $text = date('Y-m-d H:i:s');
    }
    return $text;
}

/*━━━━━━━━━━━━━━━
パンくずリスト
━━━━━━━━━━━━━━━*/
function Breadcrumbs()
{
    ob_start();
    if (function_exists('bcn_display')) {
        bcn_display();
    }
    $text = ob_get_clean();
    return $text;
}

/*━━━━━━━━━━━━━━━
指定した固定ページのテキスト系フィールドを出力
━━━━━━━━━━━━━━━*/
function PageContent($atts)
{
    extract(
        shortcode_atts(array(
            'slug' => '',	//ページスラッグ（必須）
            'field' => '',	//カスタムフィールド（任意）
            'type' => 'text',	//フィールドタイプ（任意）
            'pbr' => false,	//<p><br>自動整形（任意）
        ), $atts)
    );
    $page = get_page_by_path($slug);//固定ページのデータ取得

    if (!empty($page) && !empty($field)) {//指定がある場合はカスタムフィールドを読み込む
        $data = get_post_meta($page->ID, $field, true);
        if ($type === 'text') {	//テキスト
            $text = nl2br(do_shortcode($data));
        } elseif ($type === 'url') {	//ファイルURL
            $text = wp_get_attachment_url($data);
        } elseif ($type === 'img') {	//IMGタグ
            $text = wp_get_attachment_image($data, 'full', true);
        }
    } elseif (!empty($page)) {//スラッグのみなら本文を読み込む
        $text = do_shortcode($page->post_content);
    }
    if ($pbr) {//自動整形
        $text = wpautop($text);
    }
    return $text;
}

/*━━━━━━━━━━━━━━━
現在位置のスラッグを返す
━━━━━━━━━━━━━━━*/
function get_the_currentslug()
{
    global $post;
    return $post->post_name;
}
/*━━━━━━━━━━━━━━━
現在位置のルートページのスラッグを返す
━━━━━━━━━━━━━━━*/
function get_the_rootslug()
{
    global $post;
    $root = $post;
    while ($root->post_parent != 0) {
        $root = get_post($root->post_parent);
    }
    return $root->post_name;
}

/*━━━━━━━━━━━━━━━
PHP読み込み
━━━━━━━━━━━━━━━*/
function LoadPHP($atts)
{
    extract(
        shortcode_atts(array(
            'file' => '',	//ページスラッグ（必須）
            'assets' => false,	//assetsテーマから呼び出す
        ), $atts)
    );
    ob_start();
    if ($assets) {
        $path = A_PATH;
    } else {
        $path = T_PATH;
    }
    include($path . $file);
    $text = ob_get_clean();
    return $text;
}