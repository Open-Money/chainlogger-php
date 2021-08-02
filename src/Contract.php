<?php

namespace ChainLogger;

use Web3\Contract as W3Contract;
use Web3\Web3;

class Contract
{

    /**
     * ABI of the contract
     *
     * @var string
     */
    public $abi;
    /**
     * URL of the provider
     *
     * @var string
     */
    public $provider;

    /**
     * Address of the deployed contract
     *
     * @var string
     */
    public $at;

    /**
     * URL of the private ETH Signer Instance
     *
     * @var string
     */
    protected $ethSigner;

    /**
     * Contract object
     *
     * @var object
     */
    protected $contract;

    /**
     * Contract object to use while interacting with ETH Signer
     *
     * @var object
     */
    protected $sendContract;

    /**
     * Base Web3 instance
     *
     * @var object
     */
    protected $web3;


    /**
     * Sets the provider to be used
     *
     * @param string $provider
     * @return void
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Sets the ABI for the contract
     *
     * @param string $abi
     * @return void
     */
    public function setAbi($abi)
    {
        $this->abi = $abi;
    }

    /**
     * Sets the ETH signer URL for the sending contract
     *
     * @param string $ethSigner
     * @return void
     */
    public function setEthSigner($ethSigner)
    {
        $this->ethSigner = $ethSigner;
    }

    /**
     * Sets the contract instance at given address
     *
     * @param string $contractAddress
     * @return void
     */
    public function setContract($contractAddress)
    {
        $contract = new W3Contract($this->provider,$this->abi);
        $this->contract = $contract->at($contractAddress); 
    }

    /**
     * Sets the transaction sending contract using EthSigner provider
     *
     * @param string $contractAddress
     * @return void
     */
    public function setSendContract($contractAddress)
    {
        $sendContract = new W3Contract($this->ethSigner,$this->abi);
        $this->sendContract = $sendContract->at($contractAddress); 
    }

    /**
     * Sets the Web3 object
     *
     * @return void
     */
    public function setWeb3()
    {
        $this->web3 = new Web3($this->provider);
    }

    /**
     * Gets the contract instance
     *
     * @return object $contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Gets the sending contract instance
     *
     * @return object $sendContract
     */
    public function getSendContract()
    {
        return $this->sendContract;
    }

    /**
     * Gets the Web3 instance
     *
     * @return object $web3
     */
    public function getWeb3()
    {
        return $this->web3;
    }

}