<?php
/*━━━━━━━━━━━━━━━
本文をキーワード検索対象から外す
━━━━━━━━━━━━━━━*/
function search_for_title($search)
{
    return preg_replace("/ OR \([^\(\.]+.post_content LIKE '%.+%'\)/u", "", $search);
}
//add_filter('posts_search','search_for_title');//キーワード検索対象をタイトルだけにする
//remove_filter('posts_search','search_for_title');//キーワード検索対象を通常に戻す

function search_for_post($query)
{//検索対象の投稿タイプを限定する
    if ($query->is_search) {
        $query->set('post_type', array('post','page'));
    }
    return $query;
}
//add_filter('pre_get_posts','search_for_post');

/*━━━━━━━━━━━━━━━
TOPページを広く判定
━━━━━━━━━━━━━━━*/
function is_top($paged = TURE)
{//TOPページならTRUE PagedにFALSEを入れた場合、TOPの2ページ以降は除外
    $flag = false;
    if ((is_home() || is_front_page()) &&($paged || !is_paged())) {
        $flag = true;
    }
    return $flag;
}
/*━━━━━━━━━━━━━━━
ルートページ判定
━━━━━━━━━━━━━━━*/
function is_root($slug)
{//指定スラッグのルートページ、もしくはその子孫ならTRUE
    global $post;
    $flag = false;
    $root = $post;
    if (is_page()) {
        while ($root->post_parent != 0) {
            $root = get_post($root->post_parent);
        }
        foreach ((array)$slug as $val) {
            if ($root->post_name === $val) {
                $flag = true;
                break;
            }
        }
    }
    return $flag;
}

/*━━━━━━━━━━━━━━━
テキストを行単位でラップして返す
対象文字列,ラップタグ名,ラップタグクラス
━━━━━━━━━━━━━━━*/
function br_wrap($lines, $tagname = 'li', $class = '')
{
    $lines = explode("\n", $lines);            //文字列を改行ベースで配列に分割
    $lines = array_map('trim', $lines);        //前後の空白文字を除去
    $lines = array_filter($lines, 'strlen');   //文字数0の行を除去
    $text = '';
    foreach ((array)$lines as $line) {
        $text .= '<' . $tagname . ' class="' . $class . '">' . $line . '</' . $tagname . '>';
    }
    return $text;
}

/*━━━━━━━━━━━━━━━
チェックボックス出力
━━━━━━━━━━━━━━━*/
function get_the_checkbox($name, $value, $wrap=null)
{	//チェックボックス生成
    if (!empty($wrap)) {
        return '<label><input name="' . $name . '[]" value="' . $value . '" type="checkbox"><' . $wrap . '>' . $value . '</' . $wrap . '></label>';
    } else {
        return '<label><input name="' . $name . '[]" value="' . $value . '" type="checkbox">' . $value . '</label>';
    }
}


/*━━━━━━━━━━━━━━━
YouTubeURLからID抽出
━━━━━━━━━━━━━━━*/
function get_the_youtube_id($url)
{
    $youtube_id = '';
    $key_list = array('v','video_id');
    if (preg_match('/http[s]*:\/\/www.youtube.com\//u', $url)) {//通常urlまたはパラメータ付きurl
        $parse = parse_url($url);
        parse_str($parse['query'], $query);
        foreach ($key_list as $key) {
            if (!empty($query[$key])) {
                $youtube_id = $query[$key];
                break;
            }
        }
        if ($youtube_id === '') {
            $youtube_id = substr($url, -11, 11);
        }
    }
    if (preg_match('/http[s]*:\/\/www.youtube-nocookie.com\//u', $url)) {//nocookie
        $parse = parse_url($url);
        $youtube_id = basename($parse['path']);
    }
    if (preg_match('/http[s]*:\/\/youtu.be\//u', $url)) {//短縮url
        $youtube_id = substr($url, -11, 11);
    }
    if (strstr($url, 'iframe')) {//iframe
        preg_match('/src=\"(.[^\"]*)\"/', $url, $match);
        $explode = explode("/", $match[1]);// 取り出したsrc属性の内容を/で切り分けて配列に入れる
        $file = $explode[count($explode) - 1];// ファイル名は最後のスラッシュ以降
        $youtube_id = $file;
    }
    return $youtube_id;
}


