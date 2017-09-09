<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\BlockChain;
use GuldenPHP\Tests\Fixtures\Fixtures;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\BlockChain
 */
class BlockChainTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(BlockChain::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'chain' => 'main',
            'blocks' => 1234,
            'headers' => 1230,
            'bestblockhash' => Fixtures::randomHash(),
            'verificationprogress' => 0.9,
            'chainwork' => '000000000000000000000000000000000000000000000000006a4b09fc21bb6e',
            'difficulty' => 1234.56,
            'mediantime' => 12345678,
            'pruned' => false,
            'softforks' => []
        ];

        $blockChain = BlockChain::fromArray($data);

        self::assertSame($data['chain'], $blockChain->getChain());
        self::assertSame($data['blocks'], $blockChain->getBlocks());
        self::assertSame($data['headers'], $blockChain->getHeaders());
        self::assertSame($data['bestblockhash'], $blockChain->getBestBlockHash());
        self::assertSame($data['verificationprogress'], $blockChain->getVerificationProgress());
        self::assertSame($data['chainwork'], $blockChain->getChainWork());
        self::assertSame($data['difficulty'], $blockChain->getDifficulty());
        self::assertSame($data['pruned'], $blockChain->isPruned());
        self::assertSame($data['softforks'], $blockChain->getSoftForks());

        self::assertSame($data['mediantime'], (int)$blockChain->getMedianTime()->format('U'));
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(BlockChain::class, BlockChain::fromArray([]));
    }
}
