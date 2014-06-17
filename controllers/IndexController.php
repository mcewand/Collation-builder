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

        $this->view->quire_num = $metadata['Quire'];
        $this->view->position = $metadata['Position'];
        $this->view->side = $metadata['Side'];

        // Create an indexed array for each position.
        $totalPositions = $metadata['Total Positions'];
        $quire = array();
        for ($i = 1; $i <= $totalPositions; $i++) {
            $quire[$i] = array();
        }


        // Find matching quire items

        // Get everything from the Collation Group
        $db = get_db();
        // @todo OMG fix this!
        $sql = "
            SELECT t.record_id FROM omeka_element_texts AS t
                INNER JOIN omeka_elements AS e
                    ON (e.id = t.element_id AND e.name ='Collation Group')
                WHERE
                    (e.name = 'Collation Group' AND t.text = " . $metadata['Collation Group'] . ")";

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

        // Load each item in the Quire
        foreach ($quire_group as $item_id) {
            $record = get_record_by_id('Item', $item_id['record_id']);
            $metadata_rel = item_type_elements($record);

            $quire[$metadata_rel['Position']][$metadata_rel['Side']] = $item_id;
        }

        // Now we have the postion for each item in the quire, in an indexed array
        $this->view->quire_full = $quire;

        $found = FALSE;
        while($found == FALSE) {
            reset($quire);
            $first_key = key($quire);
            end($quire);
            $last_key = key($quire);

            if ($metadata['Position'] == $first_key) {
                $found = TRUE;
            } elseif ($metadata['Position'] == $last_key) {
                $found = TRUE;
            } else {
                array_shift($quire);
                array_pop($quire);
            }
        }


        // Now that we've set the outside end of the array,
        // use last and first key to get both positions
        $struc = array(
            'fr' => array(
                'pos' => 'first_key',
                'side' => 'R'
            ),
            'fv' => array(
                'pos' => 'first_key',
                'side' => 'V'
            ),
            'lr' => array(
                'pos' => 'last_key',
                'side' => 'R'
            ),
            'lv' => array(
                'pos' => 'last_key',
                'side' => 'V'
            ),
        );

        foreach ($struc as $k=>$v) {
           if (isset($quire[${$v['pos']}][$v['side']]['record_id'])) {
                ${$k} = get_record_by_id('Item', $quire[${$v['pos']}][$v['side']]['record_id']);
                ${$k . '_image'} = files_for_item(array(), array(), ${$k});
            } else {
                ${$k . '_image'} = 'Missing';
            }

        }

        $this->view->bifold = array(
            'Lv' => $lv_image,
            'Fr' => $fr_image,

            'Fv' => $fv_image,
            'Lr' => $lr_image,
        );

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
}
