<?php

namespace PrototypeIntegration\Demo\Processors\Plugins\News;

use GeorgRinger\News\Controller\NewsController;
use GeorgRinger\News\Domain\Model\News;
use PrototypeIntegration\PrototypeIntegration\DependencyInjection\Attribute\AsExtbaseProcessor;
use PrototypeIntegration\PrototypeIntegration\Processor\PtiDataProcessor;
use PrototypeIntegration\PrototypeIntegration\Processor\RichtextProcessor;

#[AsExtbaseProcessor(
    controller: NewsController::class,
    action: 'list',
    template: '@content-elements/ce02-news-list/ce02-news-list.twig',
)]
class NewsListView implements PtiDataProcessor
{
    public function __construct(
        protected RichtextProcessor $richtextProcessor,
    ) {
    }

    public function process(array $data, array $configuration): ?array
    {
        $newsItems = $data['news']->toArray();
        $result= [
            'type' => 'ce02-news-list',
            'headline' => $data['contentObjectData']['header'],
            'news' => array_map([$this, 'translateNewsItem'], $newsItems),
        ];
        return $result;
    }

    protected function translateNewsItem(News $newsItem): array
    {
        return [
            'title' => $newsItem->getTitle(),
            'teaser' => $newsItem->getTeaser(),
            'text' => $this->richtextProcessor->processRteText($newsItem->getBodytext()),
        ];
    }
}
