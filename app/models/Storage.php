<?php

class Storage {

    private $client;
    private $dbName;
    private $hostname;
    private $collection;
    private $port;
    private $db;

    public function __construct($collection = null, $hostname = "localhost", $port = 27017) {
        $this->setClient(new MongoClient("mongodb://$hostname:$port"));

        $this->setHostname($hostname);
        $this->setPort($port);

        //select database
        $this->setDbName('sandbox');
        $this->setCollection($collection);
        $this->setDb($this->client->selectDB($this->getDbName()));
    }

    public function getClient() {
        return $this->client;
    }

    public function setClient($client) {
        $this->client = $client;
    }

    public function getDbName() {
        return $this->dbName;
    }

    public function getDb() {
        return $this->db;
    }

    public function setDb($db) {
        $this->db = $db;
    }

    public function setDbName($dbName) {
        $this->dbName = $dbName;
    }

    public function getCollection($name = null) {
        return $this->client->selectCollection($this->getDbName(), (empty($name) ? $this->collection : $name));
    }

    public function setCollection($collection) {
        $this->collection = $collection;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function getPort() {
        return $this->port;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public static function instance($collection){
        return new self($collection);
    }
}
