<?php

class BuildObject {

    /**
     * @var ClassObject
     */
    private $class;

    /**
     * @var PropertyObject[]
     */
    private $property = [];

    /**
     * @return ClassObject
     */
    public function getClass(): ClassObject {
        return $this->class;
    }

    /**
     * @return PropertyObject[]
     */
    public function getProperty(): array {
        return $this->property;
    }

    /**
     * @param ClassObject $class
     */
    public function setClass(ClassObject $class): void {
        $this->class = $class;
    }

    /**
     * @param PropertyObject[] $property
     */
    public function setProperty(array $property): void {
        $this->property = $property;
    }

    /**
     * @param PropertyObject $property
     */
    public function addProperty(PropertyObject $property): void {
        $this->property[] = $property;
    }




}