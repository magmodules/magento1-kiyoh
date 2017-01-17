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

class Magmodules_Kiyoh_Block_Adminhtml_Widget_Grid_Stars extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{

    /**
     * @param Varien_Object $row
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());

        if ($value == '0') {
            $output = '';
        } else {
            $o = 0;

            $shopId = $row->getData('shop_id');
            $reviewStats = Mage::getModel('kiyoh/stats')->load($shopId, 'shop_id');

            $output = '<span class="rating-empty"><span class="rating-star-' . $value . '"></span></span>';
            $output .= '<a href="#" class="magtooltip" alt="">(i)<span>';
            $output .= '<strong>' . Mage::helper('kiyoh')->__('Overall') . ':</strong> ';
            $output .= $row->getData('score') . '/10<br>';

            if ($row->getData('score_q2') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ2Title() . ':</strong> ';
                $output .= $row->getData('score_q2') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q3') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ3Title() . '</strong> ';
                $output .= $row->getData('score_q3') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q4' > 0)) {
                $output .= '<strong>' . $reviewStats->getScoreQ4Title() . '</strong> ';
                $output .= $row->getData('score_q4') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q5') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ5Title() . '</strong> ';
                $output .= $row->getData('score_q5') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q6') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ6Title() . '</strong> ';
                $output .= $row->getData('score_q6') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q7') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ7Title() . '</strong> ';
                $output .= $row->getData('score_q7') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q8') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ8Title() . '</strong> ';
                $output .= $row->getData('score_q8') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q9') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ9Title() . '</strong> ';
                $output .= $row->getData('score_q9') . '/10<br>';
                $o++;
            }

            if ($row->getData('score_q10') > 0) {
                $output .= '<strong>' . $reviewStats->getScoreQ10Title() . '</strong> ';
                $output .= $row->getData('score_q10') . '/10<br>';
                $o++;
            }

            if ($o > 0) {
                $output .= '<br/>';
            }

            if ($row->getData('positive')) {
                $output .= '<strong>' . Mage::helper('kiyoh')->__('Positive') . ':</strong> ';
                $output .= $row->getData('positive') . '<br>';
            }

            if ($row->getData('negative')) {
                $output .= '<strong>' . Mage::helper('kiyoh')->__('Negative') . ':</strong> ';
                $output .= $row->getData('negative') . '<br>';
            }

            if ($row->getData('reaction')) {
                $output .= '<strong>' . Mage::helper('kiyoh')->__('Reaction') . ':</strong> ';
                $output .= $row->getData('reaction') . '<br>';
            }

            $output .= '</span></a>';
        }

        return $output;
    }

}