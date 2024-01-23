<?php

namespace ThirdRailPackages\ScheduleValidity;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 * @implements \IteratorAggregate<TKey, TValue>
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /** @var array<TKey, TValue> */
    protected $items = [];

    /**
     * @param array<TKey, TValue> $items
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
     * @return Collection<TMakeKey, TMakeValue>
     */
    public static function make($items): self
    {
        return new static($items);
    }

    /**
     *
     * @param TValue|mixed $item
     * @return Collection
     */
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param callable $function
     *
     * @return Collection
     */
    public function each(callable $function)
    {
        foreach ($this->items as $key => $item) {
            $function($item, $key);
        }

        return $this;
    }

    /**
     * @template TMapValue
     *
     * @param  callable(TValue): TMapValue $function
     * @return self<TKey, TMapValue>
     */
    public function map(callable $function)
    {
        return new self(array_map($function, $this->items));
    }

    /**
     * @param (callable(TValue): bool)|null $function
     * @return self
     */
    public function filter(callable $function = null): self
    {
        if ($function !== null) {
            return new self(array_filter($this->items, $function));
        }

        return new self(array_filter($this->items));
    }

    /**
     * @param callable(TValue): bool $function
     * @return self
     */
    public function reject(callable $function): self
    {
        return $this->filter(function ($item) use ($function) {
            return !$function($item);
        });
    }

    /**
     * Sort through each item with a callback.
     *
     * @param  (callable(TValue, TValue): int)|null|int  $callback
     * @return self
     */
    public function sort($callback = null)
    {
        $items = $this->items;

        $callback !== null && is_callable($callback)
            ? uasort($items, $callback)
            : asort($items, $callback ?? SORT_REGULAR);

        return new self($items);
    }


    /**
     * @return TValue|null
     */
    public function first()
    {
        foreach ($this->items as $item) {
            return $item;
        }

        return null;
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

    /**
     * Reset the keys on the underlying array.
     *
     * @return Collection<int, mixed>
     */
    public function values()
    {
        return new self(array_values($this->items));
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
     * @return \ArrayIterator<TKey, TValue>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @param TKey $offset
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param  TKey  $offset
     * @return TValue
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * @param TKey $offset
     * @param TValue    $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    /**
     * @param TKey $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }
}
