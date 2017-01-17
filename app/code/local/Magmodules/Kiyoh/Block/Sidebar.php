<?php
/**
 * Magmodules.eu - http://www.magmodules.eu
 *
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category      Magmodules
 * @package       Magmodules_Kiyoh
 * @author        Magmodules <info@magmodules.eu)
 * @copyright     Copyright (c) 2017 (http://www.magmodules.eu)
 * @license       http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Magmodules_Kiyoh_Block_Sidebar extends Mage_Core_Block_Template
{

    /**
     * @param $sidebar
     *
     * @return bool|object
     */
    public function getSidebarCollection($sidebar)
    {
        $enabled = '';
        $qty = '5';
        if (Mage::getStoreConfig('kiyoh/general/enabled')) {
            if ($sidebar == 'left') {
                $qty = Mage::getStoreConfig('kiyoh/sidebar/left_qty');
                $enabled = Mage::getStoreConfig('kiyoh/sidebar/left');
            }

            if ($sidebar == 'right') {
                $qty = Mage::getStoreConfig('kiyoh/sidebar/right_qty');
                $enabled = Mage::getStoreConfig('kiyoh/sidebar/right');
            }
        }

        if ($enabled) {
            $shopId = Mage::getStoreConfig('kiyoh/general/api_id');
            $collection = Mage::getModel("kiyoh/reviews")->getCollection();
            $collection->setOrder('date_created', 'DESC');
            $collection->addFieldToFilter('status', 1);
            $collection->addFieldToFilter('sidebar', 1);
            $collection->addFieldToFilter('shop_id', array('eq' => array($shopId)));
            $collection->setPageSize($qty);
            $collection->load();
            return $collection;
        } else {
            return false;
        }
    }

    /**
     * @param        $sidebarreview
     * @param string $sidebar
     *
     * @return string
     */
    public function formatContent($sidebarreview, $sidebar = 'left')
    {
        $content = $sidebarreview->getPositive();
        if ($sidebar == 'left') {
            $charLimit = Mage::getStoreConfig('kiyoh/sidebar/left_lenght');
        }

        if ($sidebar == 'right') {
            $charLimit = Mage::getStoreConfig('kiyoh/sidebar/right_lenght');
        }

        $content = Mage::helper('core/string')->truncate($content, $charLimit, ' ...');
        if ($content) {
            $content = '"' . $content . '"';
        }

        return $content;
    }

    /**
     * @param string $sidebar
     *
     * @return bool|string
     */
    public function getReviewsUrl($sidebar = 'left')
    {
        $link = '';
        $url = '';
        $target = '';

        if ($sidebar == 'left') {
            $link = Mage::getStoreConfig('kiyoh/sidebar/left_link');
        }

        if ($sidebar == 'right') {
            $link = Mage::getStoreConfig('kiyoh/sidebar/right_link');
        }

        if ($link == 'internal') {
            $url = $this->getUrl('kiyoh');
        }

        if ($link == 'external') {
            $url = Mage::getStoreConfig('kiyoh/general/url');
            $target = 'target="_blank"';
        }

        if ($url) {
            return '<a href="' . $url . '" ' . $target . '>' . $this->__('View all reviews') . '</a>';
        }

        return false;
    }

    /**
     * @param string $sidebar
     *
     * @return bool
     */
    public function getSnippetsEnabled($sidebar = 'left')
    {
        $enabled = '';
        if ($sidebar == 'left') {
            $enabled = Mage::getStoreConfig('kiyoh/sidebar/left_snippets');
        }

        if ($sidebar == 'right') {
            $enabled = Mage::getStoreConfig('kiyoh/sidebar/right_snippets');
        }

        if ($enabled) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getTotalScore()
    {
        return $this->helper('kiyoh')->getTotalScore();
    }

}