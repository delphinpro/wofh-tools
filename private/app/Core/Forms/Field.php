<?php

namespace WofhTools\Core\Forms;


/**
 * Class Field
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2015—2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core\Forms
 */
class Field
{
    /* @var string */
    private $name;
    /* @var string|null */
    private $value;
    /* @var bool */
    private $valid;
    /* @var string|null */
    private $message;
    /* @var callable[] */
    private $assertions;


    public function __construct(string $name)
    {
        $this->name = $name;
        $this->value = null;
        $this->valid = false;
        $this->message = null;

        $this->assertions = [];
    }


    public function assert(callable $assert): Field
    {
        $this->assertions[] = $assert;

        return $this;
    }


    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }


    public function validate(): void
    {
        try {

            foreach ($this->assertions as $assertion) {
                call_user_func($assertion, $this->value);
            }

            $this->valid = true;
            $this->message = '';

        } catch (\InvalidArgumentException $e) {

            $this->valid = false;
            $this->message = $e->getMessage();

        }
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getValue()
    {
        return $this->value;
    }


    public function setValue($value): void
    {
        $this->value = $value;
    }


    public function toArray(): array
    {
        return [
            'name'    => $this->name,
            'value'   => $this->value,
            'isValid' => $this->valid,
            'message' => $this->message,
        ];
    }
}
