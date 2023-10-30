<?php

namespace Abhinay\RandomCat\Block\Product;

/**
 * Class RandomCatImage
 * @package Abhinay\RandomCat\Block\Product
 */
class RandomCatImage extends \Magento\Catalog\Block\Product\Image
{
    /**
     * @var \Abhinay\RandomCat\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Abhinay\RandomCat\Model\CatImageApi
     */
    protected $catImageApi;

    /**
     * RandomCatImage constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Abhinay\RandomCat\Model\CatImageApi $catImageApi
     * @param \Abhinay\RandomCat\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Abhinay\RandomCat\Model\CatImageApi $catImageApi,
        \Abhinay\RandomCat\Helper\Data $helper,
        array $data = []
    )
    {
        if (isset($data['template'])) {
            $this->setTemplate($data['template']);
            unset($data['template']);
        }
        parent::__construct($context, $data);
        $this->catImageApi = $catImageApi;
        $this->_helper = $helper;
    }

    /**
     * Get Original Image URL
     * @return string
     */
    public function getOriginalImageUrl()
    {
        return parent::getImageUrl();
    }

    /**
     * Get Image URL
     * Get Image Random Cat Image If module enable
     * @return mixed|null|string
     */
    public function getImageUrl()
    {
        if (!$this->_helper->getGeneralConfigValue('enable')) {
            return $this->getOriginalImageUrl();
        }
        $imageUrl = $this->catImageApi->getRadomCatImageUrl();
        if (is_null($imageUrl)) {
            return $this->getCatImageNotFound();
        }
        return $imageUrl;
    }

    /**
     * Get Static Image URL Incase if random image not found
     * @return string
     */
    public function getCatImageNotFound()
    {
        return $this->getViewFileUrl('Abhinay_RandomCat::images/404.jpg');
    }

}