/*
####### ##      ####### ##   ##     ######   #####   ######  ####### ######
##      ##      ##       ## ##      ##   ## ##   ## ##       ##      ##   ##
#####   ##      #####     ###       ######  ####### ##   ### #####   ######
##      ##      ##       ## ##      ##      ##   ## ##    ## ##      ##   ##
##      ####### ####### ##   ##     ##      ##   ##  ######  ####### ##   ##
*/

/*━━━━━━━━━━━━━━━
Flex Pager Var 2019-08-01
━━━━━━━━━━━━━━━*/
function flex_pager($args = '')
{
    global $wp_query;
    $text = '';
    $default = array(
        'query'          => $wp_query, // 使用クエリー
        'range'          => 3, // カレントからのページボタン数　指定数*2+1が総数
        'current_format' => '<span class="current">%d</span>', // カレントボタンのレイアウト
        'prev_next'      => true, // 戻る/進むボタン
        'edge_pn'        => false, // ページ末端側の戻る/進むボタンを表示するか
        'prev_label'     => '&lt;', // 戻るボタンテキスト
        'next_label'     => '&gt;', // 進むボタンテキスト
        'first_last'     => true, // 最初/最後ボタン
        'edge_fl'        => false, // ページ末端側の最初/最後ボタンを表示するか
        'first_last_no'  => true, // 最初/最後ナンバー
        'first_label'    => '&laquo;', // 最初ボタンテキスト
        'last_label'     => '&raquo;', // 最後ボタンテキスト
        'op_left'        => '', // 左側オプション　%d（現ページ）%d（総ページ）
        'op_right'       => '', // 右側オプション　%d（現ページ）%d（総ページ）
        'navi_class'     => 'page_navi', // ナビのクラス スペース区切りで複数指定
        'navi_id'        => '', // ナビのID
        'child_class'    => '', // 子要素のクラス
        'navi_type'      => 'div', // ナビのタイプ
        'child_wrap'     => '', // 子要素を括る要素
        'cw_class'       => '', // child_wrapのクラス
        'cw_cu_class'    => '', // child_wrapのカレントボタンクラス
        'off_class'      => 'off', // edgeオプションで表示している要素のクラス wrap有効時はそちらに付加
        'echo'           => true, // 出力するか、値で返すか
        'get_name'       => '', // GETパラメータ名
        'get_val'        => '', // GETパラメータ
        'get_val'        => '', // GETパラメータ
        'attr'           => '', // Aタグに任意の属性を追加
    );
    $args = wp_parse_args($args, $default); // パラメータとデフォルト値をマージ

    $now = get_query_var('paged'); // 現在のページ
    if ($now < 1) {
        $now = 1;
    }
    $max = $args['query']->max_num_pages; // 総ページ数
    if (!$max) {
        $max = 1;
    }

    if (1 >= $max) { // 総ページが1ページなら終了
        return false;
    }

    $getval = ''; // GETパラメータ
    if ($args['get_name']) { // GETパラメータの構築
        $getcount = 0;
        foreach ((array)$args['get_name'] as $getname) {
            if ($getcount === 0) {
                $getval .= '?';
            } else {
                $getval .= '&';
            }
            $getval .= $getname . '=' . $args['get_val'][$getcount];
            ++$getcount;
        }
    }


    $cw_st = '';
    $cw_st_cu = '';
    $cw_st_off = '';
    $cw_en = '';
    if ($args['child_wrap'] != '') { // 子要素を括る要素を決定
        $cw_st = '<'  . $args['child_wrap'] . ' class="' . $args['cw_class'] . '">';
        $cw_st_cu = '<'  . $args['child_wrap'] . ' class="' . $args['cw_class'] . ' ' . $args['cw_cu_class'] . '">';
        $cw_st_off = '<'  . $args['child_wrap'] . ' class="' . $args['cw_class'] . ' ' . $args['off_class'] . '">';
        $cw_en = '</' . $args['child_wrap'] . '>';
    }

    $text .= '<' . $args['navi_type'] . ' id="' . $args['navi_id'] . '" class="' . $args['navi_class'] . '">'; // ページャータグ

    if ($args['op_left']) {
        $text .= $cw_st . sprintf($args['op_left'], $now, $max) . $cw_en; // 左オプション
    }
    if ($args['first_last']) { // 最初
        if ($now == 1 && $args['edge_fl']) { // 必要ないが、それでも表示する設定
            if ($cw_st_off) {
                $text .= $cw_st_off . '<span class="move max first ' . $args['child_class'] . '">' . $args['first_label'] . '</span>' . $cw_en;
            } else {
                $text .= $cw_st_off . '<span class="move max first ' . $args['off_class'] . ' ' . $args['child_class'] . '">' . $args['first_label'] . '</span>' . $cw_en;
            }
        } elseif ($now >= 2) { // 戻り先がある
            $text .= $cw_st . '<a class="move max first ' . $args['child_class'] . '" href="' . get_pagenum_link(1) . $getval . '" ' . $args['attr'] . ' >' . $args['first_label'] . '</a>' . $cw_en;
        }
    }

    if ($args['prev_next']) { // 戻る
        if ($now == 1 && $args['edge_pn']) { // 戻り先がないが、それでも表示する設定
            if ($cw_st_off) {
                $text .= $cw_st_off . '<span class="move min prev ' . $args['child_class'] . '">' . $args['prev_label'] . '</span>' . $cw_en;
            } else {
                $text .= $cw_st_off . '<span class="move min prev ' . $args['off_class'] . ' ' . $args['child_class'] . '">' . $args['prev_label'] . '</span>' . $cw_en;
            }
        } elseif ($now >= 2) { // 戻り先がある
            $text .= $cw_st . '<a class="move min prev ' . $args['child_class'] . '" href="' . get_pagenum_link($now - 1) . $getval . '" ' . $args['attr'] . ' >' . $args['prev_label'] . '</a>' . $cw_en;
        }
    }

    $prev_volume = $now - 1;	 // 戻れる数
    $next_volume = $max - $now;	 // 進める数
    $prev_lange  = $args['range']; // 進む数
    $next_lange  = $args['range']; // 進む数
    if ($prev_volume < $args['range']) {
        $next_lange += $args['range'] - $prev_volume;
    }
    if ($next_volume < $args['range']) {
        $prev_lange += $args['range'] - $next_volume;
    }
    if ($args['first_last_no'] && ($now - $prev_lange) > 1) { // 1ページ目のリンクを表示
        $text .= $cw_st . '<a href="' . get_pagenum_link(1) . $getval . '" class="page ' . $args['child_class'] . '" ' . $args['attr'] . '" >1</a>' . $cw_en;
        if (($now - $prev_lange) > 2) { // 3点リーダー
            $text .= $cw_st . '...' . $cw_en;
        }
    }
    for ($i = $now - $prev_lange;$i < $now;$i++) { // 戻るページ番号を出力
        if ($i >= 1) {
            $text .= $cw_st . '<a href="' . get_pagenum_link($i) . $getval . '" class="page ' . $args['child_class'] . '" ' . $args['attr'] . '" >' . $i . '</a>' . $cw_en;
        }
    }
    $text .= $cw_st_cu . sprintf($args['current_format'], $now) . $cw_en; // カレントページ
    for ($i = $now + 1;$i <= $now + $next_lange;$i++) { // 進むページ番号を出力
        if ($i <= $max) {
            $text .= $cw_st . '<a href="' . get_pagenum_link($i) . $getval . '" class="page ' . $args['child_class'] . '" ' . $args['attr'] . '" >' . $i . '</a>' . $cw_en;
        }
    }

    if ($args['first_last_no'] && ($now + $next_lange) < $max) { // 最終ページのリンクを表示
        if (($now + $next_lange) < ($max - 1)) { // 3点リーダー
            $text .= $cw_st . '...' . $cw_en;
        }
        $text .= $cw_st . '<a href="' . get_pagenum_link($max) . $getval . '" class="page ' . $args['child_class'] . '" ' . $args['attr'] . '" >' . $max . '</a>' . $cw_en;
    }

    if ($args['prev_next']) { // 進む
        if ($now == $max && $args['edge_pn']) { // 進み先がないが、それでも表示する設定
            if ($cw_st_off) {
                $text .= $cw_st_off . '<span class="move min next ' . $args['child_class'] . '">' . $args['next_label'] . '</span>' . $cw_en;
            } else {
                $text .= $cw_st_off . '<span class="move min next ' . $args['off_class'] . ' ' . $args['child_class'] . '">' . $args['next_label'] . '</span>' . $cw_en;
            }
        } elseif ($now <= $max - 1) { // 進み先がある
            $text .= $cw_st . '<a class="move min next ' . $args['child_class'] . '" href="' . get_pagenum_link($now + 1) . $getval . '" ' . $args['attr'] . ' >' . $args['next_label'] . '</a>' . $cw_en;
        }
    }

    if ($args['first_last']) { // 最後
        if ($now == $max && $args['edge_fl']) { // 必要ないが、それでも表示する設定
            if ($cw_st_off) {
                $text .= $cw_st_off . '<span class="move max last ' . $args['child_class'] . '">' . $args['last_label'] . '</span>' . $cw_en;
            } else {
                $text .= $cw_st_off . '<span class="move max last ' . $args['off_class'] . ' ' . $args['child_class'] . '">' . $args['last_label'] . '</span>' . $cw_en;
            }
        } elseif ($now <= $max - 1) { // 進み先がある
            $text .= $cw_st . '<a class="move max last ' . $args['child_class'] . '" href="' . get_pagenum_link($max) . $getval . '" ' . $args['attr'] . ' >' . $args['last_label'] . '</a>' . $cw_en;
        }
    }

    if ($args['op_right']) {
        $text .= $cw_st . sprintf($args['op_right'], $now, $max) . $cw_en; // 右オプション
    }
    $text .= '</' . $args['navi_type'] . '>';
    $text = wp_kses_post($text); // HTMLサニタイズ

    if ($args['echo']) {
        echo $text;
    }
    return $text;
}

