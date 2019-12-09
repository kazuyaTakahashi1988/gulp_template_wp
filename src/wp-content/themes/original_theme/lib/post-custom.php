<?php
/*━━━━━━━━━━━━━━━
postのカスタマイズ
━━━━━━━━━━━━━━━*/
//$post_option = new post_option();
//$post_option->post_rename('新着情報');//投稿名を変える
//$post_option->post_remove();//エディタ、タグ、カテゴリーを消す

//$post_option->post_permalink('スラッグ');

/*━━━━━━━━━━━━━━━
記事タイトルプレースホルダー
━━━━━━━━━━━━━━━*/
function title_placeholder($title)
{	//記事タイトル入力欄のプレースホルダーを変更
    $screen = get_current_screen();
    if ($screen -> post_type == 'post') {
        $title = 'ここにタイトルを入力';
    } elseif ($screen -> post_type == '＜カスタム投稿名＞') {
        $title = 'ここにタイトルを入力';
    }
    return $title;
}
add_filter('enter_title_here', 'title_placeholder');

/*━━━━━━━━━━━━━━━
指定投稿タイプのタクソノミーをラジオボタン化
━━━━━━━━━━━━━━━*/
//new cat_change_radio('投稿名','タクソノミースラッグ');


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
 ######## ##     ## ##     ## ##     ## ########
    ##    ##     ## ##     ## ###   ### ##     ##
    ##    ##     ## ##     ## #### #### ##     ##
    ##    ######### ##     ## ## ### ## ########
    ##    ##     ## ##     ## ##     ## ##     ##
    ##    ##     ## ##     ## ##     ## ##     ##
    ##    ##     ##  #######  ##     ## ########
*/
// 管理画面の一覧にサムネイルを表示
//サムネイルカラム追加
function customize_manage_posts_columns($columns) {
    $columns['thumbnail'] = __('Thumbnail');
    return $columns;
}
add_filter( 'manage_posts_columns', 'customize_manage_posts_columns' );
//サムネイル画像表示
function customize_manage_posts_custom_column($column_name, $post_id) {
    if ( 'thumbnail' === $column_name) {
        $thum = get_the_post_thumbnail($post_id, 'small', array( 'style'=>'width:100px;height:auto;' ));
        if ( isset($thum) && $thum ) {
            echo $thum;
        } else {
            echo __('None');
        }
    }
}
add_action( 'manage_posts_custom_column', 'customize_manage_posts_custom_column', 10, 2 );


/*
 ###### ##       #####  ####### #######
##      ##      ##   ## ##      ##
##      ##      ####### ####### #######
##      ##      ##   ##      ##      ##
 ###### ####### ##   ## ####### #######
*/

class post_option
{	//postの内容変更
    public $link;
    public $newname;
    public function post_rename($name)
    {
        $this->newname = $name;
        add_action('admin_menu', array($this,'revcon_change_post_label'));
        add_action('init', array($this,'revcon_change_post_object'));
    }
    public function revcon_change_post_label()
    {
        global $menu;
        global $submenu;
        $menu[5][0] = $this->newname;
        $submenu['edit.php'][5][0] = $this->newname;
        $submenu['edit.php'][10][0] = $this->newname . 'を書く';
        $submenu['edit.php'][16][0] = 'タグ';
        echo '';
    }
    public function revcon_change_post_object()
    {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = $this->newname;
        $labels->singular_name = $this->newname;
        $labels->add_new = $this->newname . 'を書く';
        $labels->add_new_item = $this->newname . 'を書く';
        $labels->edit_item = $this->newname . 'の編集';
        $labels->new_item = $this->newname;
        $labels->view_item = $this->newname . 'を見る';
        $labels->search_items = $this->newname . 'を探す';
        $labels->not_found = $this->newname . 'はありません';
        $labels->not_found_in_trash = $this->newname . 'はありません';
        $labels->all_items = '全ての' . $this->newname;
        $labels->menu_name = $this->newname;
        $labels->name_admin_bar = $this->newname;
    }
    public function post_permalink($link)
    {
        $this->link = $link;
        add_filter('post_rewrite_rules', array($this,'custom_post_rewrite_rules')); //アクセスされた際に条件を判定する
        add_filter('pre_post_link', array($this,'custom_post_rewrite_structure'), 10, 2); //リンクを出力する時
    }
    public function custom_post_rewrite_rules($post_rewrite)
    {
        if ($post_rewrite) {
            $custom_rules = array();
            $prefix = $this->link . '/';
            foreach ($post_rewrite as $key => $post_rule) {
                $custom_rules[ $prefix . $key ] = $post_rule;
            }
            $post_rewrite = $custom_rules;//カスタマイズしたリンクをリターン
        }
        return $post_rewrite;//カスタマイズしたリンクを出力する
    }
    public function custom_post_rewrite_structure($permalink, $post)
    {
        if ($permalink && $post->post_type == 'post') { //$postはpostのインスタンス
            $permalink = '/' . $this->link . $permalink; //判定するリンクのカスタマイズ
        }
        return $permalink;
    }
    public function post_remove($editor=false, $cat=false, $tag=false)
    {
        if ($editor) {
            add_action('init', array($this,'remove_post_editor'));
        }
        if ($cat) {
            add_action('init', array($this,'remove_post_category'));
        }
        if ($tag) {
            add_action('init', array($this,'remove_post_tag'));
        }
    }
    public function remove_post_editor()
    {
        remove_post_type_support('post', 'editor');
    }
    public function remove_post_category()
    {
        unregister_taxonomy_for_object_type('category', 'post');
    }
    public function remove_post_tag()
    {
        unregister_taxonomy_for_object_type('post_tag', 'post');
    }
}


class cat_change_radio
{	//カテゴリーラジオボタン化
    public $postname;
    public $taxname;
    public function __construct($post, $term)
    {
        $this->postname = $post;
        $this->taxname  = $term;
        add_action('admin_print_footer_scripts', array($this,'cat_radio'), 21);
    }
    public function cat_radio()
    {//カテゴリー入力をラジオボタン化
        global $post_type;
        if ($post_type == $this->postname) {
            echo '<script type="text/javascript">
			jQuery(function($){
				$("#' . $this->taxname . 'checklist input[type=checkbox],.' . $this->taxname . '-checklist input[type=checkbox]").each(function(){
					$(this).replaceWith($(this).clone().attr("type","radio"));
				});
				$("#posts-filter").each(function(){	//投稿一覧ならクイック編集用の処理を追加
					if(inlineEditPost && inlineEditPost.edit){
						var _Edit = inlineEditPost.edit;//元のeditの処理を取り出しておく
						inlineEditPost.edit = function(id){//edit関数を書き換える
							_Edit.apply(inlineEditPost,arguments);//元のeditの処理を行う
							var t = this,rowData,editRow;
							if(typeof(id) === "object"){
								id = this.getId(id);
							}
							editRow = $("#edit-"+id);//編集エリアは既にcloneされているものを取得
							rowData = $("#inline_"+id);
							$(".post_category",rowData).each(function(){//hierarchicaltaxonomies
								var $t = $(this),taxname,term_ids = $t.text();
								if(term_ids){
									taxname = $t.attr("id").replace("_"+id,"");
									$("ul."+taxname+"-checklist :radio",editRow).val(term_ids.split(","));//カテゴリーのラジオボタンにチェック
								}
							});
							return false;
						};
					}
				});
			});
			</script>';
        }
    }
}