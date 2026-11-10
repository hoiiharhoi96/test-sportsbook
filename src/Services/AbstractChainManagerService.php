<?php

declare(strict_types=1);

namespace App\Services;

use App\Providers\AppProvider;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

abstract class AbstractChainManagerService
{
    protected private(set) object $internalFirst;
    abstract protected object $first { get; }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(readonly protected Container $container)
    {
        $map = AppProvider::PIPELINE_MAPPING[static::class];
        $this->internalFirst = $container->get($map[0]);
        $currentParser = $this->internalFirst;
        for ($i = 1; $i < count($map); ++$i) {
            $parser = $container->get($map[$i]);
            $currentParser->next = $parser;
            $currentParser = $parser;
        }
    }
}
