<?php
/**
 * @category   Shipping
 * @package    Eniture_WweSpqSecondAccount
 * @author     Eniture Technology : <sales@eniture.com>
 * @website    http://eniture.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Eniture\WweSpqSecondAccount\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    private $curl;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->context       = $context;
        $this->curl = $curl;
        parent::__construct($context);
    }

    /**
     * Returns second account info
     */
    public function getSecondAccountReqArr()
    {
        $secondAccArr = [];

        $username = $this->getConfigData('WweSmConnSetting/ENWweSpqSecondAcc/rgUsername');
        $password = $this->getConfigData('WweSmConnSetting/ENWweSpqSecondAcc/rgPassword');
        $authenticationKey = $this->getConfigData('WweSmConnSetting/ENWweSpqSecondAcc/rgAuthenticationKey');
        $accountNumber = $this->getConfigData('WweSmConnSetting/ENWweSpqSecondAcc/rgAccountNumber');

        if (!empty($accountNumber) && !empty($username) && !empty($password) && !empty($authenticationKey)) {
            $secondAccArr = [
                'speed_ship_username' => $username,
                'speed_ship_password' => $password,
                'authentication_key' => $authenticationKey,
                'world_wide_express_account_number' => $accountNumber,
            ];
        }

        return $secondAccArr;
    }

    /**
     * @param $confPath
     * @return mixed
     */
    public function getConfigData($confPath)
    {
        $scopeConfig = $this->context->getScopeConfig();
        return $scopeConfig->getValue($confPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $quotes
     * @return array
     */
    public function addResiGroundRatesIntoNormalRatesArr($quotes)
    {

        foreach ($quotes as $shipmentKey => $shipQuote) {
            if (isset($shipQuote->quotesWithResiAccount) && is_array($shipQuote->quotesWithResiAccount)) {
                foreach ($shipQuote->quotesWithResiAccount as $index => $servQuote) {
                    if (isset($servQuote->serviceType) && $servQuote->serviceType == 'GND') {
                        $isUpdated = false;
                        if (isset($shipQuote->q) && is_array($shipQuote->q)) {
                            foreach ($shipQuote->q as $key => $value) {
                                if (isset($value->serviceType) && $value->serviceType == 'GND') {
                                    $isUpdated = true;
                                    unset($quotes[$shipmentKey]->q[$key]);
                                    $quotes[$shipmentKey]->q[$key] = $servQuote;
                                }
                            }
                        }
                        if (!$isUpdated) {
                            $quotes[$shipmentKey]->q[] = $servQuote;
                        }
                    }
                }
                unset($quotes[$shipmentKey]->quotesWithResiAccount);
            }
        }

        return $quotes;
    }

    /**
     * This function send request and return response
     * $isAssocArray Parameter When TRUE, then returned objects will
     * be converted into associative arrays, otherwise its an object
     * @param $url
     * @param $postData
     * @param $isAssocArray
     * @return string
     */
    public function wweSmSendCurlRequest($url, $postData, $isAssocArray = false)
    {
        $fieldString = http_build_query($postData);
        try {
            $this->curl->post($url, $fieldString);
            $output = $this->curl->getBody();
            $result = json_decode($output, $isAssocArray);
        } catch (\Throwable $e) {
            $result = [];
        }

        return $result;
    }
}
