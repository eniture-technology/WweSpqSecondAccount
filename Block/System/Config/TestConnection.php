<?php
namespace Eniture\WweSpqSecondAccount\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class TestConnection extends Field
{
    const BUTTON_TEMPLATE = 'system/config/testconnection.phtml';

    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(Context $context, $data = [])
    {
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::BUTTON_TEMPLATE);
        }
        return $this;
    }

    /**
     * @param AbstractElement $element
     * @return element
     */
    public function render(AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * @return url
     */
    public function getAjaxCheckUrl()
    {
        return $this->getbaseUrl().'wwesmallsecondaccount/Test/TestConnection/';
    }

    /**
     * @param AbstractElement $element
     * @return array
     */
    public function _getElementHtml(AbstractElement $element)
    {
        $this->addData(
            [
                'id'            => 'rg-wwesm-test-conn',
                'button_label'  => 'Test Connection',
            ]
        );
        return $this->_toHtml();
    }
}
