<?php
require __DIR__ . '/skeleton.php';

// - `class {$TYPE} extends _generated/{$TYPE}_` for everything
// - change Events return type (tricky)
// - remove `use {$TYPE}Fields` from Record
// - delete `{$TYPE}Fields.php`
// - read {$TYPE}Relationships into {$TYPE}Related, w/ attribs not methods (super tough)
//     - likely to take AST work
// - delete {$TYPE}Relationships

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

        $this->rewrite("{$typeDir}/{$type}Related.php", [
            '/use Atlas\\\\Mapper\\\\Define;/' => '$0' . PHP_EOL . $upgradeRelationships->imports(),
            '/\{[\r\n]+\}/m' => '{' . PHP_EOL . $upgradeRelationships->properties() . PHP_EOL . '}',
        ]);

        // rm Relationships.php
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
        ?string $extraName = null,
        ?string $extraValue = null,
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
            $extraName,
            $extraValue,
        );
        $this->rels[] = $rel;
        return $rel;
    }

    public function properties()
    {
        $str = '';
        foreach ($this->rels as $rel) {
            $str .= '    ' . $rel->property() . PHP_EOL;
        }
        return rtrim($str);
    }

    public function imports()
    {
        $str = [];

        foreach ($this->rels as $rel) {
            foreach ($rel->imports as $import) {
                $str[] = 'use ' . $import . ';';
            }
        }

        $str = array_unique($str);
        return implode(PHP_EOL, $str);
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
            [],
            'column',
            $referenceCol,
        );
    }

    protected function manyToMany(
        string $relatedName,
        string $foreignMapperClass,
        string $throughRelatedName = null,
        array $on = []
    ) : UpgradeRelationship
    {
        return $this->add(
            $relatedName,
            'Define\\ManyToMany',
            $foreignMapperClass,
            $on,
            'through',
            $throughRelatedName
        );
    }
}


class UpgradeRelationship
{
    public array $calls = [];

    public array $imports = [];

    public function __construct(
        public string $relatedName,
        public string $defineClass,
        public string $relatedType,
        public ?array $on = [],
        public ?string $extraName = null,
        public ?string $extraValue = null,
    ) {
        if ($relatedType !== 'mixed') {
            $this->imports[] = $relatedType;
        }
    }

    public function property()
    {
        $on = '';
        if (! empty($this->on)) {
            $on = $this->fixOn($this->on);
        }

        $extra = '';
        if ($this->extraName !== null) {
            $extra = $this->extraName . ': ' . var_export($this->extraValue, true);
        }

        $define = "#[{$this->defineClass}";
        if ($on || $extra) {
            $comma = $on && $extra ? ', ' : '';
            $define .= '(';
            $define .= $on . $comma . $extra;
            $define = rtrim($define, ', ');
            $define .= ')';
        }

        $define .= ']' . PHP_EOL;

        $short = $this->shortClass($this->relatedType);
        if (substr($short, -6) === 'Record') {
            $short = "?{$short}";
        }

        $called = '';
        foreach ($this->calls as $call) {
            list ($attr, $args) = $call;
            $called .= "    #[{$attr}";
            if (! empty($args)) {
                $called .= '(' . implode(', ', $args) . ')';
            }
            $called .= ']' . PHP_EOL;
        }

        $property = "    protected {$short} \${$this->relatedName};". PHP_EOL;
        return $define . $called . $property;
    }

    protected function fixOn(array $on)
    {
        if (empty($on)) {
            return '';
        }

        $on = var_export($on, true);
        $on = str_replace('array (' . PHP_EOL, '', $on);
        $on = str_replace(PHP_EOL . ')', '', $on);
        $on = str_replace('  ', '', $on);
        $on = str_replace(PHP_EOL, ' ', $on);
        $on = rtrim($on, ', '. PHP_EOL);
        return 'on: [' . $on . ']';
    }

    protected function shortClass(string $fqcn)
    {
        $parts = explode('\\', $fqcn);
        return end($parts);
    }

    public function type(
        string $typeVal,
        string $foreignMapperClass,
        array $on
    ) : self
    {
        $variantType = $foreignMapperClass . 'Record';
        $this->imports[] = $variantType;
        $short = $this->shortClass($variantType);
        $args = [
            var_export($typeVal, true),
            $short . '::CLASS',
            $this->fixOn($on),
        ];
        $this->calls[] = ['Define\\Variant', $args];
        return $this;
    }

    public function ignoreCase(bool $ignoreCase = null) : self
    {
        $args = ($ignoreCase === null) ? [] : [var_export($ignoreCase, true)];
        $this->calls[] = ['Define\\IgnoreCase', $args];
        return $this;
    }

    public function where(string $condition, ...$bindInline) : self
    {
        $args = [];
        foreach (func_get_args() as $arg) {
            $args[] = var_export($arg, true);
        }
        $this->calls[] = ['Define\\Where', $args];
        return $this;
    }

    public function onDeleteCascade() : self
    {
        $this->calls[] = ['Define\\OnDelete\\Cascade', []];
        return $this;
    }

    public function onDeleteInitDeleted() : self
    {
        $this->calls[] = ['Define\\OnDelete\\InitDeleted', []];
        return $this;
    }

    public function onDeleteSetDelete() : self
    {
        $this->calls[] = ['Define\\OnDelete\\SetDelete', []];
        return $this;
    }

    public function onDeleteSetNull() : self
    {
        $this->calls[] = ['Define\\OnDelete\\SetNull', []];
        return $this;
    }
}

$upgrade = new Upgrade();
$upgrade();
