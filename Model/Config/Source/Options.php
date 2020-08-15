<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AjaxContactForm
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\AjaxContactForm\Model\Config\Source;

class Options implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
          ['value' => '2', 'label' => __('no')],
            ['value' => '1', 'label' => __('yes')]

        ];
    }
}
