<?php
/**
 * @package     BlueAcorn\LayeredNavigation
 * @version     1.0.0
 * @author      Sam Tay @ Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2016 Blue Acorn, Inc.
 */
namespace BlueAcorn\LayeredNavigation\Controller\Adminhtml;

use BlueAcorn\LayeredNavigation\Model\DependencyFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

abstract class Dependency extends \Magento\Backend\App\Action
{
    /** @var Registry */
    protected $registry = null;

    /** @var DependencyFactory */
    protected $dependencyFactory;

    /**
     * @param Context $context
     * @param DependencyFactory $dependencyFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        DependencyFactory $dependencyFactory,
        Registry $registry
    ) {
        $this->registry = $registry;
        $this->dependencyFactory = $dependencyFactory;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('BlueAcorn_LayeredNavigation::filter_dependency')
            ->addBreadcrumb(__('Layered Navigation'), __('Layered Navigation'))
            ->addBreadcrumb(__('Filter Dependencies'), __('Filter Dependencies'));
        return $resultPage;
    }

    /**
     * Load Dependency from request
     *
     * @param string $idFieldName
     * @return \BlueAcorn\LayeredNavigation\Model\Dependency
     */
    protected function initDependency($idFieldName = 'dependency_id')
    {
        $dependencyId = (int)$this->getRequest()->getParam($idFieldName);
        $model = $this->dependencyFactory->create();
        if ($dependencyId) {
            $model->load($dependencyId);
        }
        if (!$this->registry->registry('current_dependency')) {
            $this->registry->register('current_dependency', $model);
        }
        return $model;
    }

    /**
     * Check permissions
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('BlueAcorn_LayeredNavigation::filter_dependencies');
    }
}

