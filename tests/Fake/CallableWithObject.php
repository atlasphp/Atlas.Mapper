<?php
namespace Atlas\Mapper\Fake;

class CallableWithObject
{
    public function replies($query)
    {
        $query->loadRelated(['author']);
    }
}
