<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PageRanking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PageRankingRepository
{
    public function searchPaginated(?string $q, int $limit, int $page): LengthAwarePaginator
    {
        return PageRanking::query()
            ->when(!is_null($q) , fn ($query) =>
                $query->where('domain', 'LIKE', "%$q%")
            )
            ->paginate(
                perPage: $limit,
                page: $page,
            );
    }

    public function getChunk(array $domains): Collection
    {
        return PageRanking::query()->whereIn('domain', $domains)->get();
    }

    public function storeBulk(array $rankings): bool
    {
        return PageRanking::query()->insert($rankings);
    }

    public function upsert(array $rankings): int
    {
        return PageRanking::query()->upsert($rankings, ['domain'], ['rank']);
    }
}
