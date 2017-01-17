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

class Magmodules_Kiyoh_Model_Observer
{

    /**
     *
     */
    public function processStats()
    {
        $storeIds = Mage::getModel('kiyoh/api')->getStoreIds();
        foreach ($storeIds as $storeId) {
            $enabled = Mage::getStoreConfig('kiyoh/general/enabled', $storeId);
            $cronEnabled = Mage::getStoreConfig('kiyoh/reviews/cron', $storeId);
            if ($enabled && $cronEnabled) {
                $cType = 'stats';
                $startTime = microtime(true);
                $feed = Mage::getModel('kiyoh/api')->getFeed($storeId, $cType);
                $results = array();
                $results['stats'] = Mage::getModel('kiyoh/stats')->processFeed($feed, $storeId);
                $results['company'] = $feed->company;
                $time = (microtime(true) - $startTime);
                Mage::getModel('kiyoh/log')->add('reviews', $storeId, $results, '', $time, $cType);
            }
        }

        Mage::getModel('kiyoh/stats')->processOverall();
    }

    /**
     *
     */
    public function processReviews()
    {
        $storeIds = Mage::getModel('kiyoh/api')->getStoreIds();
        foreach ($storeIds as $storeId) {
            $enabled = Mage::getStoreConfig('kiyoh/general/enabled', $storeId);
            $cronEnabled = Mage::getStoreConfig('kiyoh/reviews/cron', $storeId);
            if ($enabled && $cronEnabled) {
                $cType = 'reviews';
                $startTime = microtime(true);
                $feed = Mage::getModel('kiyoh/api')->getFeed($storeId, $cType);
                $results = Mage::getModel('kiyoh/reviews')->processFeed($feed, $storeId, $cType);
                $results['stats'] = Mage::getModel('kiyoh/stats')->processFeed($feed, $storeId);
                $time = (microtime(true) - $startTime);
                Mage::getModel('kiyoh/log')->add('reviews', $storeId, $results, '', $time, $cType);
            }
        }
    }

    /**
     *
     */
    public function processHistory()
    {
        $storeIds = Mage::getModel('kiyoh/api')->getStoreIds();
        foreach ($storeIds as $storeId) {
            $enabled = Mage::getStoreConfig('kiyoh/general/enabled', $storeId);
            $cronEnabled = Mage::getStoreConfig('kiyoh/reviews/cron', $storeId);
            if ($enabled && $cronEnabled) {
                $cType = 'history';
                $startTime = microtime(true);
                $storeId = 0;
                $feed = Mage::getModel('kiyoh/api')->getFeed($storeId, $cType);
                $results = Mage::getModel('kiyoh/reviews')->processFeed($feed, $storeId, $cType);
                $results['stats'] = Mage::getModel('kiyoh/stats')->processFeed($feed, $storeId);
                $time = (microtime(true) - $startTime);
                Mage::getModel('kiyoh/log')->add('reviews', $storeId, $results, '', $time, $cType);
            }
        }
    }

    /**
     *
     */
    public function cleanLog()
    {
        $enabled = Mage::getStoreConfig('kiyoh/log/clean', 0);
        $days = Mage::getStoreConfig('kiyoh/log/clean_days', 0);
        if (($enabled) && ($days > 0)) {
            $logModel = Mage::getModel('kiyoh/log');
            $logs = $logModel->getCollection()
                ->addFieldToSelect('id')
                ->addFieldToFilter('date', array('lteq' => date('Y-m-d', strtotime('-' . $days . ' days'))));
            foreach ($logs as $log) {
                $logModel->load($log->getId())->delete();
            }
        }
    }

    /**
     * @param $observer
     */
    public function processFeedbackInvitationcallAfterShipment($observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();
        $invEnabled = Mage::getStoreConfig('kiyoh/invitation/enabled', $order->getStoreId());
        $apiKey = Mage::getStoreConfig('kiyoh/general/api_key', $order->getStoreId());
        if ($invEnabled && $apiKey) {
            $status = Mage::getStoreConfig('kiyoh/invitation/status', $order->getStoreId());
            if ($order->getStatus() == $status) {
                $backlog = Mage::getStoreConfig('kiyoh/invitation/backlog', $order->getStoreId());
                if ($backlog > 0) {
                    $dateDiff = floor(time() - strtotime($order->getCreatedAt())) / (60 * 60 * 24);
                    if ($dateDiff < $backlog) {
                        Mage::getModel('kiyoh/api')->sendInvitation($order);
                    }
                } else {
                    Mage::getModel('kiyoh/api')->sendInvitation($order);
                }
            }    
        }
    }

    /**
     * @param $observer
     */
    public function processFeedbackInvitationcall($observer)
    {
        $order = $observer->getEvent()->getOrder();
        $invEnabled = Mage::getStoreConfig('kiyoh/invitation/enabled', $order->getStoreId());
        $apiKey = Mage::getStoreConfig('kiyoh/general/api_key', $order->getStoreId());
        if ($invEnabled && $apiKey) {
            $status = Mage::getStoreConfig('kiyoh/invitation/status', $order->getStoreId());
            if ($order->getStatus() == $status) {
                $backlog = Mage::getStoreConfig('kiyoh/invitation/backlog', $order->getStoreId());
                if ($backlog > 0) {
                    $dateDiff = floor(time() - strtotime($order->getCreatedAt())) / (60 * 60 * 24);
                    if ($dateDiff < $backlog) {
                        Mage::getModel('kiyoh/api')->sendInvitation($order);
                    }
                } else {
                    Mage::getModel('kiyoh/api')->sendInvitation($order);
                }
            }
        }
    }

}