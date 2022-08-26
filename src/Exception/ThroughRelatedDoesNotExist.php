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

class ThroughRelatedDoesNotExist extends Exception
{
    public function __construct(
        string $nativeMapperClass,
        string $nativeName,
        string $throughName,
        string $throughRelatedClass,
        string $targetMapperClass
    ) {
        // DataSource\Thread\ThreadRelated::$tags goes through
        // DataSource\Tagging\TaggingRecordSet $taggings,
        // but DataSource\Tagging\TaggingRelated does not define
        // a ManyToOne property relating to a TagRecord.

        $ms = "ManyToMany {$nativeMapperClass}Related::\${$nativeName} "
            . "goes through ManyToOne \${$throughName}, "
            . "but {$throughRelatedClass} does not define "
            . "a ManyToOne property typehinted to {$targetMapperClass}Record.";

        parent::__construct($ms);
    }
}
