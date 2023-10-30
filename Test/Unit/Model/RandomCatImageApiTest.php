<?php


namespace Abhinay\RandomCat\Test\Unit\Model;

use Abhinay\RandomCat\Model\CatImageApi;
use Abhinay\RandomCat\Helper\Data as RandomCat_Helper;
use Abhinay\RandomCat\Logger\Logger;
use \Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use \Magento\Framework\Serialize\Serializer\Json as Json_Helper;

/**
 * Class RandomCatImageApiTest
 * @package Abhinay\RandomCat\Test\Unit\Model
 */
class RandomCatImageApiTest extends \PHPUnit\Framework\TestCase
{
    const RANDOM_CAT_GOOD_CALL = 1;

    const FAIL_CALL = 3;

    const RANDOM_CAT_BAD_CALL = 2;

    /**
     * @var $_objectManager
     */
    protected $_objectManager;
    /**
     * @var $_model
     */
    protected $_model;

    /**
     * Set up function call before the test run
     * @return void
     */
    protected function setUp(): void
    {
        $this->_objectManager = new ObjectManager($this);
        $this->jsonHelper = $this->_objectManager->getObject(Json_Helper::class);
        $this->randomcatHelper = $this->_objectManager->getObject(RandomCat_Helper::class);
        $this->randomcatLogger = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Tear Down function is called after the test runs
     * @return void
     */
    public function tearDown(): void
    {
        $this->_curlFactory = null;
        $this->_model = null;
    }

    /**
     * Test Good case scenerio
     */
    public function testGoodApiCall()
    {
        $expectedResult = array("url" => "https://randomcatapi.commerce.lingaro.dev/images/cat7.jpg");
        $result = $this->getModelApiFactory(self::RANDOM_CAT_GOOD_CALL)->getRandomCatImageFromApi();
        $this->assertEquals($result, $expectedResult);
    }

    /**
     * Test Fail case scenerio
     */
    public function testFailedApiCall()
    {
        $model = $this->getModelApiFactory(self::RANDOM_CAT_BAD_CALL);
        $apiResult = $model->getRandomCatImageFromApi();
        $randomCatImage = $model->getRadomCatImageUrl();
        $this->assertEquals($apiResult, array());
        $this->assertEquals($randomCatImage, null);
    }

    /**
     * Test Bad case scenerio
     */
    public function testBadImage()
    {
        $model = $this->getModelApiFactory(self::FAIL_CALL);
        $randomCatImage = $model->getRadomCatImageUrl();
        $randomCatImageUrl = $model->checkRandomCatImageUrl();
        $this->assertEquals($randomCatImage, null);
        $this->assertEquals($randomCatImageUrl, false);
    }

    /**
     * Get the instance of Model File
     * Send request
     */
    public function getModelApiFactory($flagTest = self::RANDOM_CAT_GOOD_CALL)
    {
        switch ($flagTest) {
            case self::RANDOM_CAT_BAD_CALL:
                $message = $this->getNotFoundResponse();
                break;
            case self::FAIL_CALL:
                $message = $this->getResourceNotFoundResponse();
                break;
            default:
                $message = $this->getGoodResponse();
                break;
        }
        $this->_curlFactory = $this->getCurlFactory(
            $this->_objectManager, $message
        );
        $this->_model = new CatImageApi(
            $this->_curlFactory,
            $this->randomcatLogger,
            $this->jsonHelper,
            $this->randomcatHelper
        );
        return $this->_model;
    }

    /**
     * @param ObjectManager $objectHelper
     * @param string $response
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCurlFactory(ObjectManager $objectHelper, $response = "")
    {
        $httpClient = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\Curl')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $httpClient->expects(static::any())
            ->method('read')
            ->will($response);

        $curlFactory = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\CurlFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'getHttpClient'])
            ->getMock();
        $curlFactory->expects(static::any())->method('create')->willReturn($httpClient);
        $curlFactory->expects(static::any())->method('getHttpClient')->willReturn($httpClient);

        return $curlFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\Stub\ReturnStub
     */
    public function getGoodResponse()
    {
        return static::returnValue(
            'HTTP/1.1 200 OK' .
            "\r\n" . 'Server: nginx/1.14.0' .
            "\r\n\r\n" . '{"url":"https://randomcatapi.commerce.lingaro.dev/images/cat7.jpg"}'
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\Stub\ReturnStub
     */
    public function getNotFoundResponse()
    {
        return static::returnValue(
            'HTTP/1.1 404 Not Found' .
            "\r\n" . 'Server: nginx/1.14.0' .
            "\r\n\r\n" . '404 - cat not found'
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\Stub\ReturnStub
     */
    public function getResourceNotFoundResponse()
    {
        return static::returnValue(
            'HTTP/1.1 404 Not Found' .
            "\r\n" . 'Server: nginx/1.14.0' .
            "\r\n\r\n" . '404 - resource not found'
        );
    }


}