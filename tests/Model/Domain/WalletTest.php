<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\Wallet;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\Wallet
 */
class WalletTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(Wallet::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'balance' => 1234.56,
            'immature_balance' => 12.34,
            'keypoololdest' => 123,
            'paytxfee' => 12.345,
            'txcount' => 123,
            'unconfirmed_balance' => 12345.567,
            'walletversion' => 2
        ];

        $wallet = Wallet::fromArray($data);

        self::assertSame($data['balance'], $wallet->getBalance());
        self::assertSame($data['immature_balance'], $wallet->getImmatureBalance());
        self::assertSame($data['keypoololdest'], $wallet->getKeyPoolOldest());
        self::assertSame($data['paytxfee'], $wallet->getPayTransactionFee());
        self::assertSame($data['txcount'], $wallet->getTransactionCount());
        self::assertSame($data['unconfirmed_balance'], $wallet->getUnconfirmedBalance());
        self::assertSame($data['walletversion'], $wallet->getWalletVersion());
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(Wallet::class, Wallet::fromArray([]));
    }
}
