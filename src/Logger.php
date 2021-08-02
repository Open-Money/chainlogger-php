<?php

namespace ChainLogger;

use ChainLogger\Contract as Contract;
use ChainLogger\Parser as Parser;
use ChainLogger\Parameters as Parameters;;
use ChainLogger\Hasher as Hasher;

class Logger extends Contract
{

    /**
     * Account user is calling the contract from
     * @var string
     */
    protected $account;

    /**
     * Salt that will be used for hashing the JSON input
     *
     * @var string
     */
    protected $salt = "CHANGE_ME";

    /**
     * Amount of gas to use
     *
     * @var string
     */
    protected $gas = '0x3d090';
    
    /**
     * The gas price to be paid per gas
     *
     * @var string
     */
    protected $gasPrice = '0x174876e800';

    /**
     * Static function that returns the Logger instance
     *
     * @return Logger
     */
    public static function create()
    {
        return new Logger();
    }

    /**
     * Sets the gas
     *
     * @param string $gas
     * @return void
     */
    public function setGas($gas)
    {
        $this->gas = $gas;
    }

    /**
     * Sets gas price
     *
     * @param string $gasPrice
     * @return void
     */
    public function setGasPrice($gasPrice)
    {
        $this->gasPrice = $gasPrice;
    }

    /**
     * Sets the user's account from EthSigner
     *
     * @param string $account
     * @return void
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * Sets the salt
     *
     * @param string $salt
     * @return void
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Calls the registerVendor method on the omChain
     * Returns the transaction hash or throws error
     * 
     * Takes gasPrice and gas as hexadecimal
     *
     * @param string $gasPrice
     * @param string $gas
     * @return string
     */
    public function registerVendor()
    {
        $parameters = [
            'from' => $this->account,
            'gasPrice' => $this->gasPrice,
            'gas' => $this->gas
        ];

        $txHash = null;
        $this->contract->send('registerVendor',$parameters,function($err,$result) use (&$txHash) {
            if($err !== null) {
                throw $err;
            }

            if($result) {
                $txHash = $result;
            }
        });

        return $txHash;
    }

    /**
     * Registers a project
     *
     * @param string $gasPrice
     * @param string $gas
     * @return string
     */
    public function registerProject()
    {
        $parameters = [
            'from' => $this->account,
            'gasPrice' => $this->gasPrice,
            'gas' => $this->gas
        ];

        $txHash = null;
        $this->contract->send('registerProject',$parameters,function($err,$result) use (&$txHash) {
            if($err !== null) {
                throw $err;
            }

            if($result) {
                $txHash = $result;
            }
        });

        return $txHash;
    }

    /**
     * Registers a log
     *
     * @param string $projectId
     * @param mixed $rawdata
     * @param string $gasPrice
     * @param string $gas
     * @return string
     */
    public function registerLog($projectId,$rawdata)
    {
        $parameters = [
            'from' => $this->account,
            'gasPrice' => $this->gasPrice,
            'gas' => $this->gas
        ];

        $hasher = Hasher::create($this->salt);
        $toChainData = Parser::jsonEncode($rawdata);
        $toChainData = $hasher->hashWithSalt($toChainData);
        $toChainData = Hasher::add0x($toChainData);

        $txHash = null;
        $this->sendContract->send('registerLog',$projectId,$toChainData,$parameters,
            function($err,$result) use (&$txHash){
                if($err !== null) {
                    throw $err;
                }

                if($result) {
                    $txHash = $result;
                }
            });

        return $txHash;
    }

    /**
     * Gets transaction receipt with events
     *
     * @param string $txId
     * @return array[
     *  'transactionHash' => string,
     *  'blockHash' => string,
     *  'blockNumber' => int,
     *  'data' => array
     * ]
     */
    public function getTransactionReceipt($txId)
    {

        $eth = $this->web3->eth;
        $receipt = null;

        $eth->getTransactionReceipt($txId,function ($err, $response) use (&$receipt) {
            if($err !== null) {
                throw $err;
            }

            if($response) {
                $receipt = $response;
            }
        });

        $contract = $this->contract;
        
        foreach ($contract->events['LogRegistered']['inputs'] as $input) {
            if ($input['indexed']) {
                $eventIndexedParameterNames[] = $input['name'];
                $eventIndexedParameterTypes[] = $input['type'];
            } else {
                $eventParameterNames[] = $input['name'];
                $eventParameterTypes[] = $input['type'];
            }
        }

        $numEventIndexedParameterNames = count($eventIndexedParameterNames);

        $logs = $receipt->logs;

        foreach($logs as $object)
        {
            $decodedData = array_combine($eventParameterNames,$contract->ethabi->decodeParameters($eventParameterTypes,$object->data));

            for ($i = 0; $i < $numEventIndexedParameterNames; $i++) {
                $decodedData[$eventIndexedParameterNames[$i]] = $contract->ethabi->decodeParameters([$eventIndexedParameterTypes[$i]], $object->topics[$i + 1])[0];
            }

            $eventLogData[] = [
                'transactionHash' => $object->transactionHash,
                'blockHash' => $object->blockHash,
                'blockNumber' => hexdec($object->blockNumber),
                'data' => $decodedData
            ];
        }

        return $eventLogData[0];


    }
    
    /**
     * Get Batch Transaction Receipts
     *
     * @param array[string] $txIds
     * @return array[string]
     */
    public function getBatchTransactionReceipt($txIds)
    {
        $returnArray = [];
        foreach ($txIds as $value) {
            $returnArray[] = getTransactionReceipt($value);
        }

        return $returnArray;
    }

    /**
     * Parses the transaction receipt's data column
     * to return string instead of hexdec
     *
     * @param array $blockData
     * @return array
     */
    public static function parseData($blockData)
    {
        $returnData = [];
        foreach ($blockData as $key => $value) {
            if($value instanceof \phpseclib\Math\BigInteger) {
                $returnData[$key] = $value->toString();
            } else {
                $returnData[$key] = $value;
            }
        }

        return $returnData;
    }

    /**
     * Parse Batch Block Data
     *
     * @param array $blockDatas
     * @return array
     */
    public static function parseBatchData($blockDatas)
    {
        $returnData[] = null;
        foreach ($blockDatas as $value) {
            $returnData[] = Logger::parseData($value);
        }

        return $returnData;
    }

    /**
     * Verify integrity of blockchain record
     *
     * @param string $hashedData
     * @param mixed $rawInput
     * @param string $salt
     * @return bool
     */
    public function verifyData($hashedData,$rawInput,$salt = null)
    {
        $hasher = $salt ? Hasher::create($salt) : Hasher::create($this->salt);
        
        $toChainData = Parser::jsonEncode($rawInput);
        $toChainData = $hasher->hashWithSalt($toChainData);
        $toChainData = Hasher::add0x($toChainData);

        if($hashedData == $toChainData) {
            return true;
        } else {
            return false;
        }
    }
}