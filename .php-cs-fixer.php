<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use RVxLab\PhpCsFixerRules\{RuleSet, RuleSetRisky};

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setFinder($finder)
    ->registerCustomRuleSets([
        new RuleSet(),
        new RuleSetRisky(),
    ])
    ->setRiskyAllowed(true)
    ->setRules([
        RuleSet::NAME => true,
        RuleSetRisky::NAME => true,
        'final_class' => false,
    ]);
