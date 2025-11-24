<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PER-CS3x0' => true,
        '@PSR12' => true,
        'fully_qualified_strict_types' => true,
        'modifier_keywords' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_align' => true,
        'phpdoc_order' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
    ])
    ->setFinder($finder)
;
