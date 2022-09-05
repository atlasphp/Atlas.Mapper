<?php
// require __DIR__ . '/skeleton.php';

// - `class {$TYPE} extends _generated/{$TYPE}_` for everything
// - change Events return type (tricky)
// - remove `use {$TYPE}Fields` from Record
// - delete `{$TYPE}Fields.php`
// - read {$TYPE}Relationships into {$TYPE}Related, w/ attribs not methods (super tough)
//     - likely to take AST work
// - delete {$TYPE}Relationships

$upgrade = new Upgrade();
$upgrade();


class Upgrade
{
    public function __invoke()
    {
        $baseDirs = [
            __DIR__ . '/CompositeDataSource',
            __DIR__ . '/DataSource',
        ];

        $typeDirs = [];

        foreach ($baseDirs as $baseDir) {
            $typeDirs = array_merge($typeDirs, glob("{$baseDir}/*"));
        }

        $this->upgradeTypes($typeDirs);
    }

    public function upgradeTypes($typeDirs)
    {
        foreach ($typeDirs as $typeDir) {
            $type = basename($typeDir);
            $this->upgradeType($typeDir, $type);
            $this->upgradeTypeEvents($typeDir, $type);
            $this->upgradeTypeFields($typeDir, $type);
            $this->upgradeTypeRecord($typeDir, $type);
            $this->upgradeTypeRecordSet($typeDir, $type);
            $this->upgradeTypeRelationships($typeDir, $type);
            $this->upgradeTypeRow($typeDir, $type);
            $this->upgradeTypeSelect($typeDir, $type);
            $this->upgradeTypeTable($typeDir, $type);
            $this->upgradeTypeTableEvents($typeDir, $type);
            $this->upgradeTypeTableSelect($typeDir, $type);
        }
    }

    public function upgradeType($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}.php", [
            '/^use Atlas\\\\Mapper\\\\Mapper;[\r\n]*/m' => "",
            '/^ \* @method .*[\r\n]*/m' => "",
            '/\/\*\*\s*\*\/[\r\n]*/m' => "",
            '/ extends Mapper(.*)/' => " extends _generated\\{$type}$1_",
        ]);
    }

    public function upgradeTypeEvents($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}Events.php", [
            '/^use Atlas\\\\Mapper\\\\MapperEvents;[\r\n]*/m' => "",
            '/ extends MapperEvents(.*)/' => " extends _generated\\{$type}Events$1_",
        ]);
    }

    public function upgradeTypeFields($typeDir, $type)
    {
        // unlink {$typeDir}/{$type}Fields.php
    }

    public function upgradeTypeRecord($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}Record.php", [
            '/^use Atlas\\\\Mapper\\\\Record;[\r\n]*/m' => "",
            '/^ \* @method .*[\r\n]*/m' => "",
            '/\/\*\*\s*\*\/[\r\n]*/m' => "",
            '/ extends Record(.*)/' => " extends _generated\\{$type}Record$1_",
            "/^    use {$type}Fields;[\r\n]*/m" => "",
        ]);
    }

    public function upgradeTypeRecordSet($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}RecordSet.php", [
            '/^use Atlas\\\\Mapper\\\\RecordSet;[\r\n]*/m' => "",
            '/^ \* @method .*[\r\n]*/m' => "",
            '/\/\*\*\s*\*\/[\r\n]*/m' => "",
            '/ extends RecordSet(.*)/' => " extends _generated\\{$type}RecordSet$1_",
        ]);
    }

    public function upgradeTypeRelationships($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}Relationships.php", [
            '/ extends MapperRelationships/' => " extends \UpgradeRelationships",
            '/    protected function define/' => '    public function define',
        ]);

        require "{$typeDir}/{$type}Relationships.php";
        $classes = get_declared_classes();
        $class = end($classes);
        $upgradeRelationships = new $class();
        $upgradeRelationships->define();
        var_dump($upgradeRelationships);
    }

    public function upgradeTypeRow($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}Row.php", [
            '/^use Atlas\\\\Table\\\\Row;[\r\n]*/m' => "",
            '/^ \* @property .*[\r\n]*/m' => "",
            '/\/\*\*\s*\*\/[\r\n]*/m' => "",
            '/ extends Row(.*)/' => " extends _generated\\{$type}Row$1_",
            '/^    protected \$cols = \[.*?    \];[\r\n]*/ms' => '',
        ]);
    }

    public function upgradeTypeSelect($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}Select.php", [
            '/^use Atlas\\\\Mapper\\\\MapperSelect;[\r\n]*/m' => "",
            '/^ \* @method .*[\r\n]*/m' => "",
            '/\/\*\*\s*\*\/[\r\n]*/m' => "",
            '/ extends MapperSelect(.*)/' => " extends _generated\\{$type}Select$1_",
        ]);
    }

    public function upgradeTypeTable($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}Table.php", [
            '/^use Atlas\\\\Table\\\\Table;[\r\n]*/m' => "",
            '/^ \* @method .*[\r\n]*/m' => "",
            '/\/\*\*\s*\*\/[\r\n]*/m' => "",
            '/ extends Table(.*)/' => " extends _generated\\{$type}Table$1_",
            '/^    const DRIVER = .*[\r\n]*/m' => '',
            '/^    const NAME = .*[\r\n]*/m' => '',
            '/^    const COLUMNS = \[.*?    \];[\r\n]*/ms' => '',
            '/^    const COLUMN_NAMES = \[.*?    \];[\r\n]*/ms' => '',
            '/^    const COLUMN_DEFAULTS = \[.*?    \];[\r\n]*/ms' => '',
            '/^    const PRIMARY_KEY = \[.*?    \];[\r\n]*/ms' => '',
            '/^    const AUTOINC_COLUMN = .*[\r\n]*/m' => '',
            '/^    const AUTOINC_SEQUENCE = .*[\r\n]*/m' => '',
        ]);
    }

    public function upgradeTypeTableEvents($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}TableEvents.php", [
            '/ extends TableEvents(.*)/' => " extends _generated\\{$type}TableEvents$1_",
        ]);
    }

    public function upgradeTypeTableSelect($typeDir, $type)
    {
        $this->rewrite("{$typeDir}/{$type}TableSelect.php", [
            '/^use Atlas\\\\Table\\\\TableSelect;[\r\n]*/m' => "",
            '/^ \* @method .*[\r\n]*/m' => "",
            '/\/\*\*\s*\*\/[\r\n]*/m' => "",
            '/ extends TableSelect(.*)/' => " extends _generated\\{$type}TableSelect$1_",
        ]);
    }

    public function rewrite(string $file, array $findReplace)
    {
        $code = file_get_contents($file);

        foreach ($findReplace as $find => $replace) {
            $code = preg_replace($find, $replace, $code);
        }

        file_put_contents($file, $code);
        // echo $code . PHP_EOL;
    }
}

