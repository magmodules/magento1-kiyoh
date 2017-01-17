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

class Magmodules_Kiyoh_Adminhtml_KiyohreviewsController extends Mage_Adminhtml_Controller_Action
{

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('kiyoh/kiyohreviews')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    /**
     *
     */
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    /**
     *
     */
    public function processAction()
    {
        $storeIds = Mage::getModel('kiyoh/api')->getStoreIds();
        $startTime = microtime(true);

        foreach ($storeIds as $storeId) {
            $msg = '';
            $apiId = Mage::getStoreConfig('kiyoh/general/api_id', $storeId);
            $result = Mage::getModel('kiyoh/api')->processFeed($storeId, 'history');
            $time = (microtime(true) - $startTime);
            Mage::getModel('kiyoh/log')->add('reviews', $storeId, $result, '', $time, '', '');

            if (($result['review_new'] > 0) || ($result['review_updates'] > 0) || ($result['stats'] == true)) {
                $msg = Mage::helper('kiyoh')->__('Webwinkel ID %s:', $apiId) . ' ';
                $msg .= Mage::helper('kiyoh')->__('%s new review(s)', $result['review_new']) . ', ';
                $msg .= Mage::helper('kiyoh')->__('%s review(s) updated', $result['review_updates']) . ' & ';
                $msg .= Mage::helper('kiyoh')->__('and total score updated.');
            }

            if ($msg) {
                Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            }
        }

        Mage::getModel('kiyoh/stats')->processOverall();
        $this->_redirect('adminhtml/system_config/edit/section/kiyoh');
    }

    /**
     *
     */
    public function massDisableAction()
    {
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if (!is_array($reviewIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('kiyoh')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $reviewId) {
                    $reviews = Mage::getModel('kiyoh/reviews')->load($reviewId);
                    $reviews->setStatus(0)->save();
                }

                $msg = Mage::helper('kiyoh')->__('Total of %d review(s) were disabled.', count($reviewIds));
                Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     *
     */
    public function massEnableAction()
    {
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if (!is_array($reviewIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('kiyoh')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $reviewId) {
                    $reviews = Mage::getModel('kiyoh/reviews')->load($reviewId);
                    $reviews->setStatus(1)->save();
                }

                $msg = Mage::helper('kiyoh')->__('Total of %d review(s) were enabled.', count($reviewIds));
                Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     *
     */
    public function massEnableSidebarAction()
    {
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if (!is_array($reviewIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('kiyoh')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $reviewId) {
                    $reviews = Mage::getModel('kiyoh/reviews')->load($reviewId);
                    $reviews->setSidebar(1)->save();
                }

                $msg = Mage::helper('kiyoh')->__('Total of %d review(s) were added to the sidebar.', count($reviewIds));
                Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     *
     */
    public function massDisableSidebarAction()
    {
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if (!is_array($reviewIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('kiyoh')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $reviewId) {
                    $reviews = Mage::getModel('kiyoh/reviews')->load($reviewId);
                    $reviews->setSidebar(0)->save();
                }

                $msg = Mage::helper('kiyoh')->__('Total of %d review(s) were removed from the sidebar.', count($reviewIds));
                Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     *
     */
    public function truncateAction()
    {
        $i = 0;
        $collection = Mage::getModel('kiyoh/reviews')->getCollection();
        foreach ($collection as $item) {
            $item->delete();
            $i++;
        }

        $msg = Mage::helper('kiyoh')->__('Succefully deleted all %s saved review(s).', $i);
        Mage::getSingleton('adminhtml/session')->addSuccess($msg);
        $this->_redirect('*/*/index');
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/kiyoh/kiyoh_reviews');
    }

}