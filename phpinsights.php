<?php

declare(strict_types=1);

return [
    'preset' => 'laravel',
    'exclude' => [
        'src/Sport.php',
        'src/ReturnObjects/Fixture.php',
        //  'path/to/directory-or-file'
    ],
    'add' => [
        //  ExampleMetric::class => [
        //      ExampleInsight::class,
        //  ]
    ],
    'remove' => [
        //  ExampleInsight::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff::class,
        \NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenGlobals::class,
        \SlevomatCodingStandard\Sniffs\Files\LineLengthSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff::class,
        \SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff::class,
    ],
    'config' => [
        \NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 6,
        ],
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 120,
            'ignoreComments' => false,
        ],
    ],
];
