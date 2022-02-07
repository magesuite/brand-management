<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    protected $isDispatchedFlag = false;

    protected \Magento\Framework\App\ActionFactory $actionFactory;

    protected \Magento\Framework\App\ResponseInterface $response;

    protected \MageSuite\BrandManagement\Helper\Configuration $configuration;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \MageSuite\BrandManagement\Helper\Configuration $configuration
    ) {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->configuration = $configuration;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if ($this->isDispatchedFlag) {
            return;
        }

        $identifier = trim($request->getPathInfo(), '/');
        $identifierParts = explode('/', $identifier);
        $routeToBrand = $this->configuration->getRouteToBrand();

        if ($identifierParts[0] !== $routeToBrand) {
            return;
        }

        $this->isDispatchedFlag = true;
        $request->setModuleName('brands')
            ->setControllerName('index')
            ->setActionName('all');

        if (count($identifierParts) > 1) {
            $request->setActionName('index')
                ->setParam('brand', $identifierParts[1]);
        }

        return $this->actionFactory->create(
            \Magento\Framework\App\Action\Forward::class,
            ['request' => $request]
        );
    }
}
