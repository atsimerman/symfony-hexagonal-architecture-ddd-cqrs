<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony'                                      => true, // Includes PSR1, PSR2, and PSR12
        'yoda_style'                                    => false,
        'array_syntax'                                  => ['syntax' => 'short'],
        'array_indentation'                             => true,
        'phpdoc_indent'                                 => false,
        'phpdoc_separation'                             => false,
        'phpdoc_order'                                  => true,
        'phpdoc_align'                                  => ['align' => 'left'],
        'phpdoc_scalar'                                 => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'no_superfluous_phpdoc_tags'                    => ['allow_mixed' => true],
        'phpdoc_add_missing_param_annotation'           => ['only_untyped' => true],
        'general_phpdoc_annotation_remove'              => ['annotations' => ['author', 'class', 'package', 'throws']],
        'method_argument_space'                         => ['on_multiline' => 'ignore'],
        'single_quote'                                  => true, // Aligning with Symfony preference
        'phpdoc_to_param_type'                          => true,
        'phpdoc_to_property_type'                       => ['scalar_types' => false],
        'phpdoc_to_return_type'                         => true,
        'declare_strict_types'                          => true,
        'strict_comparison'                             => true,
        'binary_operator_spaces'                        => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'align',
            ],
        ],
    ])
    ->setFinder($finder)
;
