<?php
/**
 * Webkul software.
 * @category Webkul
 * @package Webkul_AjaxContactForm
 * @author Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */
namespace Webkul\AjaxContactForm\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Contactus extends Template implements BlockInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    
    protected $httpContext;

    /**
     * @var \Magento\Catalog\Block\Product\Context
     * @var \Magento\Customer\Model\Session
     * @var array
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        array $data = []
    ) {
          $this->setTemplate('widget/contactus.phtml');
          $this->_customerSession = $customerSession;
          $this->httpContext = $httpContext;
          $this->_encryptor = $encryptor;
          parent::__construct($context, $data);
    }
    //This function will be used to get the css/js file.
    public function getAssetUrl($asset)
    {
        return $this->_assetRepo->createAsset($asset)->getUrl();
    }
    public function getApiKey()
    {
        return $this->_encryptor->decrypt($this->_scopeConfig->getValue('ajaxcontactform/general/appendChild'));
    }

    public function getApiVersion()
    {
        return $this->_scopeConfig->getValue('ajaxcontactform/general/map_status');
    }
    public function getApiCoordinate()
    {
        return $this->_scopeConfig->getValue('ajaxcontactform/general/map_coordinates');
    }

    public function isShowEmail()
    {
        return $this->_scopeConfig->getValue('ajaxcontactform/general/showemail');
    }

    /**
     * getCustomerEmail
     * @return string
     */
    public function getCustomerEmail()
    {
        $customerEmail = $this->httpContext->getValue('customer_email');
        return $customerEmail;
    }

    /**
     * getCustomerName
     * @return string
     */
    public function getCustomerName()
    {
        $customerName = $this->httpContext->getValue('customer_name');
        return $customerName;
    }
}
