<?php declare(strict_types=1);

namespace Compolomus\Collection;

use PHPUnit\Framework\TestCase;
use stdClass;
use Exception;

class LinqTest extends TestCase
{
    public function test__construct(): void
    {
        try {
            $collection = new Collection(stdClass::class);
            $obj = new Linq($collection);
            $this->assertIsObject($obj);
            $this->assertInstanceOf(Linq::class, $obj);
        } catch (Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testGet(): void
    {
        $collection = new Collection(stdClass::class);
        $add = new stdClass();
        $add->test = 42;
        $collection->addOne($add);
        $obj = new Linq($collection);
        $this->assertIsArray($obj->get());
        $this->assertEquals(count($obj->get()), 1);
    }

    public function testWhere(): void
    {
        $collection = new Collection(stdClass::class);
        for ($i = 0; $i <= 42; $i++) {
            $add = new stdClass();
            $add->test = $i;
            $collection->addOne($add);
        }
        $obj = new Linq($collection);
        $linq = $obj->where('test > 33');
        $this->assertEquals(count($linq->get()), 9);
    }
}