/*==============================
アーカイブタクソノミー
==============================*/
//$ac_term = get_archive_term();	//アーカイブページのタクソノミー情報取得
//echo $ac_term->name;	//タクソノミー名
//echo $ac_term->slug;	//タクソノミースラッグ
//echo $ac_term->count;	//タクソノミー投稿数
//echo $ac_term->description;	//アーカイブタクソノミー説明文
//echo $ac_term->term_id;	//アーカイブタクソノミーID
//echo echo $cat_root = get_term($ac_term->parent,'c_cat', $args )->slug;	//親タクソノミースラッグ

function get_archive_term()
{	//アーカイブページのタクソノミー情報取得
    $id;
    $tax_slug;
    if (is_category()) {
        $tax_slug = 'category';
        $id = get_query_var('cat');
    } elseif (is_tag()) {
        $tax_slug = 'post_tag';
        $id = get_query_var('tag_id');
    } elseif (is_tax()) {
        $tax_slug = get_query_var('taxonomy');
        $term_slug = get_query_var('term');
        $term = get_term_by('slug', $term_slug, $tax_slug);
        $id = $term->term_id;
    }
    return get_term($id, $tax_slug);
}

/*━━━━━━━━━━━━━━━
後方互換
━━━━━━━━━━━━━━━*/
if (!function_exists("get_the_permalink")) {//「get_the_permalink」が未定義なら定義する
    function get_the_permalink()
    {
        return get_permalink();
    }
}