class UpgradeRelationships
{
    public $rels = [];

    protected function add(
        string $relatedName,
        string $defineClass,
        string $foreignMapperClass,
        ?array $on = [],
        ?string $extra = null,
    ) : UpgradeRelationship
    {
        switch (true) {
            case strpos($defineClass, 'ToOneVariant') !== false:
                $relatedType = 'mixed';
                break;

            case strpos($defineClass, 'ToOne') !== false:
                $relatedType = $foreignMapperClass . 'Record';
                break;

            default:
                $relatedType = $foreignMapperClass . 'RecordSet';
                break;
        }

        $rel = new UpgradeRelationship(
            $relatedName,
            $defineClass,
            $relatedType,
            $on,
            $extra
        );
        $this->rels[] = $rel;
        return $rel;
    }

    protected function oneToOne(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : UpgradeRelationship
    {
        return $this->add(
            $relatedName,
            'Define\\OneToOne',
            $foreignMapperClass,
            $on
        );
    }

    protected function oneToOneBidi(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : UpgradeRelationship
    {
        return $this->add(
            $relatedName,
            'Define\\OneToOneBidi',
            $foreignMapperClass,
            $on
        );
    }

    protected function oneToMany(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : UpgradeRelationship
    {
        return $this->add(
            $relatedName,
            'Define\\OneToMany',
            $foreignMapperClass,
            $on
        );
    }

    protected function manyToOne(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : UpgradeRelationship
    {
        return $this->add(
            $relatedName,
            'Define\\ManyToOne',
            $foreignMapperClass,
            $on
        );
    }

    protected function manyToOneVariant(
        string $relatedName,
        string $referenceCol
    ) : UpgradeRelationship
    {
        return $this->add(
            $relatedName,
            'Define\\ManyToOneVariant',
            'mixed',
            null,
            $referenceCol,
        );
    }

    protected function manyToMany(
        string $relatedName,
        string $foreignMapperClass,
        string $throughRelatedName,
        array $on = []
    ) : UpgradeRelationship
    {
        return $this->add(
            $relatedName,
            'Define\\ManyToMany',
            $foreignMapperClass,
            $on,
            $throughRelatedName
        );
    }
}

class UpgradeRelationship
{
    public array $calls = [];

    public function __construct(
        public string $relatedName,
        public string $defineClass,
        public string $relatedType,
        public ?array $on = [],
        public ?string $extra = null,
    ) {
    }

    public function __call(string $func, array $args)
    {
        $this->calls[] = [$func, $args];
        return $this;
    }
}
