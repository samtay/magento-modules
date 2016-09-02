<?php
/**
 * @package     BlueAcorn\LayeredNavigation
 * @version     1.0.0
 * @author      Sam Tay @ Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2016 Blue Acorn, Inc.
 */
namespace BlueAcorn\LayeredNavigation\Block\Adminhtml\Dependency;

use BlueAcorn\LayeredNavigation\Model\Dependency;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /** @var \Magento\Framework\Registry */
    protected $registry;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'dependency_id';
        $this->_blockGroup = 'BlueAcorn_LayeredNavigation';
        $this->_controller = 'adminhtml_dependency'; // Note, this is used when finding Edit\Form class name
        parent::_construct();
    }

    /**
     * Get registered dependency
     *
     * @return Dependency
     */
    public function getDependency()
    {
        return $this->registry->registry('current_dependency');
    }

    /**
     * Prepare layout.
     * Allow saving only after attribute id is submitted
     *
     * @return $this
     */
    protected function _preparelayout()
    {
        if ($this->getDependency()->getFilterAttributeId()) {
            $this->buttonList->add(
                'save_and_edit_button',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                100
            );
        } else {
            $this->removeButton('save');
        }
        return parent::_prepareLayout();
    }

    /**
     * Return translated header text depending on creating/editing action
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->getDependency()->getId()) {
            return __('Filter Dependency: "%1"', $this->escapeHtml($this->getDependency()->getFilterAttribute()->getDefaultFrontendLabel()));
        } else {
            return __('New Filter Dependency');
        }
    }

    /**
     * Return validation url for edit form
     *
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', ['_current' => true]);
    }

    /**
     * Return save url for edit form
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => null]);
    }

    /**
     * Allow "incremental" back url after first setting step
     *
     * @return string
     */
    public function getBackUrl()
    {
        if ($this->getDependency()->getFilterAttributeId() && !$this->getDependency()->getId()) {
            return $this->getUrl('*/*/new', ['_current' => false]);
        }
        return parent::getBackUrl();
    }
}

