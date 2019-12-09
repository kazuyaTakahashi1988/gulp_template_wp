<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="ja" class="ie6 ielt8" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if IE 7 ]>    <html lang="ja" class="ie7 ielt8" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if IE 8 ]>    <html lang="ja" class="ie8" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="ja">
<!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <?php if (is_archive()): // アーカイブの場合はOGPタグを自力で生成 画像はAll in One SEO Pack のデフォルト画像を要設定?>
        <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
        <meta property="og:title" content="<?php wp_title('|', true, 'right'); bloginfo('name'); ?>" />
        <meta name="twitter:title" content="<?php wp_title('|', true, 'right'); bloginfo('name'); ?>" />
        <meta name="twitter:domain" content="<?php echo $_SERVER["HTTP_HOST"] ; ?>" />
        <meta property="og:url" content="<?php echo(empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>" />
        <meta property="og:image" content="<?php echo $aioseop_options['modules']['aiosp_opengraph_options']['aiosp_opengraph_dimg']; ?>" />
        <meta name="twitter:image" content="<?php echo $aioseop_options['modules']['aiosp_opengraph_options']['aiosp_opengraph_dimg']; ?>" />
        <meta itemprop="image" content="<?php echo $aioseop_options['modules']['aiosp_opengraph_options']['aiosp_opengraph_dimg']; ?>" />
        <meta property="og:type" content="article" />
        <meta name="twitter:card" content="summary_large_image" />
    <?php endif; ?>

    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" type="text/css" media="all" href="<?php echo T_URL; ?>css/*****.min.css?v=<?php echo filemtime(T_PATH . 'css/*****.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo T_URL; ?>css/*****.min.css?v=<?php echo filemtime(T_PATH . 'css/*****.min.css'); ?>" />

    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,500,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo T_URL; ?>img/favicon.ico">

    <?php wp_head(); ?>

    <meta name="theme-color" content="#0874dc">

</head>

<body <?php body_class(); ?>>


<!-- ▼▼▼ header start ▼▼▼ -->
<header id="header" class="header" itemscope itemtype="https://schema.org/WPHeader">
<br>
<br>
<br>
<p style="text-align: center;"><?php echo '<b>ヘッダーだよ</b>' ?></p>
<br>
<br>
<br>
</header>
<!-- ▲▲▲ header end   ▲▲▲ -->

