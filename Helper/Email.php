<?php
/**
 * Webkul contactus js.
 * @category Webkul
 * @package Webkul_AjaxContactForm
 * @author Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */
namespace Webkul\AjaxContactForm\Helper;

use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_CONTACTUS_MAIL = 'ajaxcontactform_general_contactus_email';
  
    /**
     * @var Magento\Framework\Translate\Inline\StateInterface
     */
    private $inlineTranslation;

    /**
     * @var Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @param \Magento\Framework\App\Helper\Context  $context,
     * @param tateInterface                          $inlineTranslation,
     * @param TransportBuilder                       $transportBuilder,
     * @param StoreManagerInterface                  $storeManager,
     * @param CustomerRepositoryInterface            $customer,
     * @param AffiliateHelper                        $affiliateHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customer,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {

        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * [generateTemplate description].
     *
     * @param Mixed $emailTemplateVariables
     * @param Mixed $senderInfo
     * @param Mixed $receiverInfo
     */
    public function generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo)
    {
        $template = $this->transportBuilder
                ->setTemplateIdentifier($this->_template)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $this->_storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars($emailTemplateVariables)
                ->setFrom($senderInfo)
                ->addTo($receiverInfo['email'], $receiverInfo['name']);
        return $this;
    }
    
    /*
     * sent the mail to admin
     */
    public function contactusMail($emailto, $name, $email, $subject, $message)
    {
        try {
            $senderInfo = [
                'name' => $name,
                'email' => $email
            ];
            $receiverInfo = ['name' => $name, 'email' =>$emailto];
            $emailTempVariables = ['message' => $message,'subject' => $subject,'name' => $name];
            $this->_template = $this->getTemplateId(self::XML_PATH_CONTACTUS_MAIL);
            $this->inlineTranslation->suspend();
            $this->generateTemplate($emailTempVariables, $senderInfo, $receiverInfo);
            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            return $this->getResponse()->setBody($e->getMessage());
        }
    }

    /**
     * Return template id.
     *
     * @return mixed
     */
    public function getTemplateId($xmlPath)
    {
        return $this->getConfigValue($xmlPath, $this->_storeManager->getStore()->getId());
    }

    protected function getConfigValue($path, $storeId)
    {
        return $this->_scopeConfig->getValue(
            "ajaxcontactform/general/contactus_email",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
