<?php

namespace GuldenPHP\Tests\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Block;
use GuldenPHP\Model\Domain\BlockChain;
use GuldenPHP\Model\Domain\ChainTip;
use GuldenPHP\Model\Domain\MemPool;
use GuldenPHP\Model\Domain\MemPoolEntry;
use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\SubClient\BlockChainSubClient;
use GuldenPHP\Tests\ClientTestHelper;
use GuldenPHP\Tests\Fixtures\Fixtures;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenPHP\SubClient\AbstractSubClient
 * @covers \GuldenPHP\SubClient\BlockChainSubClient
 */
class BlockChainSubClientTest extends ClientTestHelper
{
    /** @var BlockChainSubClient */
    private $client;

    /** @var ObjectProphecy|GuldenClient */
    private $guldenClient;

    public function setUp()
    {
        $this->guldenClient = self::prophesize(GuldenClient::class);

        $this->client = BlockChainSubClient::fromClient($this->guldenClient->reveal());
    }

    public function testGetBestBlockHashReturnsHashStringIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, 'block-hash');

        $this->guldenClient->executeCommand('getbestblockhash')->willReturn($response);

        self::assertSame('block-hash', $this->client->getBestBlockHash());
    }

    public function testGetBestBlockHashThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getbestblockhash')->willReturn($response);

        $this->client->getBestBlockHash();
    }

    public function testGetBlockWillReturnBlockInstanceIfRequestSucceeds()
    {
        $blockHash = Fixtures::randomHash();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, Fixtures::block());

        $this->guldenClient->executeCommand('getblock', $blockHash, true)->willReturn($response);

        self::assertInstanceOf(Block::class, $this->client->getBlock($blockHash));
    }

    public function testGetBlockThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $blockHash = Fixtures::randomHash();

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getblock', $blockHash, true)->willReturn($response);

        $this->client->getBlock($blockHash);
    }

    public function testGetBlockChainInfoWillReturnBlockChainInfoIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, Fixtures::blockChainInfo());

        $this->guldenClient->executeCommand('getblockchaininfo')->willReturn($response);

        self::assertInstanceOf(BlockChain::class, $this->client->getBlockChainInfo());
    }

    public function testGetBlockChainInfoThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getblockchaininfo')->willReturn($response);

        $this->client->getBlockChainInfo();
    }

    public function testGetBlockCountReturnsWillReturnBlockCountIfRequestSucceeds()
    {
        $blockCount = 123456;

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $blockCount);

        $this->guldenClient->executeCommand('getblockcount')->willReturn($response);

        self::assertSame($blockCount, $this->client->getBlockCount());
    }

    public function testGetBlockCountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getblockcount')->willReturn($response);

        $this->client->getBlockCount();
    }

    public function testGetBlockHashReturnsBlockHashIfRequestSucceeds()
    {
        $blockHash = Fixtures::randomHash();
        $blockNumber = 123456;

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $blockHash);

        $this->guldenClient->executeCommand('getblockhash', $blockNumber)->willReturn($response);

        self::assertSame($blockHash, $this->client->getBlockHash($blockNumber));
    }

    public function testGetBlockHashThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $blockNumber = 123456;

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getblockhash', $blockNumber)->willReturn($response);

        $this->client->getBlockHash($blockNumber);
    }

    public function testGetBlockHeaderReturnsBlockHeaderIfRequestSucceeds()
    {
        $blockHash = Fixtures::randomHash();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, Fixtures::block());

        $this->guldenClient->executeCommand('getblockheader', $blockHash)->willReturn($response);

        self::assertInstanceOf(Block::class, $this->client->getBlockHeader($blockHash));
    }

    public function testGetBlockHeaderThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $blockHash = Fixtures::randomHash();

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getblockheader', $blockHash)->willReturn($response);

        $this->client->getBlockHeader($blockHash);
    }

    public function testGetChainTipsReturnsChainTipsIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, [
            Fixtures::chainTip(),
            Fixtures::chainTip(),
            Fixtures::chainTip()
        ]);

        $this->guldenClient->executeCommand('getchaintips')->willReturn($response);

        self::assertContainsOnlyInstancesOf(ChainTip::class, $this->client->getChainTips());
    }

    public function testGetChainTipsThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getchaintips')->willReturn($response);

        $this->client->getChainTips();
    }

    public function testGetDifficultyReturnsDifficultyIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, 1234.56);

        $this->guldenClient->executeCommand('getdifficulty')->willReturn($response);

        self::assertSame(1234.56, $this->client->getDifficulty());
    }

    public function testGetDifficultyThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getdifficulty')->willReturn($response);

        $this->client->getDifficulty();
    }

    public function testGetMemPoolEntryReturnsMempoolEntryIfRequestSucceeds()
    {
        $transactionId = Fixtures::randomHash();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, Fixtures::mempoolEntry());

        $this->guldenClient->executeCommand('getmempoolentry', $transactionId)->willReturn($response);

        self::assertInstanceOf(MemPoolEntry::class, $this->client->getMemPoolEntry($transactionId));
    }

    public function testGetMemPoolThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $transactionId = Fixtures::randomHash();

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getmempoolentry', $transactionId)->willReturn($response);

        $this->client->getMemPoolEntry($transactionId);
    }

    public function testGetMemPoolInfoReturnsMempoolInfoIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, Fixtures::mempoolInfo());

        $this->guldenClient->executeCommand('getmempoolinfo')->willReturn($response);

        self::assertInstanceOf(MemPool::class, $this->client->getMemPoolInfo());
    }

    public function testGetMemPoolInfoThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getmempoolinfo')->willReturn($response);

        $this->client->getMemPoolInfo();
    }

    public function testGetRawMemPoolReturnsRawMemPoolIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, [
            Fixtures::randomHash(),
            Fixtures::randomHash(),
            Fixtures::randomHash(),
            Fixtures::randomHash()
        ]);

        $this->guldenClient->executeCommand('getrawmempool')->willReturn($response);

        $rawMemPool = $this->client->getRawMemPool();

        self::assertContainsOnly('string', $rawMemPool);
        self::assertCount(4, $rawMemPool);
    }

    public function testGetRawMemPoolThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getrawmempool')->willReturn($response);

        $this->client->getRawMemPool();
    }

    /**
     * @param      $responseBody
     * @param bool $expectedResult
     *
     * @testWith [true, true]
     *           [false, false]
     */
    public function testVerifyChainReturnsValidityOfTheChainIfTheRequestSucceeds($responseBody, bool $expectedResult)
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $responseBody);

        $this->guldenClient->executeCommand('verifychain', 1000)->willReturn($response);

        self::assertSame($expectedResult, $this->client->verifyChain(1000));
    }

    public function testVerifyChainThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('verifychain', 1000)->willReturn($response);

        $this->client->verifyChain(1000);
    }
}
