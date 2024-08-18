<?php
namespace Cresenity\Laravel;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * CValidation library.
 */
class CValidation
{

    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return \Illuminate\Validation\Validator
     */
    public static function createValidator(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return self::factory($data, $rules, $messages, $customAttributes);
    }

    /**
     * @return \Illuminate\Validation\Rule
     */
    public static function createRule()
    {
        return new Rule();
    }

    /**
     * @return \Illuminate\Validation\Rule
     */
    public static function rule()
    {
        return static::createRule();
    }

    /**
     * Return validation factory or validator given by the parameter.
     *
     * @param array $data             | optional
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return CValidation_Factory|CValidation_Validator
     */
    public static function factory($data = null, array $rules = null, array $messages = [], array $customAttributes = [])
    {
        $factory = \c::container('validation');
        /** @var \Illuminate\Validation\Factory $factory */
        if ($data != null) {
            return $factory->make($data, $rules, $messages, $customAttributes);
        }

        return $factory;
    }
}

// End CValidation
