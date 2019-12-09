<?php
/*━━━━━━━━━━━━━━━
混在コンテンツ対策+URL文字数削減
━━━━━━━━━━━━━━━*/
class URL_Optimize{
    public function __construct(){//get_headerの呼び出しに合わせて開始、wp_footerの終了に合わせて終了
        add_action('get_header',array(&$this,'get_header'),1);
        add_action('wp_footer',array(&$this,'wp_footer'),99999);
    }
    protected function Replace_URL($content){
        $top_url = preg_replace('!^(https?:\/\/.+?)\/(.*)$!','$1',H_URL);//ホームURLをドメイン部分だけに絞る

        //内部URLはルート相対パス化
        $content = preg_replace('!(<a .*href=[\'"])' . $top_url . '!im','$1',$content);
        $content = preg_replace('!(<link .*rel=[\'"]stylesheet[\'"] .*?href=[\'"])' . $top_url . '!im','$1',$content);
        $content = preg_replace('!(<script .*src=[\'"])' . $top_url . ':!im','$1',$content);
        $content = preg_replace('!(<img .*src=[\'"])' . $top_url . ':!im','$1',$content);

        //外部リソースはスキーム削除
        $content = preg_replace('!(<link .*rel=[\'"]stylesheet[\'"] .*?href=[\'"])https?:!im','$1',$content);
        $content = preg_replace('!(<script .*?src=[\'"])https?:!im','$1',$content);
        $content = preg_replace('!(<img .*?src=[\'"])https?:!im','$1',$content);
        return $content;
    }
    public function get_header(){
        ob_start(array(&$this,'Replace_URL'));
    }
    public function wp_footer(){
        ob_end_flush();
    }
}
new URL_Optimize();