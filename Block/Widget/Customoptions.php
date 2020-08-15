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

use Magento\Widget\Block\Adminhtml\Widget\Options;

class Customoptions extends Options
{

    /**
     * Add field to Options form based on parameter configuration
     *
     * @param \Magento\Framework\DataObject $parameter
     * @return \Magento\Framework\Data\Form\Element\AbstractElement
     */
    protected function _addField($parameter)
    {
        $form = $this->getForm();
        $fieldset = $this->getMainFieldset();

        // prepare element data with values (either from request of from default values)
        $fieldName = $parameter->getKey();
        if ($fieldName == 'email_to') {
            $data = [
                'name' => $form->addSuffixToName($fieldName, 'parameters'),
                'label' => __($parameter->getLabel()),
                'required' => $parameter->getRequired(),
                'class' => 'widget-option validate-email',
                'note' => __($parameter->getDescription()),
            ];
        } else {
            $data = [
            'name' => $form->addSuffixToName($fieldName, 'parameters'),
            'label' => __($parameter->getLabel()),
            'required' => $parameter->getRequired(),
            'class' => 'widget-option',
            'note' => __($parameter->getDescription()),
            ];
        }

        if ($values = $this->getWidgetValues()) {
            $data['value'] = isset($values[$fieldName]) ? $values[$fieldName] : '';
        } else {
            $data['value'] = $parameter->getValue();
        }

        //prepare unique id value
        if ($fieldName == 'unique_id' && $data['value'] == '') {
            $data['value'] = hash('sha256', microtime(1));
        }

        if (is_array($data['value'])) {
            foreach ($data['value'] as &$value) {
                if (is_string($value)) {
                    $value = html_entity_decode($value);
                }
            }
        } else {
            $data['value'] = html_entity_decode($data['value']);
        }

        // prepare element dropdown values
        if ($values = $parameter->getValues()) {
            // dropdown options are specified in configuration
            $data['values'] = [];
            foreach ($values as $option) {
                $data['values'][] = ['label' => __($option['label']), 'value' => $option['value']];
            }
            // otherwise, a source model is specified
        } elseif ($sourceModel = $parameter->getSourceModel()) {
            $data['values'] = $this->_sourceModelPool->get($sourceModel)->toOptionArray();
        }

        // prepare field type or renderer
        $fieldRenderer = null;
        $fieldType = $parameter->getType();
        // hidden element
        if (!$parameter->getVisible()) {
            $fieldType = 'hidden';
            // just an element renderer
        } elseif ($fieldType && $this->_isClassName($fieldType)) {
            $fieldRenderer = $this->getLayout()->createBlock($fieldType);
            $fieldType = $this->_defaultElementType;
        }

        // instantiate field and render html
        $field = $fieldset->addField($this->getMainFieldsetHtmlId() . '_' . $fieldName, $fieldType, $data);
        if ($fieldRenderer) {
            $field->setRenderer($fieldRenderer);
        }

        // extra html preparations
        if ($helper = $parameter->getHelperBlock()) {
            $helperBlock = $this->getLayout()->createBlock(
                $helper->getType(),
                '',
                ['data' => $helper->getData()]
            );
            if ($helperBlock instanceof \Magento\Framework\DataObject) {
                $helperBlock->setConfig(
                    $helper->getData()
                )->setFieldsetId(
                    $fieldset->getId()
                )->prepareElementHtml(
                    $field
                );
            }
        }

        // dependencies from other fields
        $dependenceBlock = $this->getChildBlock('form_after');
        $dependenceBlock->addFieldMap($field->getId(), $fieldName);
        if ($parameter->getDepends()) {
            foreach ($parameter->getDepends() as $from => $row) {
                $values = isset($row['values']) ? array_values($row['values']) : (string)$row['value'];
                $dependenceBlock->addFieldDependence($fieldName, $from, $values);
            }
        }

        return $field;
    }
}
