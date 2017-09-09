<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

class MemPool
{
    /** @var int */
    private $bytes = -1;

    /** @var int */
    private $max = -1;

    /** @var float */
    private $minFee = -1;

    /** @var int */
    private $size = -1;

    /** @var int */
    private $usage = -1;

    /**
     * @param int   $bytes
     * @param int   $maxMemPoolSize
     * @param float $memPoolMinFee
     * @param int   $size
     * @param int   $usage
     */
    private function __construct(int $bytes, int $maxMemPoolSize, float $memPoolMinFee, int $size, int $usage)
    {
        $this->bytes = $bytes;
        $this->max = $maxMemPoolSize;
        $this->minFee = $memPoolMinFee;
        $this->size = $size;
        $this->usage = $usage;
    }

    /**
     * @param array $memPoolInfo
     *
     * @return MemPool
     */
    public static function fromArray(array $memPoolInfo): self
    {
        return new static(
            $memPoolInfo['bytes'] ?? -1,
            $memPoolInfo['maxmempool'] ?? -1,
            $memPoolInfo['mempoolminfee'] ?? -1,
            $memPoolInfo['size'] ?? -1,
            $memPoolInfo['usage'] ?? -1
        );
    }

    /**
     * @return int
     */
    public function getBytes(): int
    {
        return $this->bytes;
    }

    /**
     * @return int
     */
    public function getMaxMemPoolSize(): int
    {
        return $this->max;
    }

    /**
     * @return float
     */
    public function getMinFee(): float
    {
        return $this->minFee;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getUsage(): int
    {
        return $this->usage;
    }
}
