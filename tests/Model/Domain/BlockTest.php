<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\Block;
use GuldenPHP\Tests\Fixtures\Fixtures;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\Block
 */
class BlockTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(Block::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'height' => 123456,
            'confirmations' => 15,
            'hash' => Fixtures::randomHash(),
            'previousblockhash' => Fixtures::randomHash(),
            'nextblockhash' => Fixtures::randomHash(),
            'difficulty' => 1234.56,
            'chainwork' => '000000000000000000000000000000000000000000000000006a4b09fc21bb6e',
            'merkleroot' => Fixtures::randomHash(),
            'nonce' => 12345678,
            'tx' => [],
            'time' =>  12345678,
            'mediantime' =>  12345670,
            'size' => 12345,
            'strippedsize' => 12340,
            'bits' => '1d00ffff',
            'version' => 2,
            'versionHex' => '20000',
            'weight' =>  123
        ];

        $block = Block::fromArray($data);

        self::assertSame($data['height'], $block->getHeight());
        self::assertSame($data['confirmations'], $block->getConfirmations());
        self::assertSame($data['hash'], $block->getHash());
        self::assertSame($data['previousblockhash'], $block->getPreviousBlockHash());
        self::assertSame($data['nextblockhash'], $block->getNextBlockHash());
        self::assertSame($data['difficulty'], $block->getDifficulty());
        self::assertSame($data['chainwork'], $block->getChainWork());
        self::assertSame($data['merkleroot'], $block->getMerkleRoot());
        self::assertSame($data['nonce'], $block->getNonce());
        self::assertSame($data['tx'], $block->getTransactions());
        self::assertSame(0, $block->getTransactionCount());
        self::assertSame($data['size'], $block->getSize());
        self::assertSame($data['strippedsize'], $block->getStrippedSize());
        self::assertSame($data['bits'], $block->getBits());
        self::assertSame($data['version'], $block->getVersion());
        self::assertSame($data['versionHex'], $block->getVersionHex());
        self::assertSame($data['weight'], $block->getWeight());

        self::assertSame($data['time'], (int)$block->getTime()->format('U'));
        self::assertSame($data['mediantime'], (int)$block->getMedianTime()->format('U'));
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(Block::class, Block::fromArray([]));
    }
}
