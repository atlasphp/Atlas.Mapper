<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Exception;

use Atlas\Mapper\Exception;

class ThroughPropertyDoesNotExist extends Exception
{
    public function __construct(
        string $nativeMapperClass,
        string $nativeName,
        string $throughName,
    ) {
        $ms = "ManyToMany {$nativeMapperClass}Related::\${$nativeName} "
            . "goes through ManyToOne \${$throughName}, "
            . "but \${$throughName} does not exist.";

        return new Exception($ms);
    }
}
