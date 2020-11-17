<?php
declare(strict_types=1);

namespace App\Support\Helper;

class Img
{
    protected $image  = null;
    protected $width  = 0;
    protected $height = 0;

    /**
     * 初始化一个新的图像，可指定宽度、高度及背景色
     *
     * @param int   $width
     * @param int   $height
     * @param array $backColor #这是一个rgb颜色的数组或null值
     */
    public function __construct($width = 500, $height = 500, $backColor = [255, 255, 255])
    {
        $this->width  = $width;
        $this->height = $height;
        $this->image  = imagecreatetruecolor($width, $height);
        $color        = imagecolorallocate($this->image, $backColor[0], $backColor[1], $backColor[2]);
        imagefill($this->image, 0, 0, $color);
    }

    /**
     * 在图像上贴一张图片
     *
     * @param      $maskImage
     * @param int  $_x
     * @param int  $_y
     * @param null $width
     * @param null $height
     *
     * @return $this
     */
    public function addImage($maskImage, $_x = 0, $_y = 0, $width = null, $height = null)
    {
        if (gettype($maskImage) != 'resource') {
            $maskImage = imagecreatefromstring(file_get_contents($maskImage));
        }
        $maskImageWidth  = imagesx($maskImage);
        $maskImageHeight = imagesy($maskImage);
        $width           = $width == null ? $maskImageWidth : $width;
        $height          = $height == null ? $maskImageHeight : $height;
        imagecopyresampled($this->image, $maskImage, $_x, $_y, 0, 0, $width, $height, $maskImageWidth,
            $maskImageHeight);
        return $this;
    }

    /**
     * @param $imgpath
     * 将图片切成圆角
     *
     * @return resource
     * #author: PanQing
     * #Time: 2019/4/26   4:24 PM
     */
    public function yuanjiao($imgpath)
    {
        $ext     = pathinfo($imgpath);
        $src_img = null;
        if (isset($ext['extension'])) {
            switch ($ext['extension']) {
                case 'jpg':
                    $src_img = imagecreatefromjpeg($imgpath);
                    break;
                case 'png':
                    $src_img = imagecreatefrompng($imgpath);
                    break;
            }
        } else {
            $src_img = $imgpath;
        }

        $wh  = getimagesize($imgpath);
        $w   = $wh[0];
        $h   = $wh[1];
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        return $img;
    }

    /**
     * 在图像上增加文字
     *
     * @param       $text
     * @param       $fontSize
     * @param array $fontColor #这是一个rgb颜色的数组
     * @param int   $_x
     * @param int   $_y
     * @return $this
     */
    public function addText($text, $fontSize, $fontColor = [0, 0, 0], $_x = 0, $_y = 0)
    {
        $im    = imagecreatetruecolor(1, 1);
        $color = imagecolorexact($im, $fontColor[0], $fontColor[1], $fontColor[2]);
        $font = BASE_PATH.'/storage/font/songti_e.ttf';
        $verb  = $fontSize;
        imagefttext($this->image, $fontSize, 0, $_x, $_y + ($verb), $color, $font, $text);
        return $this;
    }

    /**
     * 输出图像
     */
    public function render()
    {
        imagepng($this->image, 'storage/image/demo.png');
        imagedestroy($this->image);
    }

    public function savePng($filename)
    {
        imagepng($this->image, $filename);
        imagedestroy($this->image);
    }

    /**
     * 将图像转换为圆型
     *
     * @param $srcImage
     *
     * @return resource
     */
    public function roundImage($srcImage)
    {
        if (gettype($srcImage) != 'resource') {
            $srcImage = imagecreatefromstring(file_get_contents($srcImage));
        }
        $w   = imagesx($srcImage);
        $h   = imagesy($srcImage);
        $img = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r = $w / 2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($srcImage, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        return $img;
    }
}