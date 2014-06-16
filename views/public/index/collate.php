<!DOCTYPE html>
<html>
<?php
echo head(array('title'=>__("Collation"), 'bodyclass'=>'collate browse'));
?>

  <body>
    <div>
      <div class='content'>
        <div class="current">
          <a href="/items/show/<?php echo $itemId; ?>" /><?php echo $title; ?></a> <br />
          <?php print_r($image); ?><br />
          Quire: <?php echo $quire_num; ?> <br />
          Position: <?php echo $position; ?> <br />
          Side: <?php echo $side; ?> <br />
        </div>
        <div class="related">
          <div class="bifolio" style="background-color:#C0C0C0;">
            Bifolio Group:
            <?php foreach ($bifold as $pos => $single): ?>
            <div>
              Position: <?php echo $pos; ?>
              <div class="folio">
                <?php echo $single; ?>
              </div>


            </div>
            <?php endforeach; ?>

          </div>
          <div class="quire" style="background-color:#00FFFF;">
            Organization of this Quire:
            <?php foreach ($quire_full as $pos => $single): ?>
            <div>
              Position: <?php echo $pos; ?>
              <div class="recto">
                Recto: <?php echo isset($single['R']['record_id']) ? $single['R']['record_id'] : "Missing"; ?>
              </div>
              <div class="verso">
                Verso: <?php echo isset($single['V']['record_id']) ? $single['V']['record_id'] : "Missing"; ?>
              </div>


            </div>
            <?php endforeach; ?>

          </div>

          <?php// print_r($helper2); ?> <br />
          <?php //print_r($helper3); ?>
        </div>
      </div>

    </div>
  </body>
</html>
