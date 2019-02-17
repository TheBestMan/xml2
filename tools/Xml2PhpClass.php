<?php

class Xml2PhpClass {
    /**
     * @var BuildObject
     */
    private $class;

    /**
     * @var string
     */
    private $pathDestination;

    public function __construct() {
        $this->class = new BuildObject();
        $this->pathDestination = './';
    }

    public function fromFile(string $file) {
        if (!file_exists($file)) {
            throw new Exception('Файл не найден');
        }

        $this->fromStr(file_get_contents($file));
    }

    public function fromStr(string $str) {
        $xr = new XMLReader();
        $xr->XML($str);

        while ($xr->read()) {
            if ($xr->nodeType === XMLReader::ELEMENT) {
                switch ($xr->localName) {
                    case 'name':
                        $class = new ClassObject();
                        $class->setName($xr->readString());
                        if (!empty($xr->getAttribute('description'))) {
                            $class->setDescription($xr->getAttribute('description'));
                        }
                        $codegen = ($xr->getAttribute('codegen') == 'true') ? true : false;
                        $class->setCodegen($codegen);
                        $this->class->setClass($class);
                        break;
                    case 'property':
                        $property = new PropertyObject();
                        $property->setName($xr->readString());
                        if (!empty($xr->getAttribute('type'))) {
                            $property->setType($xr->getAttribute('type'));
                        }
                        if (!empty($xr->getAttribute('description'))) {
                            $property->setDescription($xr->getAttribute('description'));
                        }
                        $codegen = ($xr->getAttribute('codegen') == 'true') ? true : false;
                        $property->setCodegen($codegen);
                        $this->class->addProperty($property);
                        break;

                }
            }
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute(): bool {
//        var_dump($this->class);
        if (empty($this->class->getClass()->getName())) {
            return false;
        }
        $this->createPath();
        if (!$this->createFile()) {
            return false;
        }

        return true;
    }

    private function createPath(): void {
        $path = explode('_', $this->class->getClass()->getName());
        array_pop($path);
        $path = implode('/', $path);
        if (!is_dir($this->pathDestination . $path)
            && !mkdir($this->pathDestination . $path, 0755, true)
            && !is_dir($this->pathDestination . $path)
        ) {
            throw new Exception('Не удалось создать директории...');
        }
        $this->pathDestination .= $path;
    }

    private function createFile(): bool {
        $path = $this->class->getClass()->getName();
        $className = explode('_', $path);
        $className = end($className) . '.php';
        $content = "<?php\n\n";
        $content .= "/**\n";
        $content .= " * {$this->class->getClass()->getDescription()}\n";
        $content .= " * @xmlns urn:ru:ilb:meta:" . str_replace('_', ':', strtolower($path)) . "\n";
        $content .= " * @xmlname Balance\n";
        $content .= " * @codegenold true\n";
        $content .= " */\n";
        $content .= "class {$this->class->getClass()->getName()} implements Adaptor_XML {\n";

        // свойства
        foreach ($this->class->getProperty() as $property) {
            $content .= <<<EOF
    /**
     * {$property->getDescription()}
     * @var {$property->getType()}
     */
    private \${$property->getName()};


EOF;
        }

        // методы
        // getter
        foreach ($this->class->getProperty() as $property) {
            $method = ucfirst($property->getName());
            $content .= <<<EOF
    /**
     * @return {$property->getType()}
     */
    public function get{$method}() {
        return \$this->{$property->getName()};
    }


EOF;
        }

        // setter
        foreach ($this->class->getProperty() as $property) {
            $method = ucfirst($property->getName());
            $content .= <<<EOF
    /**
     * @param {$property->getType()} {$property->getName()}
     */
    public function set{$method}(\${$property->getName()}) {
        \$this->{$property->getName()} = \${$property->getName()};
    }


EOF;
        }

        // от интерфейса
        $content .= <<<EOF
    /**
     * чтение из XMLReader
     * @codegenold true
     */
    public function fromXmlReader(XMLReader &\$xr) {

    }

    /**
     * чтение из xml в строку
     * @codegen true
     */
    public function fromXmlStr(\$source) {

    }

    /**
     * вывод в XMLWriter
     * @codegenold true
     */
    public function toXmlWriter(XMLWriter &\$xw, \$xmlname = NULL, \$xmlns = NULL, \$mode = Adaptor_XML::ELEMENT) {

    }

    /**
     * вывод xml в строку
     * @codegenold true
     */
    public function toXmlStr(\$xmlns = NULL, \$xmlname = NULL) {

    }

    /**
     * проверка по схеме
     * @codegenold true
     */
    public function validate(\$schemaPath) { 

    }
}

EOF;


        file_put_contents($this->pathDestination . '/' . $className, $content);

        return (file_exists($this->pathDestination . '/' . $className) ? true : false);
    }
}