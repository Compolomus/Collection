<?php declare(strict_types=1);

namespace Compolomus\Collection;

use PHPUnit\Framework\TestCase;
use Exception;
use InvalidArgumentException;
use StdClass;
use SplFileObject;
use ArrayObject;

class CollectionTest extends TestCase
{

    public function test__construct(): void
    {
        try {
            $obj = new Collection(StdClass::class);
            $this->assertInternalType('object', $obj);
            $this->assertInstanceOf(Collection::class, $obj);
        } catch (Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testAddOne(): void
    {
        $obj = new Collection(StdClass::class);
        $new = new StdClass();
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

    public function testSort(): void
    {
        $array = range(1, 100);
        $collection = new Collection(StdClass::class);

        foreach ($array as $value) {
            $obj = new StdClass();
            $obj->var = $value;
            $collection->addOne($obj);
        }

        $dummy = $collection->immutable()->limit(50);
        $dummy->sort('var', Collection::DESC);
        $this->assertArraySubset(array_keys($dummy->get()), range(49, 0));
    }

    public function testGet(): void
    {
        $obj = new Collection(ArrayObject ::class);
        $dummy = [];
        $dummy[] = new ArrayObject(range('a', 'z'));
        $dummy[] = new ArrayObject(range(1, 100));
        $dummy[] = new ArrayObject(range(10, 1000, 10));
        $obj->addAll($dummy);
        $this->assertInternalType('array', $obj->get());
    }
}
