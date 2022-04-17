<?php
/*━━━━━━━━━━━━━━━
カスタム投稿宣言
━━━━━━━━━━━━━━━*/

// ↓ 記述例 ↓ 
//new add_custompost('postname','表示名',array(array('tax','分類','階層','一覧のカラムに追加')),エディタ,アイキャッチ,アーカイブ,'親階層');
//new add_custompost('postname','表示名',array(array('tax','分類',TRUE,,TRUE)),TRUE,TRUE,TRUE,'');
new add_custompost(
    'custompost',
    'custom投稿',
    array(array('tax-category01','カテゴリー名',true,true),
    array('tax-category02','カテゴリー名02',true,true),
    array('tax-category03','カテゴリー名03',true,true)), // tax
    true, // editer
    true, // thum 
    true, // has archive
    ''
);
new add_custompost(
    'custompost02',
    'custom投稿02',
    false, // tax
    false,// editer
    true, // thum 
    true, // has archive
    ''
);

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

class add_custompost
{
    public $post;       //投稿スラッグ
    public $postname;   //投稿名
    public $editor;     //エディタ有無
    public $thumbnail;  //アイキャッチ有無
    public $tax;        //タクソノミー
    public $supports;   //設定格納用
    public function __construct($post, $postname, $tax=false, $editor=false, $thumbnail=false, $archive=true, $parent='')
    {
        $this->post      = $post;
        $this->postname  = $postname;
        $this->editor    = $editor;
        $this->thumbnail = $thumbnail;
        $this->tax       = $tax;
        $this->archive   = $archive;
        $this->parent    = $parent;

        $this->supports = array('title','page-attributes');
        if ($this->editor) {
            $this->supports[] = 'editor'; //エディタON
        }
        if ($this->thumbnail) {
            $this->supports[] = 'thumbnail'; //アイキャッチON
        }
        add_action('init', array($this,'create_custompost'));
        add_action('restrict_manage_posts', array($this,'add_term_dropdown'));
    }
    public function create_custompost()
    {
        $labels = array( //ラベル設定
            'name'               => _x($this->postname, 'post type general name'),
            'singular_name'      => _x($this->postname, 'post type singular name'),
            'add_new'            => _x('新規追加', $this->post),
            'add_new_item'       => __('新規追加'),
            'edit_item'          => __('編集'),
            'new_item'           => __($this->postname),
            'view_item'          => __($this->postname . 'を見る'),
            'search_items'       => __($this->postname . 'を探す'),
            'not_found'          => __($this->postname . 'はありません'),
            'not_found_in_trash' => __($this->postname . 'はありません'),
            'parent_item_colon'  => "",
        );
        $postUrl = $this->post;
        if ($this->parent !== '') {
            $postUrl = $this->parent . '/' . $postUrl;
        }

        $args = array( //投稿設定
            'labels'             => $labels, //ラベル設定を反映
            'public'             => true,    //ラベル設定
            'publicly_queryable' => true,
            'show_ui'            => true,
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'show_in_rest'       => true,         //REST APIで発信
            'rest_base'          => $this->post,  //REST APIのURL
            'rewrite'            => array(
                'slug' => $postUrl,
                'with_front'         => false,
            ),
            'menu_position'      => 5,
            'supports'           => $this->supports,  //エディタやアイキャッチの有無
            'has_archive'        => $this->archive,   //アーカイブ
        );
        register_post_type($this->post, $args);
        add_post_type_support($this->post, 'revisions');  // リビジョン機能に対応
        if ($this->tax) {
            foreach ((array)$this->tax as $tax) { //配列の数だけタクソノミー定義
                register_taxonomy(
                    $tax[0], //スラッグ
                    $this->post, //適用するカスタム投稿
                    array(
                        'hierarchical'          => $tax[2], //階層設定
                        'update_count_callback' => '_update_post_term_count',
                        'label'                 => $tax[1],
                        'singular_label'        => $tax[1],
                        'show_admin_column'     => $tax[3],   // 管理画面の一覧内の列に表示する
                        'public'                => true,
                        'show_ui'               => true,
                        'show_in_rest'          => true,      // REST APIで発信
                    )
                );
            }
        }
    }
    // 管理画面のフィルターにカスタムタクソノミーのドロップダウンを追加
    public function add_term_dropdown($post_type)
    {
        if ($this->post === $post_type && $this->tax) { // 該当の投稿一覧で、カスタムタクソノミーが存在する
            foreach ((array)$this->tax as $tax) { //配列の数だけタクソノミー定義
                wp_dropdown_categories(array(
                    'show_option_all'    => 'すべての' . $tax[1], // 未選択時のテキスト
                    'show_count'         => true, // ヒットする投稿数を表示するか
                    'hide_empty'         => false, // 投稿の無いタームを隠すか
                    'selected'           => get_query_var($tax[0]), // 絞り込んだあとそのタームが選択されている状態を維持
                    'name'               => $tax[0], // select の name 属性
                    'taxonomy'           => $tax[0], // タクソノミーのスラッグ
                    'value_field'        => 'slug', // option の value属性の中身を何にするか
                    'hierarchical'       => true, // 階層をインデントで表現するか
                    'depth'              => 2, // 出力する階層の深さ
                ));
            }
        }
    }
}
