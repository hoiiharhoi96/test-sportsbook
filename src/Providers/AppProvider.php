<?php

declare(strict_types=1);

namespace App\Providers;

use App\Handlers\CountryImporterHandler;
use App\Handlers\CountryParserHandler;
use App\Handlers\EventImporterHandler;
use App\Handlers\EventParserHandler;
use App\Handlers\GroupMarketsParserHandler;
use App\Handlers\MarketImporterHandler;
use App\Handlers\OutcomeImporterHandler;
use App\Handlers\ScopeImporterHandler;
use App\Handlers\SportImporterHandler;
use App\Handlers\SportParserHandler;
use App\Handlers\TournamentImporterHandler;
use App\Handlers\TournamentParserHandler;
use App\Services\ImporterChainManagerService;
use App\Services\ParserChainManagerService;

class AppProvider
{
    public const array PIPELINE_MAPPING = [
        ParserChainManagerService::class => [
            SportParserHandler::class,
            CountryParserHandler::class,
            TournamentParserHandler::class,
            EventParserHandler::class,
            GroupMarketsParserHandler::class
        ],

        ImporterChainManagerService::class => [
            SportImporterHandler::class,
            CountryImporterHandler::class,
            TournamentImporterHandler::class,
            EventImporterHandler::class,
            ScopeImporterHandler::class,
            MarketImporterHandler::class,
            OutcomeImporterHandler::class,
        ],
    ];
}
