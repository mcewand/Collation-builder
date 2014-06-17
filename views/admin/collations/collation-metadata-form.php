<form id="collation-metadata-form" method="post" class="collation-builder">
    <section class="seven columns alpha">
    <fieldset>
        <legend><?php echo __('Collation Metadata'); ?></legend>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('name', __('Collation name')); ?>
                <p class="explanation"><?php echo __('Display name for the collation group.'); ?></p>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('name', $collation->name); ?>
            </div>
        </div>

    </fieldset>
    </section>
    <section class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('save_collation', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php if ($collation->exists()): ?>
                <?php //echo collation_builder_link_to_collation($collation, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank')); ?>
                <?php echo link_to($collation, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
            <?php endif; ?>
            <div id="public-featured">
                <div class="public">
                    <label for="public"><?php echo __('Public'); ?>:</label>
                    <?php echo $this->formCheckbox('public', $collation->public, array(), array('1', '0')); ?>
                </div>
                <div class="featured">
                    <label for="featured"><?php echo __('Featured'); ?>:</label>
                    <?php echo $this->formCheckbox('featured', $collation->featured, array(), array('1', '0')); ?>
                </div>
            </div>
        </div>
    </section>
</form>

<script type="text/javascript" charset="utf-8">
//<![CDATA[
    jQuery(window).load(function() {
        Omeka.wysiwyg();
    });
//]]>
</script>
