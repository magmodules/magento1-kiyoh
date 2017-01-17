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

class Magmodules_Kiyoh_Adminhtml_KiyohlogController extends Mage_Adminhtml_Controller_Action
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
    public function massDeleteAction()
    {
        $logIds = $this->getRequest()->getParam('logids');
        if (!is_array($logIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('kiyoh')->__('Please select item(s)'));
        } else {
            try {
                foreach ($logIds as $id) {
                    Mage::getModel('kiyoh/log')->load($id)->delete();
                }

                $msg = Mage::helper('kiyoh')->__('Total of %d log record(s) deleted.', count($logIds));
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
    public function cleanAction()
    {
        $enabled = Mage::getStoreConfig('kiyoh/log/clean');
        $days = Mage::getStoreConfig('kiyoh/log/clean_days');
        $i = 0;
        if (($enabled) && ($days > 0)) {
            $logmodel = Mage::getModel('kiyoh/log');
            $deldate = date('Y-m-d', strtotime('-' . $days . ' days'));
            $logs = $logmodel->getCollection()->addFieldToSelect('id')->addFieldToFilter('date', array('lteq' => $deldate));
            foreach ($logs as $log) {
                $logmodel->load($log->getId())->delete();
                $i++;
            }

            $msg = Mage::helper('kiyoh')->__('Total of %s log record(s) deleted.', $i);
            Mage::getSingleton('adminhtml/session')->addSuccess($msg);
        }

        $this->_redirect('*/*/index');
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/kiyoh/kiyoh_log');
    }

}