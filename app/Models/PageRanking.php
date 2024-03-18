<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $rank
 * @property string $domain
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PageRanking extends Model
{
    use HasFactory;

    protected $table = 'page_ranks';

    protected $fillable = [
        'rank',
        'domain',
    ];
}
