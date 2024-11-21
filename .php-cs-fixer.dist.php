<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'vendor'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'single_line_empty_body' => true,
        'concat_space' => ['spacing' => 'one'],
        'types_spaces' => ['space' => 'single'],
    ])
    ->setFinder($finder)
;
