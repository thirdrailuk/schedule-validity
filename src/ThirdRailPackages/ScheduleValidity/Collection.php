<?php

namespace ThirdRailPackages\ScheduleValidity;

class Collection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * @param array $items
     *
     * @return static
     */
    public static function make($items = []): self
    {
        return new static($items); // @phpstan-ignore-line
    }

    /**
     * @param callable $function
     *
     * @return $this
     */
    public function each(callable $function)
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($this->items as $key => $item) {
            $function($item, $key);
        }

        return $this;
    }

    /**
     * @param callable $function
     *
     * @return static
     */
    public function map(callable $function): self
    {
        return new static(array_map($function, $this->items)); // @phpstan-ignore-line
    }

    /**
     * @param callable $function
     *
     * @return static
     */
    public function filter(callable $function): self
    {
        /** @psalm-suppress MixedArgumentTypeCoercion */
        return new static(array_filter($this->items, $function)); // @phpstan-ignore-line
    }

    /**
     * @param callable $function
     *
     * @return static
     */
    public function reject(callable $function): self
    {
        /** @psalm-suppress MissingClosureParamType */
        return $this->filter(function ($item) use ($function) {
            return !$function($item);
        });
    }

    /**
     * @return mixed
     */
    public function first()
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($this->items as $item) {
            return $item;
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return iterator_to_array($this);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        /** @psalm-suppress MixedArgument */
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        /** @psalm-suppress MixedArrayOffset */
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            /** @psalm-suppress MixedArrayOffset */
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        /** @psalm-suppress MixedArrayOffset */
        unset($this->items[$offset]);
    }
}
