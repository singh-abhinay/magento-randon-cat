<?php

namespace Abhinay\RandomCat\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Abhinay\RandomCat\Helper
 */
class Data extends AbstractHelper
{

    const XML_PATH_RANDOMCAT_CONFIG = 'randomcat/general/';

    /**
     * Calling Core Config Value with Calling function getConfigValue
     * @param $code
     * @param null $store_id
     * @return mixed
     */
    public function getGeneralConfigValue($code, $store_id = null)
    {
        return $this->getConfigValue(self::XML_PATH_RANDOMCAT_CONFIG . $code, $store_id);
    }

    /**
     * Getting Core Config Value
     * @param $field
     * @param null $store_id
     * @return mixed
     */
    public function getConfigValue($field, $store_id = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $store_id
        );
    }
}