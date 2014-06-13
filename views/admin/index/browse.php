<?php

echo head(array('title'=>__("Collation Groups"), 'bodyclass'=>'collate browse'));

?>
<div>
<?php
  $collateTable = get_db()->getTable('Collate');
  //$totalEmbeds = $embedTable->totalEmbeds($item->id);

?>

<div id='primary'>

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
            <span class='title'><a href='<?php echo url('../collation/' . metadata('collation', 'id')); ?>'>
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
