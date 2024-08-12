<?php
//@codingStandardsIgnoreStart

use Illuminate\Contracts\Support\Arrayable;

class carr extends \Illuminate\Support\Arr
{
    //@codingStandardsIgnoreEnd


    /**
     * Recursively merge two or more arrays. Values in an associative array
     * overwrite previous values with the same key. Values in an indexed array
     * are appended, but only when they do not already exist in the result.
     *
     * Note that this does not work the same as [array_merge_recursive](http://php.net/array_merge_recursive)!
     *
     *     $john = array('name' => 'john', 'children' => array('fred', 'paul', 'sally', 'jane'));
     *     $mary = array('name' => 'mary', 'children' => array('jane'));
     *
     *     // John and Mary are married, merge them together
     *     $john = carr::merge($john, $mary);
     *
     *     // The output of $john will now be:
     *     array('name' => 'mary', 'children' => array('fred', 'paul', 'sally', 'jane'))
     *
     * @param array $array1     initial array
     * @param array $array2,... array to merge
     *
     * @return array
     */
    public static function merge($array1, $array2)
    {
        if ($array1 instanceof Arrayable) {
            $array1 = $array1->toArray();
        }
        if ($array2 instanceof Arrayable) {
            $array2 = $array2->toArray();
        }
        if (\carr::isAssoc($array2)) {
            foreach ($array2 as $key => $value) {
                if (is_array($value)
                    && isset($array1[$key])
                    && is_array($array1[$key])
                ) {
                    $array1[$key] = \carr::merge($array1[$key], $value);
                } else {
                    $array1[$key] = $value;
                }
            }
        } else {
            foreach ($array2 as $value) {
                if (!in_array($value, $array1, true)) {
                    $array1[] = $value;
                }
            }
        }

        if (func_num_args() > 2) {
            foreach (array_slice(func_get_args(), 2) as $array2) {
                if (\carr::isAssoc($array2)) {
                    foreach ($array2 as $key => $value) {
                        if (is_array($value)
                            && isset($array1[$key])
                            && is_array($array1[$key])
                        ) {
                            $array1[$key] = \carr::merge($array1[$key], $value);
                        } else {
                            $array1[$key] = $value;
                        }
                    }
                } else {
                    foreach ($array2 as $value) {
                        if (!in_array($value, $array1, true)) {
                            $array1[] = $value;
                        }
                    }
                }
            }
        }

        return $array1;
    }


    /**
     * @param array $array
     * @return string
     */
    public static function hash(array $array)
    {
        array_multisort($array);

        return md5(json_encode($array));
    }
}
