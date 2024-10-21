<?php

namespace PrototypeIntegration\Demo\Processors\ContentElements;

use PrototypeIntegration\PrototypeIntegration\Processor\PtiDataProcessor;
use PrototypeIntegration\PrototypeIntegration\Processor\RichtextProcessor;

class Ce01Text implements PtiDataProcessor
{
    public function __construct(
        protected RichtextProcessor $richtextProcessor,
    ) {
    }

    public function process(array $data, array $configuration): ?array
    {
        return [
            'type' => 'ce01-text',
            'headline' => $data['header'],
            'richtext' => $this->richtextProcessor->processRteText($data['bodytext']),
        ];
    }
}