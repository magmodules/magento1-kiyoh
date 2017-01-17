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

class Magmodules_Kiyoh_Block_Adminhtml_Kiyohreviews_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * Magmodules_Kiyoh_Block_Adminhtml_Kiyohreviews_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('reviewsGrid');
        $this->setDefaultSort('date_created');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return mixed
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('kiyoh/reviews')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return mixed
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'company', array(
                'header' => Mage::helper('kiyoh')->__('Shop'),
                'index' => 'company',
                'width' => '120px',
            )
        );

        $this->addColumn(
            'customer_name', array(
                'header' => Mage::helper('kiyoh')->__('User'),
                'align' => 'left',
                'index' => 'customer_name',
            )
        );

        $this->addColumn(
            'customer_place', array(
                'header' => Mage::helper('kiyoh')->__('City'),
                'align' => 'left',
                'index' => 'customer_place',
            )
        );

        $this->addColumn(
            'score', array(
                'header' => Mage::helper('kiyoh')->__('Score'),
                'align' => 'left',
                'index' => 'score',
                'renderer' => 'kiyoh/adminhtml_widget_grid_stars',
                'width' => '110',
                'filter' => false,
                'sortable' => true,
            )
        );

        $this->addColumn(
            'customer_email', array(
                'header' => Mage::helper('kiyoh')->__('Email'),
                'align' => 'left',
                'index' => 'customer_email',
            )
        );

        $this->addColumn(
            'recommendation', array(
                'header' => Mage::helper('kiyoh')->__('Recommendation'),
                'align' => 'left',
                'index' => 'recommendation',
                'width' => '80px',
                'type' => 'options',
                'options' => array(
                    0 => Mage::helper('kiyoh')->__('No'),
                    1 => Mage::helper('kiyoh')->__('Yes'),
                ),
            )
        );

        $this->addColumn(
            'date_created', array(
                'header' => Mage::helper('kiyoh')->__('Date'),
                'align' => 'left',
                'type' => 'date',
                'index' => 'date_created',
                'width' => '140',
            )
        );

        $this->addColumn(
            'sidebar', array(
                'header' => Mage::helper('kiyoh')->__('Sidebar'),
                'align' => 'left',
                'width' => '80px',
                'index' => 'sidebar',
                'type' => 'options',
                'options' => array(
                    0 => Mage::helper('kiyoh')->__('No'),
                    1 => Mage::helper('kiyoh')->__('Yes'),
                ),
            )
        );

        $this->addColumn(
            'status', array(
                'header' => Mage::helper('kiyoh')->__('Active'),
                'align' => 'left',
                'width' => '80px',
                'index' => 'status',
                'type' => 'options',
                'options' => array(
                    0 => Mage::helper('kiyoh')->__('No'),
                    1 => Mage::helper('kiyoh')->__('Yes'),
                ),
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('review_id');
        $this->getMassactionBlock()->setFormFieldName('reviewids');

        $this->getMassactionBlock()->addItem(
            'hide', array(
                'label' => Mage::helper('kiyoh')->__('Set to invisible'),
                'url' => $this->getUrl('*/*/massDisable'),
            )
        );
        $this->getMassactionBlock()->addItem(
            'visible', array(
                'label' => Mage::helper('kiyoh')->__('Set to visible'),
                'url' => $this->getUrl('*/*/massEnable'),
            )
        );
        $this->getMassactionBlock()->addItem(
            'addsidebar', array(
                'label' => Mage::helper('kiyoh')->__('Add to Sidebar'),
                'url' => $this->getUrl('*/*/massEnableSidebar'),
            )
        );
        $this->getMassactionBlock()->addItem(
            'removesidebar', array(
                'label' => Mage::helper('kiyoh')->__('Remove from Sidebar'),
                'url' => $this->getUrl('*/*/massDisableSidebar'),
            )
        );
        return $this;
    }

    /**
     * @param $row
     *
     * @return bool
     */
    public function getRowUrl($row)
    {
        return false;
    }

}