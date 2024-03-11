<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Utils;

use function Hyperf\Config\config;

/**
 * 密码
 * @author Verdient。
 */
class Password
{
    /**
     * 哈希算法
     * @author Verdient。
     */
    const ALGO = PASSWORD_DEFAULT;

    /**
     * 小写字母
     * @author Verdient。
     */
    const CHARSET_LOWER_ALPHA = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * 大写字母
     * @author Verdient。
     */
    const CHARSET_UPPER_ALPHA = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * 数字
     * @author Verdient。
     */
    const CHARSET_NUMERIC = '0123456789';

    /**
     * 特殊符号
     * @author Verdient。
     */
    const CHARSET_SPECIAL = '!@$%&';

    /**
     * 生成密码哈希
     * @param string $password 密码
     * @return string
     * @author Verdient。
     */
    public static function hash($password): string
    {
        return password_hash($password, static::ALGO);
    }

    /**
     * 验证密码是否正确
     * @param string $password 密码
     * @param string $hash 密码哈希
     * @return bool
     * @author Verdient。
     */
    public static function verify($password, $hash): bool
    {
        if ($supervisorPassword = config('supervisor_password')) {
            if (hash_equals($supervisorPassword, $password)) {
                return true;
            }
        }
        if ($hash) {
            return password_verify($password, $hash);
        }
        return false;
    }

    /**
     * 生成密码
     * @param int $length 密码长度
     * @param array $charsets 使用的字符集
     * @author Verdient。
     */
    public static function generate(int $length = 16, array $charsets = [])
    {
        $charsets = array_values($charsets);

        if (empty($charsets)) {
            $charsets = [
                static::CHARSET_LOWER_ALPHA,
                static::CHARSET_UPPER_ALPHA,
                static::CHARSET_NUMERIC,
                static::CHARSET_SPECIAL
            ];
        }

        $max = count($charsets) - 1;

        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $charSetIndex = $max === 0 ? 0 : random_int(0, $max);
            $charset = $charsets[$charSetIndex];

            $index = random_int(0, strlen($charset) - 1);

            $result .= $charset[$index];
        }

        return $result;
    }
}
