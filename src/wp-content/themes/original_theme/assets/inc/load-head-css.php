<?php /* head css */ ?>
<link rel="stylesheet" href="<?php echo THEME_URL; ?>/assets/css/style.css" />
<?php if ($args['pageCss']) {
    echo '<link rel="stylesheet" href="' . THEME_URL . '/assets/css/' . $args['pageCss'] . '">';
} ?>

