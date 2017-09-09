<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

class ChainTip
{
    /** @var int */
    private $branchLength = -1;

    /** @var string */
    private $hash = '';

    /** @var int */
    private $height = -1;

    /** @var string */
    private $status = '';

    /**
     * @param int    $height
     * @param int    $branchLength
     * @param string $hash
     * @param string $status
     */
    private function __construct(int $height, int $branchLength, string $hash, string $status)
    {
        $this->height = $height;
        $this->branchLength = $branchLength;
        $this->hash = $hash;
        $this->status = $status;
    }

    /**
     * @param array $chainTipInfo
     *
     * @return ChainTip
     */
    public static function fromArray(array $chainTipInfo): self
    {
        return new static(
            $chainTipInfo['height'] ?? -1,
            $chainTipInfo['branchlen'] ?? -1,
            $chainTipInfo['hash'] ?? '',
            $chainTipInfo['status'] ?? ''
        );
    }

    /**
     * @return int
     */
    public function getBranchLength(): int
    {
        return $this->branchLength;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
