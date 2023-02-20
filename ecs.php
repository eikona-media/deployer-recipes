<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([SetList::CLEAN_CODE, SetList::PSR_12]);

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, [
        'header' => "This file is part of EIKONA Media deployer recipe.\n\n(c) eikona-media.de\n\n@license MIT",
    ]);

    $ecsConfig->parallel();
    $ecsConfig->lineEnding("\n");
};
