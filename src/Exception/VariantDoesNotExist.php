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

class VariantDoesNotExist extends Exception
{
    public function __construct(
        string $nativeMapperClass,
        string $relatedName,
        int|string|null $typeVal
    ) {
        $typeVal = $typeVal === null
            ? 'NULL'
            : "'" . (string) $typeVal . "'";

        $ms = "Variant relationship for value {$typeVal} "
            . "does not exist on {$nativeMapperClass}Related::\${$relatedName}.";

        parent::__construct($ms);
    }
}
