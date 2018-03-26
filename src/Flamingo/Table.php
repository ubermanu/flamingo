<?php

namespace Flamingo;

/**
 * Class Table
 * @package Flamingo
 */
class Table extends \ArrayIterator implements \Traversable
{
    /**
     * Table constructor.
     * @param array $columns
     * @param array $records
     */
    public function __construct(array $columns = [], array $records = [])
    {
        if (count($columns) * count($records) > 0) {

            // Add keys to $records
            foreach ($records as &$record) {
                $record = array_combine($columns, $record);
            }

            parent::__construct($records);
        }
    }

    /**
     * Remove null and empty values
     */
    public function sanitize()
    {
        $copy = $this->getArrayCopy();

        $cleanArray = array_filter($copy, function ($record) {
            return (is_array($record) && count($record));
        });

        parent::__construct($cleanArray);
    }

    /**
     * Copy an array into object storage
     * @param array $array
     */
    public function copy($array)
    {
        parent::__construct($array);
    }
}