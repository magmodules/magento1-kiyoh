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

class Magmodules_Kiyoh_Model_Log extends Mage_Core_Model_Abstract
{

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('kiyoh/log');
    }

    /**
     * @param        $type
     * @param        $storeId
     * @param string $review
     * @param string $inivation
     * @param        $time
     * @param string $cType
     * @param string $apiUrl
     * @param string $orderId
     *
     * @return bool
     */
    public function add($type, $storeId, $review = '', $inivation = '', $time, $cType = '', $apiUrl = '', $orderId = '')
    {
        $enabled = Mage::getStoreConfig('kiyoh/log/enabled');
        if ($enabled) {
            $apiId = Mage::getStoreConfig('kiyoh/general/api_id', $storeId);
            $company = Mage::getStoreConfig('kiyoh/general/company', $storeId);
            $reviewUpdates = '';
            $reviewNew = '';
            if ($review) {
                if (!empty($review['review_updates'])) {
                    $reviewUpdates = $review['review_updates'];
                } else {
                    $reviewUpdates = 0;
                }

                if (!empty($review['review_new'])) {
                    $reviewNew = $review['review_new'];
                } else {
                    $reviewNew = 0;
                }
            }

            $model = Mage::getModel('kiyoh/log');
            $model->setType($type)
                ->setShopId($apiId)
                ->setCompany($company)
                ->setReviewUpdate($reviewUpdates)
                ->setReviewNew($reviewNew)
                ->setResponse($inivation)
                ->setOrderId($orderId)
                ->setCron($cType)
                ->setDate(now())
                ->setTime($time)
                ->setApiUrl($apiUrl)
                ->save();
        }

        return true;
    }

}