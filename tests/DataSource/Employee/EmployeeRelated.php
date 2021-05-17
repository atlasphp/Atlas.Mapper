<?php
namespace Atlas\Mapper\DataSource\Employee;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class EmployeeRelated extends Related
{
    protected mixed $id;
}
