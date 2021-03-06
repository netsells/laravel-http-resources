<?php

namespace Netsells\Http\Resources\Eloquent;

use Netsells\Http\Resources\DeferredValue;
use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

abstract class EloquentDeferredValue extends DeferredValue
{
    /**
     * @var string[]
     */
    public $relations;

    /**
     * EloquentDeferredValue constructor.
     * @param JsonResource|ResourceCollection $resource
     * @param array $relations
     * @param callable|null $callback
     */
    public function __construct($resource, array $relations, ?callable $callback = null)
    {
        parent::__construct($resource, $callback);
        $this->relations = $relations;
    }

    /**
     * @param static[] $deferredValues
     */
    public static function resolve(array $deferredValues)
    {
        static::loadEloquentRelations($deferredValues);

        collect($deferredValues)
            ->filter(function (EloquentDeferredValue $deferredValue) {
                return $deferredValue->resolver;
            })
            ->each(function (EloquentDeferredValue $deferredValue) {
                $relations = collect($deferredValue->relations)->map(function ($relation) use ($deferredValue) {
                    return data_get($deferredValue->resource, $relation);
                })->all();

                ($deferredValue->resolver)(...$relations);
            });
    }

    /**
     * Begins eager loading eloquent model relations.
     * @param static[] $deferredValues
     * @return string
     */
    abstract protected static function loadEloquentRelations(array $deferredValues);
}
