<?php

namespace JomaShop\NewRelicMonitoring\Plugin;

use Magento\NewRelicReporting\Model\NewRelicWrapper;
use JomaShop\NewRelicMonitoring\Controller\Soap as SoapController;

class Soap
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

    public function aroundProcess(SoapController $soap, callable $proceed, ...$args)
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