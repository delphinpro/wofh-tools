<?php

namespace WofhTools\Core\Forms;


/**
 * Class Form
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2015—2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core\Forms
 */
class Form
{

    /** @var bool */
    private $valid;
    /** @var \WofhTools\Core\Forms\Field[] */
    private $fields;
    /* @var \Slim\Http\Request */
    private $request;


    public function __construct(\Slim\Http\Request $request)
    {
        $this->valid = false;
        $this->request = $request;
    }


    /**
     * @param \WofhTools\Core\Forms\Field $field
     *
     * @return \WofhTools\Core\Forms\Field
     */
    public function addField(Field $field): Field
    {
        $name = $field->getName();
        $field->setValue($this->request->getParam($name));
        $this->fields[$name] = $field;

        return $field;
    }


    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }


    /**
     * Validate form
     */
    public function validate(): void
    {
        $this->valid = true;

        foreach ($this->fields as $field) {
            $field->validate();

            if (!$field->isValid()) {
                $this->valid = false;
            }
        }
    }


    /**
     * @param $name
     *
     * @return \WofhTools\Core\Forms\Field
     */
    public function getField($name): Field
    {
        if (!array_key_exists($name, $this->fields)) {
            throw new \InvalidArgumentException('Invalid argument');
        }

        return $this->fields[$name];
    }


    /**
     * @return array
     */
    public function toArray(): array
    {
        $fields = [];

        foreach ($this->fields as $key => $field) {
            $fields[$key] = $field->toArray();
        }

        return [
            'isValid' => $this->valid,
            'fields'  => $fields,
        ];
    }
}
