<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\Account;
use GuldenPHP\Tests\Fixtures\Fixtures;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\Account
 */
class AccountTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(Account::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'UUID' => Fixtures::randomUuid(),
            'label' => 'account-label',
            'type' => 'HD',
            'HDindex' => 1234
        ];

        $account = Account::fromArray($data);

        self::assertSame($data['UUID'], $account->getUUID());
        self::assertSame($data['label'], $account->getLabel());
        self::assertSame($data['type'], $account->getType());
        self::assertSame($data['HDindex'], $account->getHDindex());
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(Account::class, Account::fromArray([]));
    }
}
