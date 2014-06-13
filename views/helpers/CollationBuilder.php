<?php
/*
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package CollationBuilder\View\Helper
 */
class CollationBuilder_View_Helper_CollationBuilder extends Zend_View_Helper_Abstract
{
    public function collationBuilder($type, item $item = NULL)
    {
        switch ($type) {
            case 'sidebar':
                return $this->_getSidebarDisplay();
            case 'public':
                return $this->_getItemDisplay($item);
            default:
                return;
        }

    }

    protected function _getSidebarDisplay()
    {
        $html = '<div class="collation-sidebar panel">';
        $html .= '<h4>Collation</h4>';

        $html .= '</div>';

        return $html;
    }

    protected function _getItemDisplay($item)
    {
        $html = "<div id='collation'><h2>" . __('Collation') . "</h2>";
        $html .= "<a href='/items/collation/" . $item->id . "' />View Collation</a>";
        $html .= "</div>";

        return $html;

    }
}

