<?php /* body js */ ?>
<script src="<?php echo THEME_URL; ?>/assets/js/min/common.min.js"></script>
<?php if ($args['pageJs']) {
    echo '<script src="' . THEME_URL . '/assets/js/min/' . $args['pageJs'] . '"></script>';
} ?>

