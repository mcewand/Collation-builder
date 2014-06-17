<!DOCTYPE html>
<html>
<?php

$css = "
.current {
  padding: 10px;
  margin-bottom: 10px;
}
.bifolio {
  width: 50%;
  float: left;
  padding: 5px;
}
.quire {
  width: 50%;
  float: right;
  padding: 5px;
}
.position-group {
  margin-bottom: 2px;
  padding: 3px;
  border: 1px solid;
}
.right-side {
  float:left;
}
";
queue_css_string($css);
echo head_css();

echo head(array('title'=>__("Collation"), 'bodyclass'=>'collate browse'));
?>

  <body>
    <div>
      <div class='content'>
        <div class="current">
          <div class="metadata">
            <div class="title">
              <h2><a href="/items/show/<?php echo $itemId; ?>" /><?php echo $title; ?></a></h2>
            </div>
            <span>
              <strong>Quire</strong> <?php echo $quire_num; ?>
              <strong>Folio</strong> <?php echo $position; ?>
              <strong>Side</strong> <?php echo $side; ?>
            </span>
          </div>
          <div class="image">
            <?php print_r($image); ?><br />
          </div>
        </div>
        <div class="related">
          <div class="bifolio">
            <h3>Bifolio Group</h3>
            <?php foreach ($bifold as $pos => $single): ?>
              <?php $extra_class = '';
                 if ($pos == 'Lv' || $pos == 'Fv') { $extra_class = 'right-side'; } ?>
              <div class="<?php echo $extra_class; ?>">
                Position: <?php echo $pos; ?>
                <div class="folio">
                  <?php echo $single; ?>
                </div>


              </div>
            <?php endforeach; ?>

          </div>
          <div class="quire">
            <h3>Organization of this Quire</h3>
            <?php foreach ($quire_full as $pos => $single): ?>
            <div class="position-group">
              Folio: <?php echo $pos; ?>
              <div class="recto">
                <?php $related_id = isset($single['R']['record_id']) ? $single['R']['record_id'] : "Missing"; ?>
                <?php if ($related_id != 'Missing'): ?>
                  <a href="/items/show/<?php echo $related_id; ?>">Recto</a>
                <?php else: ?>
                  <?php echo $related_id; ?>
                <?php endif; ?>
              </div>
              <div class="verso">
                <?php $related_id = isset($single['V']['record_id']) ? $single['V']['record_id'] : "Missing"; ?>
                <?php if ($related_id != 'Missing'): ?>
                  <a href="/items/show/<?php echo $related_id; ?>">Verso</a>
                <?php else: ?>
                  <?php echo $related_id; ?>
                <?php endif; ?>
              </div>


            </div>
            <?php endforeach; ?>

          </div>

        </div>
      </div>

    </div>
  </body>
</html>
