<?php

class CollationBuilder_IndexController extends Omeka_Controller_AbstractActionController
{

    public function init()
    {
        $this->_helper->db->setDefaultModelName('Collate');
    }

    public function collateAction()
    {
        // Lookup current item metadata
        $itemId = $this->getParam('id');
        $current = get_record_by_id('Item', $itemId);

        $title = metadata($current, array('Dublin Core', 'Title'));
        $this->view->title = $title;
        $this->view->itemId = $itemId;

        $image = files_for_item(array(), array(), $current);
        $this->view->image = $image;


        $metadata = item_type_elements($current);

        $this->view->quire = $metadata['Quire'];
        $this->view->position = $metadata['Position'];
        $this->view->side = $metadata['Side'];

        // Create an indexed array for each position.
        $totalPositions = $metadata['Total Positions'];
        $quire = array();
        for ($i = 1; $i <= $totalPositions; $i++) {
            $quire[$i] = array();
        }


        // Find matching quire items

        /*
        $sql = "SELECT t.record_id,
            FROM
                {$db->prefix}element_texts t,
                LEFT JOIN {$db->prefix}elements e
                    ON t.element_id=e.id
            WHERE
                t.record_id != ". $itemId ."
                AND
                (e.id = t.element_id AND e.name = 'Quire')";
        */

        // Get everything from the Collation Group
        $db = get_db();
        // @todo OMG fix this!
        $sql = "
            SELECT t.record_id FROM omeka_element_texts AS t
                INNER JOIN omeka_elements AS e
                    ON (e.id = t.element_id AND e.name ='Collation Group')
                WHERE
                    (e.name = 'Collation Group' AND t.text = " . $metadata['Collation Group'] . ")";
                   // AND
                   // (t.record_id != ". $itemId.")";
        $collation_group = $db->fetchAll($sql);

        $this->view->helper1 = $collation_group;

        $cg = array();
        foreach ($collation_group as $record_id) {
            $cg[] = $record_id['record_id'];
        }

        // Get everything from this quire

        $db = get_db();
        // @todo OMG fix this!
        $sql = "
            SELECT t.record_id FROM omeka_element_texts AS t
                INNER JOIN omeka_elements AS e
                    ON (e.id = t.element_id AND e.name ='Quire')
                WHERE
                    (e.name = 'Quire' AND t.text = " . $metadata['Quire'] . ")
                    AND
                    (t.record_id IN (" . implode(',', $cg) . "))";
        $quire_group = $db->fetchAll($sql);

        // This gives me the ID's for everything in the quire.
        //$this->view->helper2 = $quire_group;

        // Load each item in the Quire
        foreach ($quire_group as $item_id) {
            $record = get_record_by_id('Item', $item_id['record_id']);
            $metadata_rel = item_type_elements($record);

            $quire[$metadata_rel['Position']][$metadata_rel['Side']] = $item_id;
        }

        // Now we have the postion for each item in the quire, in an indexed array
        $this->view->quire_full = $quire;

        //print_r( $metadata);

        $found = FALSE;
        while($found == FALSE) {
            reset($quire);
            $first_key = key($quire);
            print 'f: ' . $first_key;
            end($quire);
            $last_key = key($quire);
            print 'l: ' . $last_key;
            if ($metadata['Position'] == $first_key) {
                $found = TRUE;
            } elseif ($metadata['Position'] == $last_key) {
                $found = TRUE;
            } else {
                array_shift($quire);
                array_pop($quire);
            }
        }

        if (isset($quire[$first_key]['R']['record_id'])) {
            $fr = get_record_by_id('Item', $quire[$first_key]['R']['record_id']);
            $fr_image = files_for_item(array(), array(), $fr);
        } else {
            $fr_image = 'Missing';
        }

        if (isset($quire[$first_key]['V']['record_id'])) {
            $fv = get_record_by_id('Item', $quire[$first_key]['V']['record_id']);
            $fv_image = files_for_item(array(), array(), $fv);
        } else {
            $fv_image = 'Missing';
        }

        if (isset($quire[$last_key]['R']['record_id'])) {
            $lr = get_record_by_id('Item', $quire[$last_key]['R']['record_id']);
            $lr_image = files_for_item(array(), array(), $lr);
        } else {
            $lr_image = 'Missing';
        }

        if (isset($quire[$last_key]['V']['record_id'])) {
            $lv = get_record_by_id('Item', $quire[$last_key]['V']['record_id']);
            $lv_image = files_for_item(array(), array(), $lv);
        } else {
            $lv_image = 'Missing';
        }
        // Now that we've set the outside end of the array, use last and first key to get both positions
        $this->view->bifold = array(
            //'Fr' => $quire[$first_key]['R']['record_id'],
            'Fr' => $fr_image,
            'Lv' => $lv_image,

            'Fv' => $fv_image,
            'Lr' => $lr_image,
        );


        //$this->view->helper3 = $quire;




        $this->view->quire = array();
        foreach ($quire_group as $related) {
            if ($related['record_id'] != $itemId) {
                $item = get_record_by_id('Item', $related['record_id']);
                $title = metadata($item, array('Dublin Core', 'Title'));

                $metadata = item_type_elements($item);
                $image = files_for_item(array(), array(), $item);
                if (!$image) {
                    $image = 'No image for this item.';
                }
                $this->view->quire[] = array('id' => $item->id, 'title' => $title, 'image' => $image );
            }
        }

    }

    public function browseAction()
    {
        parent::browseAction();
        // Get all collation groups from the table
        //$this->view->total = $this->_helper->db->getTable()->totalEmbeds();

    }

    public function addAction()
    {

    }
}
