<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\FullLibrary\Preload;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookResource extends JsonResource
{
    public function preloads()
    {
        return $this->preload('author', 'genres');
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'author' => AuthorResource::make($this->author),
            'genres' => GenreResource::collection($this->genres),
        ];
    }
}
