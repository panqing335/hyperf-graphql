<?php

namespace App\Support\GraphQL\Definition;

use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\ObjectType as GraphQLObjectType;

abstract class ObjectType extends GraphQLObjectType
{
    public string $typeName = '';

    public array $internalFields = [];

    private $args;

    /**
     * @var GraphTypeFactory
     */
    private GraphTypeFactory $typeFactory;

    public function __construct($args, GraphTypeFactory $typeFactory)
    {
        $this->args = $args;
        $this->typeFactory = $typeFactory;

        // 获取属性
        $attrs = new GraphTypeAttrs();
        $this->attrs($attrs);
        $this->typeName = array_key_exists('name', $args) ? $args['name'] : $attrs->name;

        $config = [
            'name' => $this->typeName,
            'description' => array_key_exists('desc', $args) ? $args['desc'] : $attrs->desc,
            'fields' => $this->buildFields(),
            'resolveField' => function ($val, $args, $context, ResolveInfo $info) {
                // 处理fieldsMap
                $fieldName = $info->fieldName;
                $fieldsMap = $this->fieldsMap();
                if (count($fieldsMap) > 0 && array_key_exists($fieldName, $fieldsMap)) {
                    $fieldName = $fieldsMap[$fieldName];
                }

                // 替换fieldName中的_下划线
                $methodName = "resolve" . str_replace('_', '', $fieldName);

                if (method_exists($this, $methodName)) {
                    return $this->{$methodName}($val, $args, $context, $info);
                } else {
                    // 如果定义了resolveField则使用它
                    if (method_exists($this, 'resolveField')) {
                        return $this->resolveField($val, $args, $context, $info);
                    } elseif (is_object($val)) {
                        return isset($val->{$fieldName}) ? $val->{$fieldName} : null;
                    } else if (is_array($val)) {
                        return array_key_exists($fieldName, $val) ? $val[$fieldName] : null;
                    } else {
                        return null;
                    }
                }
            }
        ];

        parent::__construct($config);
    }

    public function buildFields()
    {
        $args = $this->args;
        // 判断是否从args传入
        if (array_key_exists('fields', $args)) {
            $fields = array_merge($this->fields($this->typeFactory), $args['fields']);
        } else {
            $fields = $this->fields($this->typeFactory);
        }

        foreach ($fields as $key => &$field) {
            if (is_array($field)) {
                // 过滤fields简写
                if (array_key_exists('desc', $field)) {
                    $field['description'] = $field['desc'];
                }
                // 过滤args简写
                if (array_key_exists('args', $field) && is_array($field['args'])) {
                    foreach ($field['args'] as &$arg) {
                        if (is_array($arg) && array_key_exists('desc', $arg)) {
                            $arg['description'] = $arg['desc'];
                        }
                    }
                }
            }
        }

        return $fields;
    }

    abstract public function attrs(GraphTypeAttrs &$attrs): void;

    abstract public function fields(GraphTypeFactory $types): array;

    public function inputFields(GraphTypeFactory $types, string $name = ''): array
    {
        return [];
    }

    public function fieldsMap()
    {
        return [];
    }

    /**
     * 构建Input类型
     * @param string $name
     * @return InputObjectType
     */
    public function buildInput(string $name = ''): InputObjectType
    {
        $fields = $this->buildFields();
        $inputName = $this->typeName . 'Input';

        $newFields = [];

        foreach ($fields as $key => &$value) {
            if (gettype($value) == 'array') {
                $type = $value['type'];
            } else {
                $type = $value;
            }

            // 避免循环input 在args里增加 skipInput => true
            if (in_array($key, ['createdTime', 'updatedTime', 'aiId']) || ($type instanceof ObjectType && isset($type->args["skipInput"]))) {
                continue;
            }

            $subType = $type;
            $typeWrappers = [];

            // 解出被包装的类型
            for ($i = 0; $i < 3; $i++) {
                if ($subType instanceof NonNull || $subType instanceof ListOfType) {
                    $typeWrappers[] = get_class($subType);
                    $subType = $subType->getWrappedType();
                    continue;
                }
                break;
            }

            // 如果子类型可以被重新打包 则重新打包
            if (method_exists($subType, 'buildInput')) {
                // 重新包装成Input
                $type = $this->typeFactory->input(get_class($subType));

                foreach ($typeWrappers as $wrapperClass) {
                    if ($wrapperClass == NonNull::class) {
                        $type = $this->typeFactory->nonNull($type);
                    }
                    if ($wrapperClass == ListOfType::class) {
                        $type = $this->typeFactory->listOf($type);
                    }
                }
            }

            $fields[$key]['type'] = $type;
            $newFields[$key] = $fields[$key];
        }

        $newFields = array_merge($newFields, $this->inputFields($this->typeFactory, $name));

        return new InputObjectType([
            'name' => $inputName,
            'fields' => $newFields,
        ]);
    }
}
