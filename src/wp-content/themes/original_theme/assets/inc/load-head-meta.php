<?php /* head meta */ ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo $args['pageDes'] ?: DEFAULT_DES; ?>">
<meta name="keywords" content="<?php echo $args['pageKey'] ?: DEFAULT_KEY; ?>">
<title><?php echo $args['pageTtl'] ?: SITE_NAME; ?></title>

<?php /* OGP meta */ ?>
<meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
<meta property="og:url" content="<?php echo (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>">
<meta property="og:title" content="<?php echo $args['pageTtl'] ?: SITE_NAME; ?>">
<meta property="og:description" content="<?php echo $args['pageDes'] ?: DEFAULT_DES; ?>">
<meta property="og:image" content="<?php echo $args['ogpImg'] ?: DEFAULT_IMG; ?>">
<meta property="og:locale" content="ja_JP">
<meta property="fb:admins" content="xxxxxxxxx">
<?php if (IS_HOME || IS_FRONT_PAGE) {
    echo '<meta property="og:type" content="website">';
} else {
    echo '<meta property="og:type" content="article">';
}
?>

<?php /* favicon */ ?>
<link rel="icon" href="<?php echo THEME_URL; ?>/assets/images/common/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="apple-touch-icon" href="<?php echo THEME_URL; ?>/assets/images/common/apple-touch-icon.png">
