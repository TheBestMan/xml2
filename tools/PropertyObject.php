<?php

class PropertyObject {
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string | null
     */
    private $description;
    /**
     * @var bool
     */
    private $codegen;

    public function __construct() {
        $this->codegen = true;
        $this->type = 'string';
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void {
        $this->type = $type;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     * @param bool $codegen
     */
    public function setCodegen(bool $codegen): void {
        $this->codegen = $codegen;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isCodegen(): bool {
        return $this->codegen;
    }

}