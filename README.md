# [PHP] Gulden Client

This Gulden client can be used to communicate with a Gulden node directly from PHP. It requires PHP 7.0 or higher.

Inspiration for this project came from [@paulwillen](https://github.com/paulwillen)'s [EasyGulden-PHP](https://github.com/paulwillen/EasyGulden-PHP). You can check that out as well, it's a single file you can include and it may suit your needs.

## Contributing

Currently only the most useful methods have been explicitely defined on this client. As explained below, all the other methods that are available on the Gulden-cli, can still be called through this client. The distinction about a method being implicitely or explicitely implemented, is that that the explicit ones give you better code completion in your IDE (may I recommend [PhpStorm](https://www.jetbrains.com/phpstorm/)) and pre-parsed results, instead of raw json data.

![Code Completion](/readme-images/code-completion-move.png?raw=true "Code Completion")

If a method you'd really like to use, is missing, you can simply open an [issue](https://github.com/ErikBooij/php-gulden-client/issues) or better yet, open a [pull request](https://github.com/ErikBooij/php-gulden-client/pulls).

## Installation
The easiest way of installing the Gulden client into your project, is through Composer:

```bash
$ composer require erikbooij/gulden-client
```

## Usage
You can simply instantiate a Gulden client by passing it your credentials.

```php
<?php

$username = 'testUser';
$password = 'password';
$hostname = 'http://127.0.0.1';
$port = 9232;

$guldenClient = new GuldenPHP\GuldenClient($username, $password [, $hostname, $port]);
```

In the above example, you could leave out the hostname and port because these are the default values.

Communication to the Gulden node is done over JSON RPC and every single method the Gulden node implements can be called directly on the client. For example, if you want to call `gettxout` on the Gulden node, you can simply do that as follows:

```php
<?php

$guldenClient->gettxout('transactionId', 1, true);
```

Every method throws a `NodeException` in case it doesn't receive a successful response from the Gulden server. This is useful for methods that are not explicitely implemented on the client. Most methods however, have been explicitely defined and namespaced:

### Accounts

Call within `$client->accounts()` namespace.

- `changeAccountName(string $account, string $name)`
- `createAccount(string $name)`
- `deleteAccount(string $account, bool $force = false)`
- `getAccount(string $address)`
- `getActiveAccount()`
- `getAddressesByAccount(string $account)`
- `getReadOnlyAccount(string $account)`
- `importReadOnlyAccount(string $name, string $encodedKey)`
- `listAccounts(string $seed = null)`
- `setActiveAccount(string $account)`

#### Example:

```php
<?php

$client->accounts()->getActiveAccount();
```

### Blockchain

Call within `$client->blockchain()` namespace.

- `getBestBlockHash()`
- `getBlock(string $hash)`
- `getBlockChainInfo()`
- `getBlockCount()`
- `getBlockHash(int $blockNumber)`
- `getBlockHeader(string $blockHash)`
- `getChainTips()`
- `getDifficulty()`
- `getMemPoolEntry(string $transactionId)`
- `getMemPoolInfo()`
- `getRawMemPool()`
- `verifyChain(int $numberOfBlocks)`

#### Example:

```php
<?php

$client->blockchain()->getBlockCount();
```

### Control

Call within `$client->control()` namespace.

- `getInfo()`
- `stop()`

#### Example:

```php
<?php

$client->control()->getInfo();
```

### Wallet

Call within `$client->wallet()` namespace.

- `abandonTransaction(string $transactionId)`
- `backUpWallet(string $filename)`
- `getBalance(string $account = '*', int $minConf = 0, bool $includeWatchOnly = false)`
- `getNewAddress(string $account)`
- `getRawChangeAddress()`
- `getUnconfirmedBalance()`
- `getWalletInfo()`
- `move(string $fromAccount, string $toAccount, float $amount, int $minimumConfirmations = 1, string $comment = '' )`
- `refillKeyPool(int $size)`
- `sendToMany(string $fromAccount, array $addressesWithAmounts, int $minimumConfirmations = 1, string $comment = '', array $subtractFeeFromAddresses = [])`
- `sendToAddress(string $toAddress, float $amount, string $comment = '', string $commentTo = '', bool $subtractFeeFromAmount = false )`
- `sendToAddressFromAccount(string $fromAccount, string $toAddress, float $amount, string $comment = '', string $commentTo = '', bool $subtractFeeFromAmount = false)`
- `setTransactionFee(float $transactionFee)`
- `signMessage(string $address, string $message)`

#### Example:

```php
<?php

$client->wallet()->getWalletInfo();
```
