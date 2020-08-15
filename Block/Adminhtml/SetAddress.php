<?php

/**
 * @category  Webkul
 * @package   Webkul_AjaxContactForm
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\AjaxContactForm\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field as FormField;
use Magento\Framework\Data\Form\Element\AbstractElement;

class SetAddress extends FormField
{
    const FIELD_TEMPLATE = 'set-address.phtml';

     /**
      * @var \Magento\Backend\Block\Template\Context
      * @var \Magento\Framework\Encryption\EncryptorInterface
      * @var array
      */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        array $data = []
    ) {
          $this->_encryptor = $encryptor;
          parent::__construct($context, $data);
    }

    /**
     * Set template to itself.
     * @return $this
     */

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::FIELD_TEMPLATE);
        }
        return $this;
    }

    /**
     * Render button.
     * @param AbstractElement $element
     * @return string
     */

    public function render(AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return get Google ApiKey.
     *
     * @return string
     */

    public function getGoogleApiKey()
    {
        return $this->_encryptor->decrypt($this->_scopeConfig->getValue('ajaxcontactform/general/appendChild'));
    }

    /**
     * Return ajax url for button.
     *
     * @return string
     */

    public function getSavedAddress()
    {
        return $this->_scopeConfig->getValue('ajaxcontactform/general/address');
    }

    /**
     * Get the button and scripts contents.
     * @param AbstractElement $element
     * @return string
     */
    
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
