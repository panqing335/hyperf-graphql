<?php

declare(strict_types=1);

namespace App\Support\Helper;

use Vtiful\Kernel\Excel;
use Vtiful\Kernel\Format;

class ExcelHelper
{
    public static function getTmpDir()
    {
        $tmp = ini_get('upload_tmp_dir');

        if ($tmp !== False && file_exists($tmp)) {
            return realpath($tmp);
        }

        return realpath(sys_get_temp_dir());
    }

    /**
     * 处理excel表格的header头
     * @param Excel $fileObject
     * @param array $header
     * @param array $headerStyle
     * @return Excel
     */
    public static function handleHeader(Excel $fileObject, array $header, array $headerStyle = [])
    {
        // 先设置头数据
        $fileObject->header($header);
        $fileHandle = $fileObject->getHandle();
        // 再设置样式 一个format对应一个resource
        foreach ($headerStyle as $key => $style) {
            [$start, $end] = explode(':', $key);
            $format = new Format($fileHandle);
            $resource = $format->background($style)->toResource();
            for ($i = (int)$start; $i <= (int)$end; $i++) {
                $fileObject->insertText(0, $i, $header[$i], '', $resource);
            }
        }

        return $fileObject;
    }
}
