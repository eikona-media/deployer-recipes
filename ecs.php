<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(HeaderCommentFixer::class)
        ->call('configure', [[
            'header' => "This file is part of EIKONA Media deployer recipe.\n\n(c) eikona-media.de\n\n@license MIT",
        ]]);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [SetList::CLEAN_CODE, SetList::SYMFONY]);
    $parameters->set(Option::PATHS, [__DIR__ . '/recipe']);
};
