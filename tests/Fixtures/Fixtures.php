<?php

namespace GuldenPHP\Tests\Fixtures;

class Fixtures
{
    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function account(array $overrides = []): array
    {
        return self::extend($overrides, [
            'UUID' => self::randomUuid(),
            'label' => 'original-' . rand(0, 1000),
            'type' => 'HD',
            'HDindex' => rand(12345678, 234567890),
        ]);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function block(array $overrides = []): array
    {
        return self::extend($overrides, [
            'hash' => self::randomHash(),
            'confirmations' => 1,
            'strippedsize' => 242,
            'size' => 242,
            'weight' => 968,
            'height' => 598598,
            'version' => 536870912,
            'versionHex' => '20000000',
            'merkleroot' => self::randomHash(),
            'tx' =>
                array(
                    0 => self::randomHash(),
                ),
            'time' => 1505664899,
            'mediantime' => 1505664829,
            'nonce' => 13063403,
            'bits' => '1b08dc67',
            'difficulty' => 7395.936636295851,
            'chainwork' => '0000000000000000000000000000000000000000000000001810ea05b4554140',
            'previousblockhash' => self::randomHash(),
        ]);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function blockChainInfo(array $overrides = []): array
    {
        return self::extend($overrides, [
            'chain' => 'main',
            'blocks' => 598593,
            'headers' => 598593,
            'bestblockhash' => self::randomHash(),
            'difficulty' => 3947.454692845155,
            'mediantime' => 1505664457,
            'verificationprogress' => 0.9999947846293735,
            'chainwork' => '000000000000000000000000000000000000000000000000181088af54992ff1',
            'pruned' => false,
            'softforks' => [
                [
                    'id' => 'bip34',
                    'version' => 2,
                    'enforce' => [
                        'status' => true,
                        'found' => 1000,
                        'required' => 750,
                        'window' => 1000,
                    ],
                    'reject' => [
                        'status' => true,
                        'found' => 1000,
                        'required' => 950,
                        'window' => 1000,
                    ]
                ],
                [
                    'id' => 'bip66',
                    'version' => 3,
                    'enforce' => [
                        'status' => true,
                        'found' => 1000,
                        'required' => 750,
                        'window' => 1000,
                    ],
                    'reject' => [
                        'status' => true,
                        'found' => 1000,
                        'required' => 950,
                        'window' => 1000,
                    ]
                ],
                [
                    'id' => 'bip65',
                    'version' => 4,
                    'enforce' => [
                        'status' => true,
                        'found' => 1000,
                        'required' => 750,
                        'window' => 1000,
                    ],
                    'reject' => [
                        'status' => true,
                        'found' => 1000,
                        'required' => 950,
                        'window' => 1000,
                    ]
                ]
            ],
            'bip9_softforks' => [
                'csv' => [
                    'status' => 'active',
                    'startTime' => 1462060800,
                    'timeout' => 1493596800,
                ]
            ]
        ]);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function chainTip(array $overrides = []): array
    {
        return self::extend($overrides, [
            'height' => 598598,
            'hash' => self::randomHash(),
            'branchlen' => 0,
            'status' => 'active'
        ]);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function mempoolEntry(array $overrides = []): array
    {
        return self::extend($overrides, [
            'size' => 226,
            'fee' => 0.059165,
            'modifiedfee' => 0.059165,
            'time' => 1505589284,
            'height' => 598091,
            'startingpriority' => 15908843681641.03,
            'currentpriority' => 15908843681641.03,
            'descendantcount' => 1,
            'descendantsize' => 226,
            'descendantfees' => 5916500,
            'ancestorcount' => 1,
            'ancestorsize' => 226,
            'ancestorfees' => 5916500,
            'depends' => []
        ]);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function mempoolInfo(array $overrides = []): array
    {
        return self::extend($overrides, [
            'size' => 1,
            'bytes' => 373,
            'usage' => 1424,
            'maxmempool' => 300000000,
            'mempoolminfee' => 0.0
        ]);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function nodeInfo(array $overrides = []): array
    {
        return self::extend($overrides, [
            'version' => 1060401,
            'protocolversion' => 70014,
            'walletversion' => 60000,
            'balance' => 0.0,
            'blocks' => 598602,
            'timeoffset' => 0,
            'connections' => 8,
            'proxy' => '',
            'difficulty' => 7666.619826921429,
            'testnet' => false,
            'keypoololdest' => 1505064116,
            'keypoolsize' => 42,
            'mininput' => 1.0E-5,
            'paytxfee' => 0.0,
            'relayfee' => 1.0E-5,
            'errors' => '',
        ]);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public static function randomAddress(int $length = 35)
    {
        $length = $length <= 35 || $length >= 26 ? $length : 26;

        $letters = array_merge(
            range('a', 'k'),
            range('m', 'z'),
            range('A', 'H'),
            range('J', 'N'),
            range('P', 'Z'),
            range(1, 9)
        );

        return 'G' . implode(array_map(function () use ($letters) {
                return (string)$letters[array_rand($letters)];
            }, range(1, $length)));
    }

    /**
     * @return string
     */
    public static function randomHash(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * @return string
     */
    public static function randomUuid(): string
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function simplifiedAccount(array $overrides = []): array
    {
        return self::extend($overrides, [
            self::randomUuid(),
            'original-' . rand(0, 1000)
        ]);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    public static function walletInfo(array $overrides = []): array
    {
        return self::extend($overrides, [
            'walletversion' => 60000,
            'balance' => 0.0,
            'unconfirmed_balance' => 0.0,
            'immature_balance' => 0.0,
            'txcount' => 0,
            'keypoololdest' => 1505064116,
            'paytxfee' => 0.0
        ]);
    }

    /**
     * @param array $input
     * @param array $fixture
     *
     * @return array
     */
    private static function extend(array $input, array $fixture): array
    {
        return array_merge($fixture, $input);
    }
}
