<?php
/**
 * @file Luminance/Database/QueryBuilder.php
 * @namespace Luminance\Database
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Database;

/**
 * This provides a very simple query builder wrapper
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class QueryBuilder extends Table
{
    /**
     * @var string
     */
    public $sql_string = "";

    /**
     * @var array
     */
    private $replacements = array();

    /**
     * @var array|string
     */
    private $select_fields;

    /**
     * @var string
     */
    private $from_table = "";

    /**
     * @var array
     */
    private $where_fields = array();

    /**
     * @var string
     */
    private $strategy = "select";

    /**
     * Sets select field(s)
     *
     * @param string $single_field_or_fields
     *
     * @return QueryBuilder
     */
    public function select(string $single_field_or_fields)
    {
        $this->select_fields = $single_field_or_fields;
        return $this;
    }

    /**
     * Sets from table field
     *
     * @param string $table_name
     *
     * @return QueryBuilder
     */
    public function from(string $table_name)
    {
        $this->from_table = $table_name;
        return $this;
    }

    /**
     * Sets where conditions
     *
     * @param string $field
     * @param string $comparison
     * @param string $equals
     *
     * @return QueryBuilder
     */
    public function where(string $field, string $comparison = "=", string $equals)
    {
        $this->where_fields[] = array("field" => $field, "comparison" => $comparison, "equals" => $equals);
        return $this;
    }

    /**
     * Selects the table (same as "from", just a wrapper)
     * @note This is for insert
     *
     * @param string $table_name
     *
     * @return QueryBuilder
     */
    public function insert(string $table_name)
    {
        $this->from($table_name);
        $this->strategy = "insert";
        return $this;
    }

    /**
     * Set values from the table, only to be used on
     * inserts, checks strategy by default
     *
     * @param array $array_of_values
     *
     * @return QueryBuilder
     */
    public function values(array $array_of_values = array())
    {
        if($this->strategy === "insert")
        {
            foreach($array_of_values as $key => $value)
            {
                if(is_array($key))
                {
                    $this->values($value);
                }
                else
                {
                    $this->where_fields[] = $key;
                    $this->replacements[] = $value;
                }
            }
        }
        return $this;
    }

    /**
     * Execution function
     *
     * @param array $replacements
     *
     * @return \PDO
     */
    public function execute(array $replacements = array())
    {
        if($this->strategy === "select")
        {
            if(!empty($replacements))
            {
                $this->replacements = $replacements;
            }
            $sql_string = "SELECT ";
            if(is_array($this->select_fields))
            {
                foreach($this->select_fields as $field)
                {
                    $sql_string .= $field . " ";
                }
            }
            $sql_string .= "FROM " . $this->from_table . " ";
            if(empty($this->where_fields))
            {
                $this->setQueryString($sql_string);
                return parent::execute(array());
            }
            $replacements = array();
            foreach($this->where_fields as $field)
            {
                $sql_string .= $field["field"] . ' ' . $field['comparison'] . ' ? ';
                $replacements[] = $field["equals"];
            }
            $this->sql_string = $sql_string;
            return parent::execute($replacements);
        }
        else if($this->strategy === "insert")
        {
            /**
             * Insert data into the database
             */
            $sql_string = "INSERT INTO " . $this->from_table . "(";
            foreach($this->where_fields as $field)
            {
                $sql_string .= $field .",";
            }
            $sql_string .= rtrim($sql_string, ", ");
            $sql_string .= ") VALUES (";
            foreach($this->where_fields as $field)
            {
                $sql_string .= "?, ";
            }
            $sql_string .= rtrim($sql_string, ", ");
            $sql_string .= ");";
            $this->setQueryString($sql_string);
            return parent::execute(array());
        }
    }
}