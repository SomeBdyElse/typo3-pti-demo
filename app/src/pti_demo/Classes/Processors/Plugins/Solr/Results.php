<?php

namespace PrototypeIntegration\Demo\Processors\Plugins\Solr;

use ApacheSolrForTypo3\Solr\Controller\SearchController;
use ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\SearchResultSet;
use PrototypeIntegration\PrototypeIntegration\DependencyInjection\Attribute\AsExtbaseProcessor;
use PrototypeIntegration\PrototypeIntegration\Processor\PtiDataProcessor;
use PrototypeIntegration\PrototypeIntegration\View\FluidViewAdapter;

#[AsExtbaseProcessor(
    controller: SearchController::class,
    action: 'results',
    template: '@content-elements/ce04-search/ce04-search.twig',
    adapterClassName: FluidViewAdapter::class,
)]
class Results implements PtiDataProcessor
{
    public function __construct(
    ) {
    }

    public function process(array $data, array $configuration): ?array
    {
        /** @var SearchResultSet $searchResultSet */
        $searchResultSet = $data['resultSet'];

        $results = [];
        foreach($searchResultSet->getSearchResults() as $searchResult) {
            $results[] = [
                'title' => $searchResult->title,
                'url' => $searchResult->url,
            ];
        }

        $result = [
            'hasSearched' => $searchResultSet->getHasSearched(),
            'results' => $results,
            'query' => $data['q'] ?? '',
        ];

        $previousQuery = $searchResultSet->getUsedSearchRequest()?->getRawUserQuery();
        if (isset($previousQuery)) {
            $result['query'] = $previousQuery;
        }

        return $result;
    }
}
