<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\FullLibrary\Preload;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;

/**
 * @mixin Library
 */
class LibraryResource extends JsonResource
{
    public function preloads()
    {
        return $this->preload('shelves');
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'shelves' => ShelfResource::collection($this->shelves),
        ];
    }
}
