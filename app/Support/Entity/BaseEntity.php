<?php

declare(strict_types=1);

namespace App\Support\Entity;

use Hyperf\Contract\ValidatorInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Jsonable;
use Hyperf\Utils\Str;
use Hyperf\Validation\Contract\ValidatorFactoryInterface as ValidationFactory;
use JsonSerializable;

class BaseEntity implements Jsonable, JsonSerializable
{
    private array $data = [];

    public function __construct($data = [])
    {
        $this->fillData($data);
    }

    public function fillData($data)
    {
        foreach ($data as $key => $value) {
            $this->data[Str::camel($key)] = is_string($data[$key]) ? trim($data[$key]) : $data[$key];
        }
    }


    public function __set($name, $value)
    {
        $this->data[$name] = is_string($value) ? trim($value) : $value;
    }

    public function __isset($name)
    {
        if (method_exists($this, "get{$name}")) {
            return true;
        }
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name] === null ? false : true;
        }

        return isset($this->{$name});
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function __toString(): string
    {
        return json_encode($this->getData(), JSON_UNESCAPED_UNICODE);
    }

    public function &__get($name)
    {
        if (method_exists($this, "get{$name}")) {
            return $this->{"get{$name}"}();
        }
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        $result = null;

        return $result;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getData();
    }

    /**
     * Get the validator instance for the request.
     */
    public function getValidator(): ValidatorInterface
    {
        return Context::getOrSet($this->getContextValidatorKey(), function () {
            $factory = ApplicationContext::getContainer()->get(ValidationFactory::class);

            return $this->createDefaultValidator($factory);
        });
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }

    public function rules()
    {
        return [];
    }

    /**
     * Get context validator key.
     */
    protected function getContextValidatorKey(): string
    {
        return sprintf('%s:%s', get_called_class(), ValidatorInterface::class);
    }

    /**
     * Create the default validator instance.
     * @param ValidationFactory $factory
     * @return ValidatorInterface
     */
    protected function createDefaultValidator(ValidationFactory $factory): ValidatorInterface
    {
        return $factory->make(
            $this->getData(),
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );
    }
}
