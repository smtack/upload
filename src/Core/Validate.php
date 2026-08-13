<?php

namespace Core;

class Validate
{
    private $passed;
    private $errors = [];
    private $db = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function check($source, $items = [])
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $field = $item;
                $value = trim($source[$field]);

                if ($rule == 'name') {
                    $name = $rule_value;
                }

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$name} is required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$name} must be a minimum of {$rule_value} characters.");
                            }
                        break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$name} must be a maximum of {$rule_value} characters.");
                            }
                        break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$name}");
                            }
                        break;
                        case 'unique':
                            if ($this->db->exists($rule_value, [$field => $value])) {
                                $this->addError("{$name} already exists.");
                            }
                        break;
                    }
                }
            }
        }

        if (empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passed()
    {
        return $this->passed;
    }
}
