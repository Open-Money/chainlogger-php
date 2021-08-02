# chainlogger-php

Simple PHP package for using Chain Logger on omChain Jupiter

### ABI:
```
[{"type":"constructor","stateMutability":"nonpayable","inputs":[]},{"type":"event","name":"LogRegistered","inputs":[{"type":"address","name":"_vendorAddress","internalType":"address","indexed":true},{"type":"uint256","name":"_projectId","internalType":"uint256","indexed":false},{"type":"uint256","name":"_projectLogCounter","internalType":"uint256","indexed":false},{"type":"bytes32","name":"_data","internalType":"bytes32","indexed":true}],"anonymous":false},{"type":"event","name":"VendorRegistered","inputs":[{"type":"uint256","name":"_id","internalType":"uint256","indexed":true},{"type":"address","name":"_vendorAddress","internalType":"address","indexed":true}],"anonymous":false},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"bool","name":"","internalType":"bool"}],"name":"_changeOwner","inputs":[{"type":"address","name":"toOwner","internalType":"address"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"bytes32","name":"","internalType":"bytes32"}],"name":"getLog","inputs":[{"type":"address","name":"vendorAddress","internalType":"address"},{"type":"uint256","name":"projectId","internalType":"uint256"},{"type":"uint256","name":"logId","internalType":"uint256"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"numVendors","inputs":[]},{"type":"function","stateMutability":"view","outputs":[{"type":"address","name":"","internalType":"address"}],"name":"owner","inputs":[]},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"address","name":"","internalType":"address"},{"type":"uint256","name":"","internalType":"uint256"},{"type":"uint256","name":"","internalType":"uint256"},{"type":"bytes32","name":"","internalType":"bytes32"}],"name":"registerLog","inputs":[{"type":"uint256","name":"projectId","internalType":"uint256"},{"type":"bytes32","name":"data","internalType":"bytes32"}]},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"registerProject","inputs":[]},{"type":"function","stateMutability":"nonpayable","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"registerVendor","inputs":[]},{"type":"function","stateMutability":"view","outputs":[{"type":"address","name":"vendorAddress","internalType":"address"},{"type":"uint256","name":"projectCounter","internalType":"uint256"}],"name":"vendorLogs","inputs":[{"type":"uint256","name":"","internalType":"uint256"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"address","name":"","internalType":"address"}],"name":"vendors","inputs":[{"type":"uint256","name":"","internalType":"uint256"}]},{"type":"function","stateMutability":"view","outputs":[{"type":"uint256","name":"","internalType":"uint256"}],"name":"vendorsReverse","inputs":[{"type":"address","name":"","internalType":"address"}]}]
        
```

## Installation

```composer require openmoney/chainlogger-php```

After requiring via composer, you can include the Chain Logger on your projects as following

```
use ChainLogger\Logger as Logger;

$logger = Logger::create();
$logger->setProvider('YOUR_PROVIDER_URL');
$logger->setAbi('CONTRACT_ABI');
$logger->setEthSigner('YOUR_ETH_SIGNER_PROVIDER');
$logger->setWeb3();
$logger->setContract('CONTRACT_ADDRESS');
$logger->setSendContract('CONTRACT_ADDRESS');
$logger->setSalt('YOUR_SALT');
$logger->setAccount('YOUR_WALLET_ADDRESS');
```

## Registering vendor

```
$logger->registerVendor();

//Returns the txHash of the call
```

## Registering project

```
$logger->registerProject();

//Returns the txHash of the call
```

## Registering a log

```
$logger->registerLog($projectId,$rawData);

//Returns the txHash of the call
```

## Getting tx receipt for registerLog method

```
$logger->getTransactionReceipt($txId);
```

## Verifying data from blockchain

```
$logger->verifyData($hashedDataFromBlockchain,$rawInput,$salt)

//Returns boolean
```
