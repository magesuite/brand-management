<?php
namespace MageSuite\BrandManagement\Controller;

use Magento\Framework\Url;

class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var bool
     */
    protected $dispatched;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->dispatched) {
            $identifier = trim($request->getPathInfo(), '/');
            $identifierParts = explode('/', $identifier);

            if(count($identifierParts) == 1 && $identifierParts[0] == \MageSuite\BrandManagement\Model\Brand::BRAND_URL) {
                $request
                    ->setModuleName('brands')
                    ->setControllerName('index')
                    ->setActionName('all');

                $this->dispatched = true;

            }

            if(count($identifierParts) > 1 && $identifierParts[0] == \MageSuite\BrandManagement\Model\Brand::BRAND_URL) {
                $request
                    ->setModuleName('brands')
                    ->setControllerName('index')
                    ->setActionName('index')
                    ->setParam('brand', $identifierParts[1]);

                $this->dispatched = true;

            } else {
                return;
            }

            return $this->actionFactory->create(
                'Magento\Framework\App\Action\Forward',
                ['request' => $request]
            );
        }



    }
}