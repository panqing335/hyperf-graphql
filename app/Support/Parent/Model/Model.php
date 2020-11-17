<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Support\Parent\Model;

use App\Support\Entity\CamelCase;
use Carbon\Carbon;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;
use Hyperf\Utils\ApplicationContext;
use Redis;

/**
 * Class Model.
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 * @property Carbon $deletedTime
 */
abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;
    use CamelCase;
    use SoftDeletes;

    public const CREATED_AT = 'created_time';

    public const UPDATED_AT = 'updated_time';

    public const DELETED_AT = 'deleted_time';

    /**
     * @param $id
     * @return \Hyperf\Database\Model\Model|self
     */
    public static function findFromCache($id): ?\Hyperf\Database\Model\Model
    {
        $class = static::class;
        /** @var Model $instance */
        $instance = new $class();

        /** @var Redis $redis */
        $redis = ApplicationContext::getContainer()->get(Redis::class);

        $cacheKey = $instance->getTable() . ':' . $id;
        $cacheString = $redis->get($cacheKey);

        if (!empty($cacheString)) {
            $cache = json_decode($cacheString, true);
            if (!empty($cache)) {
                $model = $instance->newFromBuilder($cache);

                return $model->{static::DELETED_AT} ? null : $model;
            }
        }

        return $instance->newQuery()->find($id);
    }

    public static function findManyFromCache(array $ids): Collection
    {
        if (count($ids) === 0) {
            return new Collection([]);
        }
        $class = static::class;
        /** @var Model $instance */
        $instance = new $class();

        /** @var Redis $redis */
        $redis = ApplicationContext::getContainer()->get(Redis::class);
        $primaryKey = $instance->getKeyName();

        $keys = [];
        foreach ($ids as $id) {
            $keys[] = $instance->getTable() . ':' . $id;
        }
        $data = $redis->mget($keys);
        $items = [];
        $fetchIds = [];
        foreach ($data ?? [] as $item) {
            if (is_string($item)) {
                $item = json_decode($item, true);
            }
            if (is_array($item) && isset($item[$primaryKey])) {
                $items[] = $item;
                $fetchIds[] = $item[$primaryKey];
            }
        }
        // Get ids that not exist in cache handler.
        $targetIds = array_diff($ids, $fetchIds);
        if ($targetIds) {
            $models = $instance->newQuery()->whereIn($primaryKey, $targetIds)->get()->all();
            $items = array_merge($items, self::formatModels($models));
        }

        return $instance->newQuery()->hydrate($items)->filter(fn($item) => $item->{static::DELETED_AT} === null);
    }

    public function getGuarded()
    {
        if ($this->guarded == ['*']) {
            return $this->guarded;
        }

        return ['editStatus', ...$this->guarded];
    }

    protected static function formatModels($models): array
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = self::formatModel($model);
        }

        return $result;
    }

    protected static function formatModel(BaseModel $model): array
    {
        return $model->getAttributes();
    }

    public static function buildBetween(Builder $query, string $column, array $values, string $relation = '')
    {
        if (count($values) === 1) {
            if ($relation) {
                return $query->whereHas($relation, fn(Builder $query) => $query->where($column, '>=', $values[0]));
            } else {
                return $query->where($column, '>=', $values[0]);
            }
        }
        $values = array_slice($values, 0, 2);
        if ((int)$values[0] > 0) {
            if ($relation) {
                return $query->whereHas($relation, fn(Builder $query) => $query->whereBetween($column, $values));
            } else {
                return $query->whereBetween($column, $values);
            }
        }

        if ($relation) {
            return $query->whereHas($relation, fn(Builder $query) => $query->where($column, '<=', $values[1]));
        } else {
            return $query->where($column, '<=', $values[1]);
        }
    }

}
