<?php

namespace ThirdRailPackages\ScheduleValidity;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var array<TKey, TValue>
     */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param array<TKey, TValue> $items
     * @return void
     */
    final public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * @template TMakeKey of array-key
     * @template TMakeValue
     *
     * @param array<TMakeKey, TMakeValue> $items
     *
     * @return static<TMakeKey, TMakeValue>
     */
    final public static function make($items = [])
    {
        return new static($items);
    }

    /**
     * @param callable $function
     *
     * @return $this
     */
    public function each(callable $function)
    {
        foreach ($this->items as $key => $item) {
            $function($item, $key);
        }

        return $this;
    }

    /**
     * @param callable(TValue): mixed $function
     * @return static<TKey, TValue>
     */
    public function map(callable $function): self
    {
        return new static(array_map($function, $this->items));
    }

    /**
     * @param callable(TValue): bool $function
     * @return static
     */
    public function filter(callable $function): self
    {
        return new static(array_filter($this->items, $function));
    }

    /**
     * @param callable(TValue): bool $function
     *
     * @return static
     */
    public function reject(callable $function): self
    {
        return $this->filter(function ($item) use ($function) {
            return !$function($item);
        });
    }

    /**
     * @return TValue|null
     * @phpstan-return TValue|null
     */
    public function first()
    {
        $item = null;

        foreach ($this->items as $item) {
             break;
        }

        return $item;
    }

    /**
     * @param callable(TValue, TValue): int $callback
     *
     * @return static
     */
    public function sort($callback = null): self
    {
        $items = $this->items;

        $callback && is_callable($callback)
            ? uasort($items, $callback)
            : asort($items, SORT_REGULAR);

        return new static($items);
    }

    /**
     * @return static
     */
    public function values(): self
    {
        return new static(array_values($this->items));
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
     * @param TKey $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param TKey $offset
     *
     * @return TValue
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @param TKey|null $offset
     * @param TValue    $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param TKey $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }
}
