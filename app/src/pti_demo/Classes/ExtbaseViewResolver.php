<?php

namespace PrototypeIntegration\Demo;

use GeorgRinger\News\Controller\NewsController;
use PrototypeIntegration\Demo\Processors\Plugins\News\NewsListView;
use PrototypeIntegration\PrototypeIntegration\Processor\PtiDataProcessor;
use PrototypeIntegration\PrototypeIntegration\View\ViewAdapter;
use PrototypeIntegration\PrototypeIntegration\View\ViewResolverInterface;
use Psr\Container\ContainerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\GenericViewResolver;
use TYPO3Fluid\Fluid\View\ViewInterface;

class ExtbaseViewResolver extends GenericViewResolver
{
    public function __construct(
        ContainerInterface $container,
        protected ViewResolverInterface $viewResolver,
    ) {
        parent::__construct($container);
    }

    public function resolve(
        string $controllerObjectName,
        string $actionName,
        string $format
    ): ViewInterface
    {
        $overrides = [
            NewsController::class => [
                'list' => [
                    'processors' => [NewsListView::class],
                    'template' => '@content-elements/ce02-news-list/ce02-news-list.twig',
                ],
            ],
        ];

        if (isset($overrides[$controllerObjectName][$actionName])) {
            $override = $overrides[$controllerObjectName][$actionName];
            $processors = array_map(
                fn(string $className): PtiDataProcessor => GeneralUtility::makeInstance($className),
                $override['processors'],
            );

            $view = $this->viewResolver->getViewForExtbaseAction(
                $controllerObjectName,
                $actionName,
                $format,
                $override['template'] ?? null,
            );

            return GeneralUtility::makeInstance(ViewAdapter::class, $processors, $view);
        }

        return parent::resolve($controllerObjectName, $actionName, $format);
    }
}