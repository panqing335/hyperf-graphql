<?php


namespace App\Support\Helper;


class Num2RmbClass
{
    /**
     * 将数值金额转换为中文大写金额
     * @param $amount float 金额(支持到分)
     * @param $type   int   补整类型,0:到角补整;1:到元补整
     * @return mixed 中文大写金额
     */
    function getMoneyDaxie($amount, $type = 1)
    {
        // 判断输出的金额是否为数字或数字字符串
        if (!is_numeric($amount)) {
            return "要转换的金额只能为数字!";
        }

        // 金额为0,则直接输出"零元整"
        if ($amount == 0) {
            return "零元整";
        }

        // 金额不能为负数
        if ($amount < 0) {
            return "要转换的金额不能为负数!";
        }

        // 金额不能超过万亿,即12位
        if (strlen($amount) > 12) {
            return "要转换的金额不能为万亿及更高金额!";
        }

        // 预定义中文转换的数组
        $digital = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        // 预定义单位转换的数组
        $position = array('仟', '佰', '拾', '亿', '仟', '佰', '拾', '万', '仟', '佰', '拾', '圆');

        // 将金额的数值字符串拆分成数组
        $amountArr = explode('.', $amount);

        // 将整数位的数值字符串拆分成数组
        $integerArr = str_split($amountArr[0], 1);

        // 将整数部分替换成大写汉字
        $result = '';
        $integerArrLength = count($integerArr);     // 整数位数组的长度
        $positionLength = count($position);         // 单位数组的长度
        $zeroCount = 0;                             // 连续为0数量
        for ($i = 0; $i < $integerArrLength; $i++) {
            // 如果数值不为0,则正常转换
            if ($integerArr[$i] != 0) {
                // 如果前面数字为0需要增加一个零
                if ($zeroCount >= 1) {
                    $result .= $digital[0];
                }
                $result .= $digital[$integerArr[$i]] . $position[$positionLength - $integerArrLength + $i];
                $zeroCount = 0;
            } else {
                $zeroCount += 1;
                // 如果数值为0, 且单位是亿,万,元这三个的时候,则直接显示单位
                if (($positionLength - $integerArrLength + $i + 1) % 4 == 0) {
                    $result = $result . $position[$positionLength - $integerArrLength + $i];
                }
            }
        }

        // 如果小数位也要转换
        if ($type == 0) {
            // 将小数位的数值字符串拆分成数组
            $decimalArr = str_split($amountArr[1], 1);
            // 将角替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[0] != 0) {
                $result = $result . $digital[$decimalArr[0]] . '角';
            }
            // 如果角为0，分大于0 就将角为零角
            if ($decimalArr[0] == 0 && $decimalArr[1] != 0) {
                $result = $result . $digital[$decimalArr[0]] . '角';
            }
            // 将分替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[1] != 0) {
                $result = $result . $digital[$decimalArr[1]] . '分';
            }
            //如果角和分都为0，就加上整
            if ($decimalArr[0] == 0 && $decimalArr[1] == 0) {
                $result = $result . '整';
            }
        } else {
            $result = $result . '整';
        }
        return $result;
    }

    public function stringToArray($num)
    {
        $amountArr = explode('.', $num);
        $integerArr = str_split($amountArr[0], 1);
        $decimalArr = str_split($amountArr[1], 1);
        $arr = array_merge($integerArr, $decimalArr);
        krsort($arr);
        $arr = array_values($arr);
        return $arr;
    }

    public function pushRMB(array $arr)
    {
        return array_push($arr, "¥");
    }

}