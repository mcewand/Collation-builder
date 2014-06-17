<?php

echo head(array('title'=>__("Collation Groups"), 'bodyclass'=>'collate browse'));

?>
<div>

<div id='primary'>

<?php if (!count($collates)): ?>
    <div id="no-collations">
        <h2><?php echo __('There are no collation groups yet.'); ?></h2>
        <a href="<?php echo html_escape(url('collation-builder/add')); ?>" class="big green add button"><?php echo __('Add a collation group'); ?></a></p>
    </div>

<?php else: ?>

<div class="table-actions">
    <a href="<?php echo html_escape(url('collation-builder/add')); ?>" class="small green add button"><?php echo __('Add a collation group'); ?></a>
</div>
<?php endif; ?>




<div class="pagination"><?php echo pagination_links(); ?></div>

<table>
    <thead>
    <tr>
        <?php
        $browseHeadings[__('ID')] = null;
        $browseHeadings[__('Name')] = null;
        echo browse_sort_links($browseHeadings, array('link_tag' => 'th scope="col"', 'list_tag' => ''));
        ?>
    </tr>
    </thead>
    <tbody>

    <?php foreach(loop('collation', $collates) as $collation):?>
    <tr>
        <td>
            <span class='title'><a href='<?php echo url('collation-builder/edit/' . metadata('collation', 'id')); ?>'>
                <?php echo metadata('collation', 'name'); ?></a></span>
            <ul class='action-links group'>
            <li class='details-text'>
              <strong>Quires:</strong> <?php //echo metadata('collation', 'quires'); ?>
            </li>
            </ul>
        </td>
        <td><?php echo metadata('collation', 'id'); ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>


<?php echo foot(); ?>
