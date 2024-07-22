<?php

namespace PrototypeIntegration\Demo\Processors\ContentElements;

use PrototypeIntegration\PrototypeIntegration\Processor\PictureProcessor;
use PrototypeIntegration\PrototypeIntegration\Processor\PtiDataProcessor;
use PrototypeIntegration\PrototypeIntegration\Processor\RichtextProcessor;

class Ce03TextImage implements PtiDataProcessor
{
    public function __construct(
        protected RichtextProcessor $richtextProcessor,
        protected PictureProcessor $pictureProcessor,
    ) {
    }

    public function process(array $data, array $configuration): ?array
    {
        $result = [
            'type' => 'ce03-textpic',
            'headline' => $data['header'],
            'richtext' => $this->richtextProcessor->processRteText($data['bodytext']),
        ];

        $pictures = $this->pictureProcessor->renderPicturesForRelation(
            'tt_content',
            'image',
            $data,
            $configuration['picture'],
        );

        if (isset($pictures[0])) {
            $result['picture'] = $pictures[0];
        }

        return $result;
    }
}