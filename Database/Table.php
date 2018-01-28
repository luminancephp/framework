<?php
/**
 * @file Luminance/Database/Table.php
 * @namespace Luminance\Database
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Database;
use Luminance\Configuration\Loader;

/**
 * This is the core database table logic, where tables will be loaded
 * and configuration will be read from. All database tables you make
 * you can extend this class, call asObjects($array_of_key_value_pairs)
 * then if you call save() it will save the data to database.
 *
 * Note: The local objects must match table names exactly, otherwise
 * this functionality will complain, likewise will the SQL server.
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Table
{
    /**
     * @var array
     */
    private $database_config = array();

    /**
     * @var array
     */
    private $key_value_pairs = array();

    /**
     * @var resource|null
     */
    private $connection;

    /**
     * @var string
     */
    private $query;

    /**
     * @var bool
     */
    private $has_executed = false;

    /**
     * @var string
     */
    public $table_name = "";

    /**
     * Gets the configuration file and opens the PDO connection
     */
    protected function createConnectionToDatabase()
    {
        $configuration_info = new Loader("database");
        $this->database_config = $configuration_info->config;
        $pdo = null;
        if($this->database_config["DB_ENABLED"])
        {
            try {
                $pdo = new \PDO($this->database_config["DB_DRIVER"].':host='.$this->database_config["DB_HOST"].';dbname='.$this->database_config["DB_NAME"], $this->database_config["DB_USER"], $this->database_config["DB_PASS"]);
            }
            catch (\PDOException $e)
            {
                error_log($e->getMessage());
                exit("<h1>Error</h1><p>There was an error connecting to the database</p>");
            }
            $this->connection = $pdo;
        }
        else
        {
            error_log('Notice: Database is disabled');
        }
    }

    /**
     * Runs the specified query
     *
     * @param array $replacements
     *
     * @return \PDO
     */
    public function execute(array $replacements = array())
    {
        $query = new $this->connection->prepare($this->query);
        $query->execute($replacements);
        $this->has_executed = true;
        return $query; /** @var \PDO */
    }

    /**
     * Creates object keys on the current object
     *
     * @param array $keys
     */
    public function asObjects(array $keys = array())
    {
        if(!empty($keys))
        {
            $this->key_value_pairs = $keys;
            foreach($keys as $k => $v)
            {
                $this->$k = $v;
            }
        }
    }

    /**
     * Set table name
     *
     * @param string $table_name
     */
    public function setTableName(string $table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * Save the local objects and changes to the database
     */
    public function save()
    {
        if(!empty($this->key_value_pairs))
        {
            $sql_string = "UPDATE " . $this->table_name . " SET ";
            $replacements = array();
            foreach($this->key_value_pairs as $key=>$value)
            {
                $sql_string .= $this->$key . ' = ? ';
                $replacements[] = $value;
            }
            $this->setQueryString($sql_string);
            $this->execute($replacements);
            return $this->has_executed;
        }
    }

    /**
     * Sets a query string
     *
     * @param string $query_string
     */
    public function setQueryString(string $query_string = "")
    {
        $this->query = $query_string;
    }

    /**
     * Creates the database connection on the fly, and deconstructs it when needed
     */
    public function __construct()
    {
        $this->createConnectionToDatabase();
    }

    /**
     * Cleanup connection details
     */
    public function __destruct()
    {
        $this->connection = null;
        $this->database_config = array();
    }

    /**
     * Query Builder wrapper
     */
    public static function DB()
    {
        return new QueryBuilder();
    }
}