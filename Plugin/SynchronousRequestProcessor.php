<?php

namespace JomaShop\NewRelicMonitoring\Plugin;

use Magento\NewRelicReporting\Model\NewRelicWrapper;
use Magento\Webapi\Controller\Rest\InputParamsResolver;
use Magento\Webapi\Controller\Rest\SynchronousRequestProcessor as MagentoSynchronousRequestProcessor;
use \Magento\Framework\Webapi\Rest\Request;

class SynchronousRequestProcessor
{
    /**
     * @var NewRelicWrapper
     */
    private $newRelicWrapper;

    /**
     * SynchronousRequestProcessor constructor.
     * @param NewRelicWrapper $newRelicWrapper
     */
    public function __construct(
        NewRelicWrapper $newRelicWrapper
    ) {
        $this->newRelicWrapper = $newRelicWrapper;
    }

    /**
     * @param MagentoSynchronousRequestProcessor $requestProcessor
     * @param callable $proceed
     * @param mixed ...$args
     * @return mixed
     * @throws \Exception
     */
    public function aroundProcess(MagentoSynchronousRequestProcessor $requestProcessor, callable $proceed, ...$args)
    {
        try {
            $result = $proceed(...$args);
        } catch (\Exception $exception) {
            $this->newRelicWrapper->reportError($exception);
            throw $exception;
        }

        return $result;
    }
}