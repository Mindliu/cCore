<?php

namespace App\Jobs;

use App\Models\PageRanking;
use App\Repositories\PageRankingRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessPageRankData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PageRankingRepository $pageRankingRepository;

    public function __construct(
        private readonly array $chunk
    ) {
        //
    }

    public function handle(
        PageRankingRepository $pageRankingRepository
    ): void
    {
        $this->pageRankingRepository = $pageRankingRepository;
        $fetchedRankings = array_column($this->chunk, 'rootDomain');
        $existingRankings = $this->pageRankingRepository->getChunk($fetchedRankings);

        $missingRankings = array_filter(
            $this->chunk,
            fn ($ranking) => !in_array($ranking['rootDomain'], $existingRankings->pluck('domain')->toArray())
        );

        if (!empty($missingRankings)) {
            $this->importMissingRanking($missingRankings);
        }

        $rankingsToUpdate = array_filter(
            $this->chunk,
            function ($ranking) use($existingRankings) {
                /** @var PageRanking $matchingPageRanking */
                $matchingPageRanking = $existingRankings->where('domain', $ranking['rootDomain'])->first();

                if ($matchingPageRanking) {
                    return $ranking['rank'] !== $matchingPageRanking->rank;
                }

                return false;
            });

        if (!empty($rankingsToUpdate)) {
            $this->updateExistingRankings($rankingsToUpdate);
        }
    }

    private function importMissingRanking(array $missingRankings): bool
    {
        try {
            $missingRankings = $this->prepareRankingData($missingRankings);

            return $this->pageRankingRepository->storeBulk($missingRankings);
        } catch (Exception $exception) {
            Log::warning('PageRankingInsert', [
                'message' => 'Could\'nt insert missing rankings',
                'error' => $exception->getMessage(),
            ]);
        }

        return false;
    }

    private function updateExistingRankings(array $rankingsToUpdate): int
    {
        try {
            $rankingsToUpdate = $this->prepareRankingData($rankingsToUpdate);

            return $this->pageRankingRepository->upsert($rankingsToUpdate);
        } catch (Exception $exception) {
            Log::warning('PageRankingInsert', [
                'message' => 'Could\'nt insert missing rankings',
                'error' => $exception->getMessage(),
            ]);
        }

        return 0;
    }

    private function prepareRankingData(array $rankings): array
    {
        return array_map(fn ($ranking) => [
            'rank' => $ranking['rank'],
            'domain' => $ranking['rootDomain'],
            'created_at' => now(),
            'updated_at' => now(),
        ], $rankings);
    }
}
