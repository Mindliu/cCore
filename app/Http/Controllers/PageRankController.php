<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageRankResource;
use App\Repositories\PageRankingRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PageRankController extends Controller
{
    public function __construct(
        private readonly PageRankingRepository $pageRankingRepository
    ) {
    }

    public function list(Request $request): AnonymousResourceCollection
    {
        $pageRanks = $this->pageRankingRepository->searchPaginated(
            $request['q'] ?? null,
            $request['limit'] ?? 100,
            $request['page'] ?? 1,
        );

        return PageRankResource::collection($pageRanks);
    }
}
