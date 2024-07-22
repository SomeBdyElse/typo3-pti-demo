<?php
defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'ptidemo/Configuration/TypoScript/';

$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pti_twig']['disableCache'] = false;
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pti_twig']['rootTemplatePath'] = '/app/patternlab/source/_patterns';
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pti_twig']['loader'] = [];
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pti_twig']['namespaces'] = [
    'pages' => '/app/patternlab/source/_patterns/pages',
    'content-elements' => '/app/patternlab/source/_patterns/content-elements',
    'molecules' => '/app/patternlab/source/_patterns/molecules',
];

$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pti']['defaultView'] = \PrototypeIntegration\Twig\TwigView::class;
