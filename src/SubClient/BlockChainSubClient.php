<?php
declare(strict_types=1);

namespace GuldenPHP\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Block;
use GuldenPHP\Model\Domain\BlockChain;
use GuldenPHP\Model\Domain\ChainTip;
use GuldenPHP\Model\Domain\MemPool;
use GuldenPHP\Model\Domain\MemPoolEntry;
use GuldenPHP\Model\NodeException;

class BlockChainSubClient extends AbstractSubClient
{
    /**
     * @return string
     * @throws NodeException
     */
    public function getBestBlockHash(): string
    {
        $response = $this->client->executeCommand('getbestblockhash')
            ->throwIfUnsuccessful();

        return $response->getBody();
    }

    /**
     * @param string $hash
     *
     * @return Block
     * @throws NodeException
     */
    public function getBlock(string $hash): Block
    {
        $response = $this->client->executeCommand('getblock', $hash, true)
            ->throwIfUnsuccessful();

        return Block::fromArray($response->getBody());
    }

    /**
     * @return BlockChain
     * @throws NodeException
     */
    public function getBlockChainInfo(): BlockChain
    {
        $response = $this->client->executeCommand('getblockchaininfo')
            ->throwIfUnsuccessful();

        return BlockChain::fromArray($response->getBody());
    }

    /**
     * @return int
     * @throws NodeException
     */
    public function getBlockCount(): int
    {
        $response = $this->client->executeCommand('getblockcount')
            ->throwIfUnsuccessful();

        return (int)$response->getBody();
    }

    /**
     * @param int $blockNumber
     *
     * @return string
     * @throws NodeException
     */
    public function getBlockHash(int $blockNumber): string
    {
        $response = $this->client->executeCommand('getblockhash', $blockNumber)
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param string $blockHash
     *
     * @return Block
     * @throws NodeException
     */
    public function getBlockHeader(string $blockHash): Block
    {
        $response = $this->client->executeCommand('getblockheader', $blockHash)
            ->throwIfUnsuccessful();

        return Block::fromArray($response->getBody());
    }

    /**
     * @return ChainTip[]
     * @throws NodeException
     */
    public function getChainTips(): array
    {
        $response = $this->client->executeCommand('getchaintips')
            ->throwIfUnsuccessful();

        return array_map(function (array $chainTipInfo): ChainTip {
            return ChainTip::fromArray($chainTipInfo);
        }, $response->getBody());
    }

    /**
     * @return float
     * @throws NodeException
     */
    public function getDifficulty(): float
    {
        $response = $this->client->executeCommand('getdifficulty')
            ->throwIfUnsuccessful();

        return (float)$response->getBody();
    }

    /**
     * @param string $transactionId
     *
     * @return MemPoolEntry
     * @throws NodeException
     */
    public function getMemPoolEntry(string $transactionId): MemPoolEntry
    {
        $response = $this->client->executeCommand('getmempoolentry', $transactionId)
            ->throwIfUnsuccessful();

        return MemPoolEntry::fromArray($response->getBody());
    }

    /**
     * @return MemPool
     * @throws NodeException
     */
    public function getMemPoolInfo(): MemPool
    {
        $response = $this->client->executeCommand('getmempoolinfo')
            ->throwIfUnsuccessful();

        return MemPool::fromArray($response->getBody());
    }

    /**
     * @return string[]
     * @throws NodeException
     */
    public function getRawMemPool(): array
    {
        $response = $this->client->executeCommand('getrawmempool')
            ->throwIfUnsuccessful();

        return $response->getBody();
    }

    /**
     * @param int $numberOfBlocks
     *
     * @return mixed
     * @throws NodeException
     */
    public function verifyChain(int $numberOfBlocks): bool
    {
        $response = $this->client->executeCommand('verifychain', $numberOfBlocks)
            ->throwIfUnsuccessful();

        return (bool)$response->getBody();
    }

    /**
     * @param GuldenClient $client
     *
     * @return BlockChainSubClient
     */
    public static function fromClient(GuldenClient $client): self
    {
        return new static($client);
    }
}
