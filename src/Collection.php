<?php declare(strict_types=1);

namespace Compolomus\Collection;

use InvalidArgumentException;

class Collection
{
    public const ASC = 1;
    public const DESC = 0;

    private $generic;

    private $collection = [];

    public function __construct(string $class)
    {
        $this->generic = $class;
    }

    public function addOne(object $object): self
    {
        if (!($object instanceof $this->generic)) {
            throw new InvalidArgumentException('Object mast be instanceof ' . $this->generic . ' class, ' . get_class($object) . ' given');
        }
        $this->collection[] = $object;

        return $this;
    }

    public function addAll(array $objects): self
    {
        array_map([$this, 'addOne'], $objects);

        return $this;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function immutable(): self
    {
        return clone $this;
    }

    private function cmp(string $key, int $order): callable
    {
        return static function(object $a, object $b) use ($key, $order) {
            return $order ? $a->$key <=> $b->$key : $b->$key <=> $a->$key;
        };
    }

    public function sort(string $key, int $order = Collection::ASC): self
    {
        uasort($this->collection, $this->cmp($key, $order));

        return $this;
    }

    public function limit(int $limit, int $offset = 0): self
    {
        $this->collection = array_slice($this->collection, $offset, $limit);

        return $this;
    }

    public function get(): array
    {
        return $this->collection;
    }
}
