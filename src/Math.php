<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Utils;

/**
 * 数学计算库
 * @author Verdient。
 */
class Math
{
    /**
     * 加法
     * @param string|int|float $left 左边的数字
     * @param string|int|float $right 左边的数字
     * @param int $scale 小数点位数
     * @return string
     * @author Verdient。
     */
    public static function add($left, $right, $scale = 0)
    {
        return bcadd(trim((string) $left), trim((string) $right), $scale);
    }

    /**
     * 减法
     * @param string|int|float $left 左边的数字
     * @param string|int|float $right 左边的数字
     * @param int $scale 小数点位数
     * @return string
     * @author Verdient。
     */
    public static function sub($left, $right, $scale = 0)
    {
        return bcsub(trim((string) $left), trim((string) $right), $scale);
    }

    /**
     * 乘法
     * @param string|int|float $left 左边的数字
     * @param string|int|float $right 左边的数字
     * @param int $scale 小数点位数
     * @return string
     * @author Verdient。
     */
    public static function mul($left, $right, $scale = 0)
    {
        return bcmul(trim((string) $left), trim((string) $right), $scale);
    }

    /**
     * 除法
     * @param string|int|float $left 左边的数字
     * @param string|int|float $right 左边的数字
     * @param int $scale 小数点位数
     * @return string
     * @author Verdient。
     */
    public static function div($left, $right, $scale = 0)
    {
        return bcdiv(trim((string) $left), trim((string) $right), $scale);
    }

    /**
     * 比较两个数字的大小
     * @param string|int|float $left 左边的数字
     * @param string|int|float $right 左边的数字
     * @param int $scale 小数点位数
     * @return int
     * @author Verdient。
     */
    public static function comp($left, $right, $scale = 0)
    {
        return bccomp(trim((string) $left), trim((string) $right), $scale);
    }

    /**
     * 拆分
     * @param string $amount 金额
     * @param int $number 拆分的份数
     * @param int $scale 小数点位数
     * @return array
     * @author Verdient。
     */
    public static function split($amount, $number, $scale): array
    {
        if ($number <= 0) {
            return [];
        }
        if ($number == 1) {
            return [Math::add($amount, 0, 2)];
        }
        $splitdAmount = static::div($amount, $number, $scale);
        $result = array_fill(0, $number, $splitdAmount);
        $total = static::mul($splitdAmount, $number, $scale);
        if (static::comp($amount, $total, $scale) !== 0) {
            $result[$number - 1] = static::add($result[$number - 1], static::sub($amount, $total, $scale), $scale);
        }
        return $result;
    }

    /**
     * 按比例拆分
     * @param string $amount 金额
     * @param array $ratios 比例集合
     * @param int $scale 小数点位数
     * @return array
     * @author Verdient。
     */
    public static function splitRatio($amount, $ratios, $scale): array
    {
        $result = [];
        $sumRatios = 0;
        foreach ($ratios as $index => $ratio) {
            $ratio = Math::add($ratio, 0, $scale);
            $sumRatios = Math::add($sumRatios, $ratio, $scale);
            $result[$index] = $ratio;
        }
        if (static::comp($sumRatios, $amount, $scale) === 0) {
            return $result;
        }
        $result = [];
        $total = 0;
        $lastIndex = array_key_last($ratios);
        $maxRatio = $ratios[$lastIndex];
        $maxIndex = $lastIndex;
        foreach ($ratios as $index => $ratio) {
            if ($ratio > $maxRatio) {
                $maxRatio = $ratio;
                $maxIndex = $index;
            }
            $total += $ratio;
        }
        $realTotal = 0;
        foreach ($ratios as $index => $ratio) {
            $number = static::mul(($ratio / $total), $amount, $scale);
            $result[$index] = $number;
            $realTotal = static::add($realTotal, $number, $scale);
        }
        if (static::comp($amount, $realTotal, $scale) !== 0) {
            $result[$maxIndex] = static::add($result[$maxIndex], static::sub($amount, $realTotal, $scale), $scale);
        }
        return $result;
    }

    /**
     * 获取一组数字的中位数
     * @param array $numbers 数字的集合
     * @param int $scale 小数点位数
     * @author Verdient。
     */
    public static function median($numbers, $scale): string
    {
        if (empty($numbers)) {
            return number_format((float) 0, $scale);
        }
        sort($numbers);
        $count = count($numbers);
        $index = floor($count / 2);
        return ($count % 2) === 0 ? static::div(static::add($numbers[$index], $numbers[$index - 1], 2), 2, 2) : number_format((float) $numbers[$index], $scale);
    }

    /**
     * 向上取整
     * @param int|double|float|string $number 数字
     * @return int
     * @author Verdient。
     */
    public static function ceil($number)
    {
        return (int) ceil((float) $number);
    }
}
