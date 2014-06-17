<?php
// Set the created item type as a static variable after it's been added


class CollationBuilderPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('install',
                              'uninstall',
                              'define_routes',
                              'public_items_show',
                              'admin_items_show_sidebar',
                              'admin_items_browse_detailed_each',
                              'initialize'
                              );

    protected $_filters = array('admin_navigation_main');

    public function hookInitialize()
    {
        //add_translation_source(dirname(__FILE__) . '/languages');
    }

    public function hookInstall()
    {
        // Check whether a 'folio' item type exists


        // Create a 'folio' item type
        $metadata = array(
            'name' => 'Folio',
            'description' => 'Single page records for a manuscript.',
        );
        $elementInfos = array(
            array(
                'name' => 'Quire',
                'description' => 'Quire that this belongs to (numeric value)',
                'order' => 1,
            ),
            array(
                'name' => 'Folio Number',
                'description' => 'ex: F2',
                'order' => 2,
            ),
            array(
                'name' => 'Side',
                'description' => 'Recto/Verso',
                'order' => 3,
            ),
            array(
                'name' => 'Collation Group',
                'description' => 'This needs to match an existing Collation Group on Import',
                'order' => 4,
            ),
            array(
                'name' => 'Position',
                'description' => 'Position within the Quire (numeric value)',
                'order' => 5,
            ),
            array(
                'name' => 'Total Positions',
                'description' => 'This needs to match an existing Collation Group on Import',
                'order' => 6,
            ),
        );

        $newItemType = insert_item_type($metadata, $elementInfos);
        if (isset($newItemType->name)) {
            _log('[CB]Created new item type ' . $newItemType->name);
        }

        // Create the base data table
        $db = $this->_db;
        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->Collate` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` text,
              `note` text,
              PRIMARY KEY (`id`)
            ) ENGINE=INNODB ; ";
        $db->query($sql);

        // Create the supporting table
        $db2 = get_db();
        $sql = "
            CREATE TABLE IF NOT EXISTS `{$db2->prefix}quire_groups` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `collation_group` int(10) unsigned NOT NULL,
              `quire_num` int(10) unsigned,
              `positions_per_quire` int(3) unsigned,
              `note` text,
              PRIMARY KEY (`id`)
            ) ENGINE=INNODB ; ";
        $db2->query($sql);
    }

    // Clean up the db table on removal
    public function hookUninstall()
    {
       $db = $this->_db;
        $sql = "DROP TABLE IF EXISTS `$db->Collate`; ";
        $db->query($sql);
    }

    /**
     * Modify the ACL to include an 'ExhibitBuilder_Exhibits' resource.
     *
     * Requires the module name as part of the ACL resource in order to avoid naming
     * conflicts with pre-existing controllers, e.g. an ExhibitBuilder_ItemsController
     * would not rely on the existing Items ACL resource.
     *
     * @param array $args Zend_Acl in the 'acl' key
     */
    function collation_builder_define_acl($args)
    {
        $acl = $args['acl'];

        /*
         * NOTE: unless explicitly denied, super users and admins have access to all
         * of the defined resources and privileges.  Other user levels will not by default.
         * That means that admin and super users can both manipulate exhibits completely,
         * but researcher/contributor cannot.
         */
        $acl->addResource('CollationBuilder_Collations');

        $acl->allow(null, 'CollationBuilder_Collations',
            array('show', 'summary', 'showitem', 'browse', 'tags'));

        // Allow contributors everything but editAll and deleteAll.
        $acl->allow('contributor', 'CollationBuilder_Collations',
            array('add', 'delete-confirm', 'item-container',
                'editSelf', 'deleteSelf', 'showSelfNotPublic'));

        $acl->allow(null, 'CollationBuilder_Collations', array('edit', 'delete'),
            new Omeka_Acl_Assert_Ownership);
    }



    public function hookPublicItemsShow($args)
    {
        $item = $args['item'];

        // Get the collation sidebar view
        echo get_view()->collationBuilder('public', $item);
    }

    public function hookAdminItemsBrowseDetailedEach($args)
    {
        $hasCollation = FALSE;
        // Check if this has an associated collation
        $html = '<span><strong>Collation:</strong> ';

        if ($hasCollation) {
            $html .= 'Link goes here';
        } else {
            $html .= 'No Collation';
        }
        $html .= '</div>';
        echo $html;
    }

    /**
     * Add a panel to the sidebar on each item, to show collation
     */
    public function hookAdminItemsShowSidebar($args)
    {
        // Get the collation sidebar view
        echo get_view()->collationBuilder('sidebar');
    }

    public function hookDefineRoutes($array)
    {
        $router = $array['router'];
        $router->addRoute(
            'collations-browse',
            new Zend_Controller_Router_Route(
                'collation-builder',
                array(
                    'module' => 'collation-builder',
                    'controller' => 'collations',
                    'action' => 'browse'
                )
            )
        );
        $router->addRoute(
            'collations-add',
            new Zend_Controller_Router_Route(
                'collation-builder/add',
                array(
                    'module' => 'collation-builder',
                    'controller' => 'collations',
                    'action' => 'add'
                )
            )
        );
        $router->addRoute(
            'collations-edit',
            new Zend_Controller_Router_Route(
                'collation-builder/edit/:id',
                array(
                    'module' => 'collation-builder',
                    'controller' => 'collations',
                    'action' => 'edit'
                )
            )
        );
        $router->addRoute(
            'collation-item',
            new Zend_Controller_Router_Route(
                'items/collation/:id',
                array(
                    'module' => 'collation-builder',
                    'controller' => 'index',
                    'action' => 'collate'
                )
            )
        );
    }

    public function filterAdminNavigationMain($navArray)
    {
        $navArray[] = array(
            'label' => __('Collation Groups'),
            'uri' => url('collation-builder'),
            //'resource' => 'CollationBuilder_CollationsController',
            //'privilege' => 'browse'
        );

        return $navArray;
    }
}
