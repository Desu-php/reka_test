<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_completed',
        'user_id'
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function image(): MorphOne
    {
        return $this->attachment()
            ->whereGroup(null);
    }

    public function preview(): MorphOne
    {
        return $this->attachment()
            ->whereGroup('preview');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'resource');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'resource');
    }

    public function scopeSearch(Builder $builder, string $query): void
    {
        $builder->where(function (Builder $builder) use ($query) {
            $builder->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('description', 'LIKE', '%' . $query . '%')
                ->orWhereHas('tags',
                    fn(Builder $builder) => $builder->where('tags.name', 'LIKE', '%' . $query . '%')
                );
        });
    }

    public function scopeFilter(Builder $builder, array $tags): void
    {
        $builder->whereHas('tags',
            fn(Builder $builder) => $builder->whereIn('tags.id', $tags)
        );
    }
}
