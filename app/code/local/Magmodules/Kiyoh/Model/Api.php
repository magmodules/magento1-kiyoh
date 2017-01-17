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
class Magmodules_Kiyoh_Model_Api extends Mage_Core_Model_Abstract
{

    /**
     * @param int $storeId
     * @param     $type
     *
     * @return bool
     */
    public function processFeed($storeId = 0, $type)
    {
        if ($feed = $this->getFeed($storeId, $type)) {
            $results = Mage::getModel('kiyoh/reviews')->processFeed($feed, $storeId, $type);
            $results['stats'] = Mage::getModel('kiyoh/stats')->processFeed($feed, $storeId);
            return $results;
        } else {
            return false;
        }
    }

    /**
     * @param        $storeId
     * @param string $type
     *
     * @return bool|SimpleXMLElement
     */
    public function getFeed($storeId, $type = '')
    {
        $apiId = Mage::getStoreConfig('kiyoh/general/api_id', $storeId);
        $apiKey = Mage::getStoreConfig('kiyoh/general/api_key', $storeId);
        $apiUrl = Mage::getStoreConfig('kiyoh/general/api_url', $storeId);

        if ($type == 'stats') {
            $apiUrl = 'https://' . $apiUrl . '/xml/recent_company_reviews.xml?';
            $apiUrl .= 'connectorcode=' . $apiKey . '&company_id=' . $apiId . '&reviewcount=10';
        }

        if ($type == 'reviews') {
            $apiUrl = 'https://' . $apiUrl . '/xml/recent_company_reviews.xml?';
            $apiUrl .= 'connectorcode=' . $apiKey . '&company_id=' . $apiId . '&reviewcount=10';
        }

        if ($type == 'history') {
            $apiUrl = 'https://' . $apiUrl . '/xml/recent_company_reviews.xml?';
            $apiUrl .= 'connectorcode=' . $apiKey . '&company_id=' . $apiId . '&reviewcount=10000';
        }

        if ($apiId) {
            $xml = simplexml_load_file($apiUrl);
            if ($xml) {
                if (empty($xml->error)) {
                    return $xml;
                } else {
                    $msg = Mage::helper('kiyoh')->__('API: %s (Please check the online manual for suggestions)', (string)$xml->error);
                    Mage::getSingleton('adminhtml/session')->addError($msg);
                    return false;
                }
            } else {
                $e = file_get_contents($apiUrl);
                $msg = Mage::helper('kiyoh')->__('API: %s (Please check the online manual for suggestions)', $e);
                Mage::getSingleton('adminhtml/session')->addError($msg);
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * @param $order
     *
     * @return bool
     */
    public function sendInvitation($order)
    {
        $storeId = $order->getStoreId();
        $startTime = microtime(true);
        $crontype = 'orderupdate';
        $apiKey = Mage::getStoreConfig('kiyoh/general/api_key', $storeId);
        $apiUrl = Mage::getStoreConfig('kiyoh/general/api_url', $storeId);
        $apiEmail = Mage::getStoreConfig('kiyoh/invitation/company_email', $storeId);
        $delay = Mage::getStoreConfig('kiyoh/invitation/delay', $storeId);
        $invStatus = Mage::getStoreConfig('kiyoh/invitation/status', $storeId);
        $email = strtolower($order->getCustomerEmail());

        if ($order->getStatus() == $invStatus) {
            $http = new Varien_Http_Adapter_Curl();
            $http->setConfig(array('timeout' => 30, 'maxredirects' => 0));

            $url = 'https://' . $apiUrl . '/set.php';
            $request = 'action=sendInvitation&connector=' . $apiKey . '&targetMail=' . $email;
            $request .= '&delay=' . $delay . '&user=' . $apiEmail;

            $http->write(Zend_Http_Client::POST, $url, '1.1', array(), $request);
            $result = $http->read();

            if ($result) {
                $lines = explode("\n", $result);
                $responseHtml = $lines[0];
                $lines = array_reverse($lines);
                $responseHtml .= ' - ' . $lines[0];
            } else {
                $responseHtml = 'No response from ' . $url;
            }

            Mage::getModel('kiyoh/log')->add(
                'invitation',
                $order->getStoreId(),
                '',
                $responseHtml,
                (microtime(true) - $startTime),
                $crontype,
                $url . '?' . $request,
                $order->getId()
            );

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getStoreIds()
    {
        $storeIds = array();
        $apiIds = array();
        $stores = Mage::getModel('core/store')->getCollection();
        foreach ($stores as $store) {
            if ($store->getIsActive()) {
                $apiId = Mage::getStoreConfig('kiyoh/general/api_id', $store->getId());
                if (!in_array($apiId, $apiIds)) {
                    $apiIds[] = $apiId;
                    $storeIds[] = $store->getId();
                }
            }
        }

        return $storeIds;
    }

}