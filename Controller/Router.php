<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    protected bool $isDispatchedFlag = false;

    public function __construct(
        protected \Magento\Framework\App\ActionFactory $actionFactory,
        protected \Magento\Framework\App\ResponseInterface $response,
        protected \MageSuite\BrandManagement\Helper\Configuration $configuration
    ) {}

    public function match(\Magento\Framework\App\RequestInterface $request) // phpcs:ignore
    {
        if ($this->isDispatchedFlag) {
            return null;
        }

        $identifier = trim($request->getPathInfo(), '/');
        $identifierParts = explode('/', $identifier);
        $routeToBrand = $this->configuration->getRouteToBrand();

        if ($identifierParts[0] !== $routeToBrand) {
            return null;
        }

        $this->isDispatchedFlag = true;

        $request->setModuleName('brands');
        $request->setControllerName('index');
        $request->setActionName('all');

        if (count($identifierParts) > 1) {
            $request->setActionName('index');
            $request->setParam('brand', $identifierParts[1]);
        }

        return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
    }
}
