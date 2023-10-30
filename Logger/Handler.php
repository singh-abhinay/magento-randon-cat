<?php

namespace Abhinay\RandomCat\Logger;

use \Magento\Framework\Logger\Handler\Base;

/**
 * Class Handler
 * @package Abhinay\RandomCat\Logger
 */
class Handler extends Base
{
    const FILE_NAME = '/var/log/random_cat.log';

    /**
     * @var string
     */
    protected $fileName = HANDLER::FILE_NAME;
    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;
}