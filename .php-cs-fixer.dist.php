<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->notPath('bootstrap.php')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        '@PHP8x4Migration' => true,
        '@PHP8x5Migration' => true,

        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'native_function_invocation' => [
            'scope' => 'namespaced',
            'include' => ['@all'],
            'strict' => true,
        ],
        'declare_strict_types' => false,

        'nullable_type_declaration_for_default_null_value' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_functions' => true,
            'import_constants' => true,
        ],
        'class_definition' => [
            'single_line' => true,
        ],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_order' => true,
        'phpdoc_to_comment' => true,

        '@PHPUnit84Migration:risky' => true,
    ])
    ->setFinder($finder)
;
