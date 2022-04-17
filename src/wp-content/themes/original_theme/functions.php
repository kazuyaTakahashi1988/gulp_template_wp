<?php
// require_once('lib/aws-fix.php');             // AWS向けの調整
// require_once('lib/head-custom.php');         // ヘッダー改変
// require_once('lib/admin-layout.php');        // 管理画面レイアウト
// require_once('lib/ssl.php');                 // SSL強制対応
// require_once('lib/editor.php');              // エディタ改変

require_once('lib/const.php');               // 定数
require_once('lib/souce-del.php');           // 不要なソース(絵文字・ver情報など)の削除
require_once('lib/login.php');               // ログイン画面カスタマイズ
require_once('lib/img.php');                 // 画像
require_once('lib/post-custom.php');         // 投稿改変
require_once('lib/func.php');                // 関数
require_once('lib/shortcode.php');           // ショートコード
require_once('lib/cache.php');               // キャッシュ

/* ----------------------------------
    カスタム投稿宣言
-------------------------------------*/
// require_once('lib/add-custompost.php');      
// コメントイン・アウトやファイル編集をしたらマメに管理画面「設定→パーマリンク→変更を保存」の更新をしてください。

/* ----------------------------------
    カスタムフィールド宣言
-------------------------------------*/
// require_once('lib/smart-custom-fields.php');
// コメントインする際はプラグイン「Smart Custom Fields」の DL ＆ 有効化が必要です。

/* ----------------------------------
    MW-WP-Form コンタクトフォーム
-------------------------------------*/
// require_once('lib/mw-wp-form.php');
// コメントインする際はプラグイン「MW WP Form」の DL ＆ 有効化が必要です。