<?php

class Table_Collate extends Omeka_Db_Table
{

    public function collationName($itemId = null)
    {
        $alias = $this->getTableAlias();
        $db = $this->_db;
        if($itemId) {
            $sql = "SELECT name FROM {$db->Collate} WHERE id=$itemId";
        }
        $row = $this->getDb()->fetchRow($sql);

        return $row['name'];
    }
}
