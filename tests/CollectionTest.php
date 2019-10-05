<?php declare(strict_types=1);

namespace Compolomus\Collection;

use PHPUnit\Framework\TestCase;
use Exception;
use InvalidArgumentException;
use stdClass;
use SplFileObject;
use ArrayObject;

class CollectionTest extends TestCase
{

    public function test__construct(): void
    {
        try {
            $obj = new Collection(stdClass::class);
            $this->assertIsObject($obj);
            $this->assertInstanceOf(Collection::class, $obj);
        } catch (Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testAddOne(): void
    {
        $obj = new Collection(stdClass::class);
        $new = new stdClass();
        $new->dummy = 'dummy';
        $obj->addOne($new);
        $this->assertEquals($obj->get()[0], $new);
        $this->expectException(InvalidArgumentException::class);
        $new2 = new SplFileObject(__FILE__);
        $obj->addOne($new2);
    }

    public function testAddAll(): void
    {
        $obj = new Collection(ArrayObject ::class);
        $dummy = [];
        $dummy[] = new ArrayObject(range('a', 'z'));
        $dummy[] = new ArrayObject(range(1, 100));
        $dummy2 = [];
        $std1 = new StdClass();
        $std1->var = 'val';
        $std2 = new StdClass();
        $std1->foo = 'bar';
        $dummy2[] = $std1;
        $dummy2[] = $std2;
        $obj->addAll($dummy);
        $this->expectException(InvalidArgumentException::class);
        $obj->addAll($dummy2);
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
        $this->expectException(InvalidArgumentException::class);
        $collection = new Collection(stdClass::class);
        $add = new stdClass();
        $add->test = 42;
        $collection->addOne($add);
        $obj = new Linq($collection);
        $linq = $obj->where('test dummy 33');
    }

    public function testCount(): void
    {
        $obj = new Collection(ArrayObject ::class);
        $dummy = [];
        $dummy[] = new ArrayObject(range('a', 'z'));
        $dummy[] = new ArrayObject(range(1, 100));
        $dummy[] = new ArrayObject(range(10, 1000, 10));
        $obj->addAll($dummy);
        $this->assertEquals($obj->count(), 3);
    }

    public function testImmutable(): void
    {
        $obj = new Collection(ArrayObject ::class);
        $dummy = [];
        $dummy[] = new ArrayObject(range('a', 'z'));
        $dummy[] = new ArrayObject(range(1, 100));
        $dummy[] = new ArrayObject(range(10, 1000, 10));
        $obj->addAll($dummy);
        $this->assertEquals($obj->count(), 3);
        $immutable = $obj->immutable()->limit(2);
        $this->assertEquals($immutable->count(), 2);
        $this->assertNotEquals($obj, $immutable);

    }

    public function testLimit(): void
    {
        $obj = new Collection(ArrayObject ::class);
        $dummy = [];
        $dummy[] = new ArrayObject(range('a', 'z'));
        $dummy[] = new ArrayObject(range(1, 100));
        $dummy[] = new ArrayObject(range(10, 1000, 10));
        $obj->addAll($dummy);
        $this->assertEquals($obj->limit(2)->count(), 2);
    }

    public function testGetGeneric(): void
    {
        $obj = new Collection(stdClass::class);
        $this->assertEquals($obj->getGeneric(), stdClass::class);
    }

    public function testSort(): void
    {
        $array = range(1, 100);
        $collection = new Collection(stdClass::class);

        foreach ($array as $value) {
            $obj = new StdClass();
            $obj->var = $value;
            $collection->addOne($obj);
        }

        $dummy = $collection->immutable()->limit(50);
        $dummy->sort('var', Collection::DESC);
        $actualArray = range(49, 0);
        foreach ($dummy->get() as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
        }
    }

    public function testGet(): void
    {
        $obj = new Collection(ArrayObject ::class);
        $dummy = [];
        $dummy[] = new ArrayObject(range('a', 'z'));
        $dummy[] = new ArrayObject(range(1, 100));
        $dummy[] = new ArrayObject(range(10, 1000, 10));
        $obj->addAll($dummy);
        $this->assertIsArray($obj->get());
    }
}
