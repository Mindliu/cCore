<?php

namespace App\Http\Resources;

use App\Models\PageRanking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageRankResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var PageRanking $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'rank' => $resource->rank,
            'domain' => $resource->domain,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
