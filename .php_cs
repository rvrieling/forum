<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$excluded_folders = [
    'node_modules',
    'storage',
    'vendor'
];

$finder = Finder::create()
    ->exclude($excluded_folders)
    ->notName('AcceptanceTester.php')
    ->notName('FunctionalTester.php')
    ->notName('UnitTester.php')
    ->notName('_ide_helper.php')
    ->notName('_ide_helper_models.php')
    ->in(__DIR__);

$rules = [
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => ['statements' => ['die', 'do', 'exit', 'for', 'foreach', 'if', 'return', 'switch', 'throw', 'try', 'while',]],
    'binary_operator_spaces' => ['default' => 'align_single_space'],
    'function_typehint_space' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_unused_imports' => true,
    'no_empty_comment' => false,
    'no_empty_phpdoc' => true,
    'no_useless_return' => true,
    'not_operator_with_successor_space' => true,
    'ordered_imports' => true,
    'phpdoc_order' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'short_scalar_cast' => true,
    'single_blank_line_before_namespace' => true,
    'ternary_operator_spaces' => true,
];

return Config::create()
    ->setFinder($finder)
    ->setRules($rules)
    ->setUsingCache(true);
