<?php
    $title = __('Edit Collation Group');
    echo head(array('title' => html_escape($title), 'bodyclass' => 'collation'));
?>
    <div id="collation-breadcrumb">
        <a href="<?php echo html_escape(url('collation-builder')); ?>"><?php echo __('Collation Projects'); ?></a> &gt;
        <?php echo html_escape($title);?>
    </div>

<?php echo flash(); ?>

<?php echo common('collation-metadata-form', array('collation' => $collation, 'theme' => null), 'collations'); ?>

<?php echo foot(); ?>
