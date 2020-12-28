<?php

namespace JomaShop\NewRelicMonitoring\Controller;

class Soap extends \Magento\Webapi\Controller\Soap
{
    /**
     * Dispatch SOAP request.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $path = $this->_pathProcessor->process($request->getPathInfo());
        $this->_request->setPathInfo($path);
        $this->areaList->getArea($this->_appState->getAreaCode())->load(\Magento\Framework\App\Area::PART_TRANSLATE);
        try {
            $this->process();
        } catch (\Exception $e) {
            $this->_prepareErrorResponse($e);
        }
        return $this->_response;
    }

    public function process()
    {
        if ($this->_isWsdlRequest()) {
            $this->validateWsdlRequest();
            $responseBody = $this->_wsdlGenerator->generate(
                $this->_request->getRequestedServices(),
                $this->_request->getScheme(),
                $this->_request->getHttpHost(),
                $this->_soapServer->generateUri()
            );
            $this->_setResponseContentType(self::CONTENT_TYPE_WSDL_REQUEST);
            $this->_setResponseBody($responseBody);
        } elseif ($this->_isWsdlListRequest()) {
            $servicesList = [];
            foreach ($this->_wsdlGenerator->getListOfServices() as $serviceName) {
                $servicesList[$serviceName]['wsdl_endpoint'] = $this->_soapServer->getEndpointUri()
                    . '?' . \Magento\Webapi\Model\Soap\Server::REQUEST_PARAM_WSDL . '&services=' . $serviceName;
            }
            $renderer = $this->rendererFactory->get();
            $this->_setResponseContentType($renderer->getMimeType());
            $this->_setResponseBody($renderer->render($servicesList));
        } else {
            $this->_soapServer->handle();
        }
    }
}