<?php
namespace Atlas\Mapper\DataSource\Employee;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class EmployeeRelated extends Related
{
    protected mixed $id;
}
