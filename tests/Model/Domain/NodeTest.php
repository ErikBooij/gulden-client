<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\Node;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\Node
 */
class NodeTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(Node::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'blocks' => 123456,
            'connections' => 8,
            'difficulty' => 1234.56,
            'errors' => '',
            'mininput' => 123.345,
            'paytxfee' => 123.4567,
            'protocolversion' => 2,
            'proxy' => '',
            'relayfee' => 1234.2345,
            'testnet' => false,
            'timeoffset' => 0,
            'version' => 2
        ];

        $node = Node::fromArray($data);

        self::assertSame($data['blocks'], $node->getBlocks());
        self::assertSame($data['connections'], $node->getConnections());
        self::assertSame($data['difficulty'], $node->getDifficulty());
        self::assertSame($data['errors'], $node->getErrors());
        self::assertSame($data['mininput'], $node->getMinInput());
        self::assertSame($data['paytxfee'], $node->getPayTransactionFee());
        self::assertSame($data['protocolversion'], $node->getProtocolVersion());
        self::assertSame($data['proxy'], $node->getProxy());
        self::assertSame($data['relayfee'], $node->getRelayFee());
        self::assertSame($data['testnet'], $node->isTestNet());
        self::assertSame($data['timeoffset'], $node->getTimeOffset());
        self::assertSame($data['version'], $node->getVersion());
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(Node::class, Node::fromArray([]));
    }
}
