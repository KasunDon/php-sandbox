<?php
namespace App\Models;

/**
 * Class for Mongo Storage
 */
class Storage {

    /**
     * Client
     * 
     * @var MongoClient 
     */
    private $client;

    /**
     * Database Name
     * 
     * @var strin 
     */
    private $dbName;

    /**
     * Hostname
     * 
     * @var string 
     */
    private $hostname;

    /**
     * Collection
     * 
     * @var string
     */
    private $collection;

    /**
     * Port
     * 
     * @var int 
     */
    private $port;

    /**
     * Database (MongoDB)
     * 
     * @var MongoDB
     */
    private $db;

    /**
     * Constructor
     * 
     * @param string $collection
     * @param string $hostname
     * @param int $port
     */
    public function __construct($collection = null, $hostname = "10.131.211.185", $port = 27017) {
        
        
        $this->setClient(new \MongoClient("mongodb://$hostname:$port"));

        $this->setHostname($hostname);
        $this->setPort($port);

        //select database
        $database = (\App::make('app.config.env')->APP_ENV !== 'local')? 'sandbox': 'sandbox_dev';
        
        $this->setDbName($database);
        $this->setCollection($collection);
        $this->setDb($this->client->selectDB($this->getDbName()));
    }

    /**
     * Returns Mongo Client
     * 
     * @return MongoClient
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * Set Mongo Client
     * 
     * @param MongoClient $client
     */
    public function setClient($client) {
        $this->client = $client;
    }

    /**
     * Returns Database Name
     * 
     * @return type
     */
    public function getDbName() {
        return $this->dbName;
    }

    /**
     * Returns MongoDB
     * 
     * @return MongoDB
     */
    public function getDb() {
        return $this->db;
    }

    /**
     * Set MongoDB
     * 
     * @param MongoDB $db
     */
    public function setDb($db) {
        $this->db = $db;
    }

    /**
     * Set Database Name
     * 
     * @param type $dbName
     */
    public function setDbName($dbName) {
        $this->dbName = $dbName;
    }

    /**
     * Returns MongoCollection
     * 
     * @param string $collection
     * @return MongoCollection
     */
    public function getCollection($collection = null) {
        return $this->client->selectCollection($this->getDbName(), (empty($collection) ? $this->collection : $collection));
    }

    /**
     * Set Collection
     * @param string $collection
     */
    public function setCollection($collection) {
        $this->collection = $collection;
    }

    /**
     * Returns Hostname
     * 
     * @return string
     */
    public function getHostname() {
        return $this->hostname;
    }

    /**
     * Returns Port
     * 
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Set Hostname
     * 
     * @param string $hostname
     */
    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    /**
     * Set Port
     * 
     * @param int $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * Returns self instance
     * 
     * @param type $collection
     * @return \self
     */
    public static function instance($collection) {
        return new self($collection);
    }

}
