<?php

namespace PrototypeIntegration\Demo\Processors\Plugins\News;

use GeorgRinger\News\Domain\Model\News;
use PrototypeIntegration\PrototypeIntegration\Processor\PtiDataProcessor;
use PrototypeIntegration\PrototypeIntegration\Processor\TypoLinkStringProcessor;

class NewsListView implements PtiDataProcessor
{
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
        ];
    }
}
