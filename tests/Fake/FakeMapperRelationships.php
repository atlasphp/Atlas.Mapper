<?php
namespace Atlas\Mapper\Fake;

class FakeMapperRelationships extends MapperRelationships
{
    protected function getNativeMapperClass()
    {
        return 'Atlas\Testing\DataSource\EmployeeMapper';
    }
}
