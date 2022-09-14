<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRelatedNameConflict;
use Atlas\Mapper\MapperLocator;
use Atlas\Testing\DataSource\Employee\Employee;
use Atlas\Testing\DataSource\Employee\EmployeeTable;
use Atlas\Testing\DataSourceFixture;
use ReflectionClass;

class ResolveRelatedTest extends \PHPUnit\Framework\TestCase
{
    protected int|string|array $fakeUnionType;

    public function testCannotResolveRelatedMapperClass()
    {
        $this->expectException(Exception\CannotResolveRelatedMapperClass::CLASS);
        $this->expectExceptionMessage('FakeNativeMapperRelated::$fakeRelated typhinted as NoSuchClass resolves to Mapper class NoSuchClass, which does not exist or is not a Mapper.');
        ResolveRelated::mapperClass('FakeNativeMapper', 'fakeRelated', 'NoSuchClass');
    }

    public function testCannotResolveRelatedMapperClass_namespaced()
    {
        $this->expectException(Exception\CannotResolveRelatedMapperClass::CLASS);
        $this->expectExceptionMessage('FakeNativeMapperRelated::$fakeRelated typhinted as Foo\\Bar\\BarRecord resolves to Mapper class Foo\\Bar\\Bar, which does not exist or is not a Mapper.');
        ResolveRelated::mapperClass('FakeNativeMapper', 'fakeRelated', 'Foo\\Bar\\BarRecord');
    }

    public function testUnionTypeResolvesAsMixed()
    {
        $type = (new ReflectionClass($this))
            ->getProperty('fakeUnionType')
            ->getType();

        $actual = ResolveRelated::mapperClass('FakeNativeMapper', 'fakeRelated', $type);
        $this->assertSame('mixed', $actual);
    }
}
