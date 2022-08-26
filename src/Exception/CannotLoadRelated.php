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

class CannotLoadRelated extends Exception
{
    public function __construct(
        string $relatedName,
        string $targetClass,
        string $nativeMapperClass,
    ) {
        $ms = "Cannot load '{$relatedName}' for {$targetClass} "
            . "because there is no {$nativeMapperClass}Related property "
            . "defined for it.";

        parent::__construct($ms);
    }
}
