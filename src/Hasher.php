<?php

namespace ChainLogger;

use ChainLogger\Parser as Parser;

class Hasher
{

    /**
     * Salt that will be used before hashing
     *
     * @var string
     */
    private $salt;

    function __construct($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Create Hasher instance
     *
     * @param string $salt
     * @return object
     */
    public static function create($salt)
    {
        return new Hasher($salt);
    }

    /**
     * Hash the JSON encoded input with salt
     *
     * @param string $input
     * @return string
     */
    public function hashWithSalt($input)
    {
        return hash('sha256',$input.$this->salt,false);
    }

    /**
     * Add 0x to the hash, this is required before 
     * saving blockchain
     *
     * @param string $input
     * @return string
     */
    public static function add0x($input)
    {
        return '0x'.$input;
    }

    /**
     * Verify block receipt
     *
     * @param string $hashedInput
     * @param mixed $rawData
     * @return bool
     */
    public function verifyInput($hashedInput,$rawData)
    {
        $toCheck = Hasher::add0x($this->hashWithSalt(Parser::jsonEncode($rawData)));
        if($hashedInput == $toCheck) {
            return true;
        } else {
            return false;
        }
        
    }
}