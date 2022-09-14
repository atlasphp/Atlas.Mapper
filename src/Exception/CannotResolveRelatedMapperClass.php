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

class CannotResolveRelatedMapperClass extends Exception
{
    public function __construct(
        string $nativeMapperClass,
        string $relatedName,
        string $relatedSpec,
        string $relatedMapperClass
    ) {
        $ms = "{$nativeMapperClass}Related::\${$relatedName} typhinted "
            . "as {$relatedSpec} resolves to Mapper class {$relatedMapperClass}, "
            . "which does not exist or is not a Mapper.";
        parent::__construct($ms);
    }
}
