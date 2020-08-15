<?php
/**
 * Webkul contactus js.
 * @category Webkul
 * @package Webkul_AjaxContactForm
 * @author Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */
namespace Webkul\AjaxContactForm\Controller\Email;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Webkul Marketplace Landing page Index Controller.
 */
class Sent extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJson;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJson
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJson = $resultJson;
        parent::__construct($context);
    }

    /**
     * Marketplace Landing page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $helper = $this->_objectManager->get(
            \Webkul\AjaxContactForm\Helper\Email::class
        );
        if ($this->getRequest()->isPost()) {
            try {
                $data = $this->getRequest()->getParams();
                $emailList = explode(',', $data['email']);
                $mailCount = 1;
                $name=$data['name'];
                $emailto=$data['emailto'];
                foreach ($emailList as $email) {
                    if ($email!='') {
                        $helper->contactusMail($emailto, $name, $email, $data['subject'], $data['message']);
                    }
                }
                $result = ['success' => true];
                return $this->_resultJson->create()->setData($result);
            } catch (\Exception $e) {
                $result = ['error' => $e->getMessage()];
                return $this->_resultJson->create()->setData($result);
            }
        }
    }
}
