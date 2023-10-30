<?php

namespace Abhinay\RandomCat\Model;

/**
 * Class CatImageApi
 * @package Abhinay\RandomCat\Model
 */
class CatImageApi
{
    const REQUEST_STATUS = '200';

    /**
     * @var \Magento\Framework\HTTP\Adapter\CurlFactory
     */
    protected $_curlFactory;
    /**
     * @var \Abhinay\RandomCat\Logger\Logger
     */
    protected $_logger;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $_jsonSerializer;
    /**
     * @var \Abhinay\RandomCat\Helper\Data
     */
    protected $_helper;

    /**
     * CatImageApi constructor.
     * @param \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory
     * @param \Abhinay\RandomCat\Logger\Logger $logger
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Abhinay\RandomCat\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Abhinay\RandomCat\Logger\Logger $logger,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Abhinay\RandomCat\Helper\Data $helper
    )
    {
        $this->_curlFactory = $curlFactory;
        $this->_logger = $logger;
        $this->_jsonSerializer = $jsonSerializer;
        $this->_helper = $helper;
    }

    /**
     * Getting Random Cat API key from core config
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->_helper->getGeneralConfigValue('api_url_key');
    }

    /**
     * Getting Random Cat API Url from core config
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->_helper->getGeneralConfigValue('api_url');
    }

    /**
     * Getting Random Cat Image for product
     * @return mixed|null
     */
    public function getRadomCatImageUrl()
    {
        $imageResponse = $this->getRandomCatImageFromApi();
        if (!isset($imageResponse['url'])) {
            return null;
        }
        if (!$this->checkRandomCatImageUrl($imageResponse['url'])) {
            return null;
        }
        return $imageResponse['url'];
    }

    /**
     * Checking Random Cat Image Url
     * @param string $imageUrl
     * @return bool
     */
    public function checkRandomCatImageUrl($imageUrl = "")
    {
        $curlResult = $this->requestCurl($imageUrl);
        $curlCode = \Zend_Http_Response::extractCode($curlResult);
        $curlBody = \Zend_Http_Response::extractBody($curlResult);
        if ($curlCode != self::REQUEST_STATUS) {
            $this->_logger->log('notice', 'Random cat image is not valid.',
                array('url' => $imageUrl, 'response' => $curlBody)
            );
        }
        return ($curlCode == self::REQUEST_STATUS);
    }

    /**
     * Preparing Request for getting Random Cat Image
     * @return array|bool|float|int|mixed|null|string
     */
    public function getRandomCatImageFromApi()
    {
        $endPointUrl = $this->getApiUrl();
        $params = [
            'api_key' => $this->getApiKey(),
        ];
        $dynamicUrl = $endPointUrl . '?' . http_build_query($params);
        try{
            $result = $this->requestCurl($dynamicUrl);
        }
        catch (\Exception $e){
            $this->_logger->log('notice', 'Something went wrong with the curl request.', array('msg' => $e->getMessage()));
        }
        $curlBody = \Zend_Http_Response::extractBody($result);
        $curlCode = \Zend_Http_Response::extractCode($result);
        if ($curlCode != self::REQUEST_STATUS) {
            $this->_logger->log('notice', 'API response is failed.', array('body' => $curlBody));
            return array();
        }
        return $this->_jsonSerializer->unserialize($curlBody);
    }

    /**
     * Sending Curl request for getting Random Cat Image
     * @param string $requestUrl
     * @return string
     */
    public function requestCurl($requestUrl = "")
    {
        $httpAdapter = $this->_curlFactory->create();
        $httpAdapter->write(
            \Zend_Http_Client::GET,
            $requestUrl,
            '1.1',
            ["Content-Type:application/json"]
        );
        return $httpAdapter->read();
    }
}