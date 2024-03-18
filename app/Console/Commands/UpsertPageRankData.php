<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPageRankData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpsertPageRankData extends Command
{
    protected $signature = 'app:upsert-page-rank-data';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $sitesJson = config('services.api.page_rank');

        $sitesJsonResponse = Http::get($sitesJson)->json();
        $chunkedResponse = array_chunk($sitesJsonResponse, 100);

        foreach ($chunkedResponse as $chunk)
        {
            ProcessPageRankData::dispatch($chunk);

            $this->info('Chunk processing job dispatched');
        }

        $this->info('Finished');
    }
}
