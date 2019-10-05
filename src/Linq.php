<?php declare(strict_types=1);

namespace Compolomus\Collection;

use InvalidArgumentException;

class Linq
{
    private $collection;

    private const CONDITIONS_TYPES = [
        '=',
        '!=',
        '>',
        '<',
        '<=',
        '>=',
    ];

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function where(string $query): self
    {
        $pattern = '#(?P<field>.*)?(?P<condition>' . implode('|', self::CONDITIONS_TYPES) . ')(?P<value>.*)#is';

        preg_match($pattern, $query, $matches);

        $count = count($matches);

        if (!$count || $count < 4) {
            throw new InvalidArgumentException('Not matches ' . '<pre>' . print_r($matches, true) . '</pre>');
        }
        $args = array_map('trim', $matches);

        $array = [
            '=' => '===',
            '!=' => '!==',
        ];

        return $this->prepare($args['field'],
            (in_array($args['condition'], $array, true) ? $array[$args['condition']] : $args['condition']),
            $args['value']);
    }
    
    private function condition($valFirst, string $condition, $valSecond): bool
    {
        switch ($condition) {
            case '=':
                return $valFirst === $valSecond;
            case '!=':
                return $valFirst !== $valSecond;
            case '>':
                return $valFirst > $valSecond;
            case '<':
                return $valFirst < $valSecond;
            case '>=':
                return $valFirst >= $valSecond;
            case '<=':
                return $valFirst <= $valSecond;
            default:
                throw new InvalidArgumentException('Condition not set');
        }
    }

    private function prepare(string $field, string $condition, $value): self
    {
        $new = new Collection($this->collection->getGeneric());

        foreach ($this->collection->get() as $val) {
            !$this->condition($val->$field, $condition, $value) ?: $new->addOne($val);
        }

        $this->collection = $new;

        return $this;
    }


    public function get(): array
    {
        return $this->collection->get();
    }
}
