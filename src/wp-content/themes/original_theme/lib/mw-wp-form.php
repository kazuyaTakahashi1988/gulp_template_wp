<?php
/*━━━━━━━━━━━━━━━
エラーメッセージ変更
━━━━━━━━━━━━━━━*/
function my_validation_rule($Validation, $data, $Data)
{
    $Validation->set_rule('user1', 'noEmpty', array('message' => '※'));
    $Validation->set_rule('user2', 'noEmpty', array('message' => '※'));
    $Validation->set_rule('email1', 'mail', array('message' => '※'));
    $Validation->set_rule('email1', 'noEmpty', array('message' => '※'));
    $Validation->set_rule('text', 'noEmpty', array('message' => '※'));
    return $Validation;
}
//add_filter('mwform_validation_mw-wp-form-000','my_validation_rule',10,3);

/*━━━━━━━━━━━━━━━
内容分岐
━━━━━━━━━━━━━━━*/
function autoback_my_mail($Mail_raw, $values, $Data)
{
    if ($Data->get('hoge') == '') {	//指定の項目を選んでいた場合
//		$Mail_raw->from    = ''; //送信元メールアドレスを変更
//		$Mail_raw->sender  = ''; //送信者名を変更
//		$Mail_raw->subject = ''; //件名を変更
//		$Mail_raw->body    = ''; //本文を変更
    }
    return $Mail_raw;
}
//add_filter('mwform_auto_mail_raw_mw-wp-form-000','autoback_my_mail',10,3);

/*━━━━━━━━━━━━━━━
URL引数
━━━━━━━━━━━━━━━*/
function my_mwform_value($value, $name)
{
    // $_GET['hoge']があったら、name属性がhogeの項目の初期値に設定
    if ($name === 'hoge' && !empty($_GET['hoge']) && !is_array($_GET['hoge'])) {
        return $_GET['hoge'];
    }
    return $value;
}

// 管理画面で作成したフォームの場合、フック名の後のフォーム識別子は「mw-wp-form-xxx」
add_filter( 'mwform_value_mw-wp-form-xxx', 'my_mwform_value', 10, 2 );

// 管理者宛アドレスをクエリにて指定する処理
// add_filter( 'mwform_admin_mail_mw-wp-form-xxx', function( $Mail, $values, $Data ) {
//     $Mail->to = $Data->get( 'sendmail' );
//     return $Mail;
// }, 10, 3 );