<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\FullLibrary\Callback;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'author' => $this->use('author', function () {
                return AuthorResource::make($this->author);
            }),
            'genres' => $this->use('genres', function () {
                return GenreResource::collection($this->genres);
            }),
        ];
    }
}
