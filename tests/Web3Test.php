<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use ChainLogger\Logger as Logger;

class Web3Test extends TestCase
{
    public $logger;
    
    public function setUp(): void
    {
        /**
         * ABI for the test contract
         */
        $abi = '[{"type":"constructor","stateMutability":"nonpayable","inputs":[]},{"type":"event","name":"LogRegistered","inputs":[{"type":"address","name":"_vendorAddress","internalType":"address","indexed":true},{"type":"uint256","name":"_projectId","internalType":"uint256","indexed":false},{"type":"uint256","name":"_projectLogCounter","internalType":"uint256","indexed":false},{"type":"bytes32","name":"_data","internalType":"bytes32","indexed":true}],"anonymous":false},{"type":"event","name":"VendorRegistered","inputs":[{"type":"uint256","name":"_id","internalType":"uint256","indexed":true},{"type":"address","name":"_vendorAddress","internalType":"address","indexed":true}],"anonymous":false},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"bool","name":"","internalType":"bool"}],"name":"_changeOwner","inputs":[{"type":"address","name":"toOwner","internalType":"address"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"bytes32","name":"","internalType":"bytes32"}],"name":"getLog","inputs":[{"type":"address","name":"vendorAddress","internalType":"address"},{"type":"uint256","name":"projectId","internalType":"uint256"},{"type":"uint256","name":"logId","internalType":"uint256"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"numVendors","inputs":[]},{"type":"function","stateMutability":"view","outputs":[{"type":"address","name":"","internalType":"address"}],"name":"owner","inputs":[]},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"address","name":"","internalType":"address"},{"type":"uint256","name":"","internalType":"uint256"},{"type":"uint256","name":"","internalType":"uint256"},{"type":"bytes32","name":"","internalType":"bytes32"}],"name":"registerLog","inputs":[{"type":"uint256","name":"projectId","internalType":"uint256"},{"type":"bytes32","name":"data","internalType":"bytes32"}]},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"registerProject","inputs":[]},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"registerVendor","inputs":[]},{"type":"function","stateMutability":"view","outputs":[{"type":"address","name":"vendorAddress","internalType":"address"},{"type":"uint256","name":"projectCounter","internalType":"uint256"}],"name":"vendorLogs","inputs":[{"type":"uint256","name":"","internalType":"uint256"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"address","name":"","internalType":"address"}],"name":"vendors","inputs":[{"type":"uint256","name":"","internalType":"uint256"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"vendorsReverse","inputs":[{"type":"address","name":"","internalType":"address"}]}]';
        
        /**
         * Public provider to read blockchain data
         */
        $provider = 'https://provider.omlira.com';

        /**
         * Private eth signer IP address that can write to the contract
         * Be careful with your instance's security
         */
        $ethSigner = 'ETH_SIGNER_PROVIDER';

        /**
         * Address of the contract
         */
        $at = '0x27977679d45bdB739E1cdd9A5c510B471CA0aB75';
    
        


        $logger = Logger::create();
        $logger->setProvider($provider);
        $logger->setAbi($abi);
        $logger->setEthSigner($ethSigner);
        $logger->setWeb3();
        $logger->setContract($at);
        $logger->setSendContract($at);
        $logger->setSalt('MY_SECRET_SALT');


        $logger->setAccount('YOUR_WALLET_ADDRESS');
        $this->logger = $logger;
    }

    /**
     * Returns the tx hash of the event
     * You must call registerVendor and registerProject methods 
     * before trying to test logger
     *
     * @return void
     */
    public function testLogger()
    {
        $rawInput = "MY_TEST_INPUT";
        $txHash = $this->logger->registerLog('0',$rawInput);
        echo $txHash;
    }



}