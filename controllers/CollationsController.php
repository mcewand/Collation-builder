<?php

class CollationBuilder_CollationsController extends Omeka_Controller_AbstractActionController
{

    public function init()
    {
        $this->_helper->db->setDefaultModelName('Collate');
    }

    public function browseAction()
    {

        parent::browseAction();
        // Get all collation groups from the table

    }

    public function addAction()
    {
        $record = new Collate;
        // Check if the form was submitted.
        if ($this->getRequest()->isPost()) {
            // Set the POST data to the record.
            $record->setPostData($_POST);
            // Save the record. Passing false prevents thrown exceptions.
            if ($record->save(false)) {
                $successMessage = $this->_getEditSuccessMessage($record);
                if ($successMessage) {
                    $this->_helper->flashMessenger($successMessage, 'success');
                }
                $this->_helper->redirector->gotoRoute(array('action' => 'browse'), 'collations-browse');
            // Flash an error if the record does not validate.
            } else {
                $this->_helper->flashMessenger($record->getErrors());
            }
        }


        $this->view->collation = $record;
    }

    public function editAction()
    {
        // Look up the collation record by url param
        $itemId = $this->getParam('id');
        $collationTable = get_db()->getTable('Collate');
        $collation = $collationTable->collationName($itemId);

        $record = new Collate;
        $record->name = $collation;
        $record->id = $itemId;


        // Check if the form was submitted.
        if ($this->getRequest()->isPost()) {
            // Set the POST data to the record.
            $record->setPostData($_POST);
            // Save the record. Passing false prevents thrown exceptions.
            if ($record->save(false)) {
                $successMessage = $this->_getEditSuccessMessage($record);
                if ($successMessage) {
                    $this->_helper->flashMessenger($successMessage, 'success');
                }
                $this->_helper->redirector->gotoRoute(array('action' => 'browse'), 'collations-browse');
            // Flash an error if the record does not validate.
            } else {
                $this->_helper->flashMessenger($record->getErrors());
            }
        }


        $this->view->collation = $record;
    }
}
