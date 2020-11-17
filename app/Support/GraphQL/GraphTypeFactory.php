<?php


namespace App\Support\GraphQL;


use App\Graph\Type\TMutationResult;
use App\Graph\Type\TPaginator;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Definition\PriceType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Di\Annotation\Inject;

class GraphTypeFactory
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    public function __construct()
    {
    }

    public function get(string $class, string $name = '', array $params = [])
    {
        $key = "__GRAPH_{$class}_{$name}";
        if ($this->container->has($key)) {
            return $this->container->get($key);
        }

        $typeInstance = $this->container->make($class, [$params, $this]);
        $this->container->set($key, $typeInstance);
        return $typeInstance;
    }

    public function input(string $class, string $name = '')
    {
        /** @var ObjectType $type */
        $type = $this->get($class, $name);

        $key = "__GRAPH_{$class}_{$name}_input";
        if ($this->container->has($key)) {
            return $this->container->get($key);
        }
        $inputType = $type->buildInput($name);
        $this->container->set($key, $inputType);
        return $inputType;
    }

    public function boolean()
    {
        return Type::boolean();
    }

    public function float()
    {
        return Type::float();
    }

    public function id()
    {
        return Type::id();
    }

    public function paginator($className, string $description = '', array $args = [])
    {
        if (is_string($className)) {
            /** @var ObjectType $type */
            $type = $this->get($className);
        } else {
            $type = $className;
        }

        $paginationName = $type->typeName.'Pagination';

        return $this->fast(
            $this->get(TPaginator::class, $paginationName, [
                'name' => $paginationName,
                'fields' => [
                    'items' => $this->listOf($type),
                ],
            ]),
            $description,
            array_merge([
                'page' => $this->fastInt('页码'),
                'pageSize' => $this->fastNullableInt('每页数量', 20),
            ], $args)
        );
    }

    /**
     * 变更返回类型
     * @return TMutationResult
     */
    public function result(): TMutationResult
    {
        return $this->get(TMutationResult::class);
    }

    /**
     * 带额外字段的返回类型
     * @param array $fields
     * @param string $name
     * @return TMutationResult
     */
    public function resultWithFields(string $name, array $fields)
    {
        $typeName = $name . 'Result';
        return $this->get(TMutationResult::class, $typeName, [
            'name' => $typeName,
            'fields' => $fields,
        ]);
    }

    public function int()
    {
        return Type::int();
    }

    public function string()
    {
        return Type::string();
    }

    public function nonNull($type)
    {
        if (gettype($type) == 'string') {
            return $this->nonNull($this->get($type));
        }

        return new NonNull($type);
    }

    public function nonNullInt()
    {
        return $this->nonNull($this->int());
    }

    public function nonNullString()
    {
        return $this->nonNull($this->string());
    }

    public function price()
    {
        return $this->get(PriceType::class);
    }

    public function fastPrice(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->nonNull($this->price()), $description, $defaultValue, $args);
    }

    /**
     * 快速定义类型
     * @param mixed $type
     * @param string $description
     * @param array $args
     * @param mixed $defaultValue
     * @return array
     */
    public function fast($type, string $description = '', $args = [], $defaultValue = null)
    {
        if (gettype($type) == 'string') {
            $type = $this->get($type);
        }

        $type = [
            'type' => $type,
            'desc' => $description,
            'args' => $args,
        ];
        if ($defaultValue !== null) {
            $type['defaultValue'] = $defaultValue;
        }

        return $type;
    }

    /**
     * 快速定义非空类型
     * @param mixed $type
     * @param string $description
     * @param array $args
     * @param mixed $defaultValue
     * @return array
     */
    public function fastNonNull($type, string $description = '', $args = [], $defaultValue = null)
    {
        return $this->fast(
            $this->nonNull($type),
            $description,
            $args,
            $defaultValue
        );
    }

    public function fastNullableString(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->string(), $description, $args, $defaultValue);
    }

    public function fastNullableInt(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->int(), $description, $args, $defaultValue);
    }

    public function fastNullableId(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->id(), $description, $args, $defaultValue);
    }

    public function fastNullableBoolean(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->boolean(), $description, $args, $defaultValue);
    }

    public function fastNullableFloat(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->float(), $description, $args, $defaultValue);
    }

    public function fastString(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->nonNull($this->string()), $description, $args, $defaultValue);
    }

    public function fastInt(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->nonNull($this->int()), $description, $args, $defaultValue);
    }

    public function fastId(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->nonNull($this->id()), $description, $args, $defaultValue);
    }

    public function fastBoolean(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->nonNull($this->boolean()), $description, $args, $defaultValue);
    }

    public function fastFloat(string $description = '', $defaultValue = null, $args = [])
    {
        return $this->fast($this->nonNull($this->float()), $description, $args, $defaultValue);
    }

    public function fastResult(string $description = '', $args = [])
    {
        return $this->fast(
            $this->result(),
            $description,
            $args
        );
    }

    public function fastResultWithFields(string $name, string $description, array $args = [], array $fields = [])
    {
        return $this->fast(
            $this->resultWithFields($name, $fields),
            $description,
            $args
        );
    }

    public function listOf($type)
    {
        if (gettype($type) == 'string') {
            return $this->listOf($this->get($type));
        }

        return new ListOfType($type);
    }

    public function fastListOf($type, string $description = '', $defaultValue = null, $args = [], bool $nonNull = false)
    {
        if ($nonNull) {
            return $this->fastNonNull($this->listOf($type), $description, $args, $defaultValue);
        } else {
            return $this->fast($this->listOf($type), $description, $args, $defaultValue);
        }
    }

    public function enumValue($value, $description = '')
    {
        return [
            'value' => $value,
            'description' => $description,
        ];
    }
}
