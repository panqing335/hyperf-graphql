<?php


namespace App\Support\GraphQL\Definition;


use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;
use GraphQL\Type\Definition\EnumType as GraphQLEnumType;

abstract class EnumType extends GraphQLEnumType
{
    public function __construct($config, GraphTypeFactory $typeFactory)
    {
        $attrs = new GraphTypeAttrs();
        $this->attrs($attrs);
        parent::__construct([
            'name' => $attrs->name,
            'description' => $attrs->desc,
            'values' => $this->values($typeFactory),
        ]);
    }

    abstract public function attrs(GraphTypeAttrs &$attrs): void;

    abstract public function values(GraphTypeFactory $types): array;

}