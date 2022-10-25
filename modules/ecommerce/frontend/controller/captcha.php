<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\ecommerce\frontend\controller\session;

/**
 * Captcha controller
 *
 * @author Dani Gilabert
 * 
 */
class captcha
{
    
    public function getCaptcha()
    {
        $value = session::getSessionVar('ecommerce-captcha');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setCaptcha($value)
    {
        session::setSessionVar('ecommerce-captcha', $value);
    }
    
    public function flush()
    {
        $this->setCaptcha('');
    }

    public function renderCaptcha() 
    {
        $captcha = '';

        for ($i = 0; $i < 5; $i++) {
            $captcha .= chr(rand(97, 122));
        }
        $captcha = strtoupper($captcha);

        // Set captcha
        $this->setCaptcha($captcha);

        $dir = 'res/fonts/';

        $image = imagecreatetruecolor(165, 50);

        // random number for font style
        $num = rand(1, 2);
        switch ($num)
        {
            case 1:
                $font = "Capture it 2.ttf";
                break;
            case 2:
                $font = "Molot.otf";
                break;
            case 3:
                $font = "PlAGuEdEaTH.otf";
                break;
            case 4:
                $font = "Walkway Black RevOblique.otf";
                break;
            case 5:
                $font = "Walkway rounded.otf";
                break;
            default:
                $font = "YoungDantes_by_laura_kristen.otf";
                break;
        }

        // random number for font color
        $num2 = rand(1, 2);
        if($num2==1)
        {
            $color = imagecolorallocate($image, 113, 193, 217);
        }
        else
        {
            $color = imagecolorallocate($image, 163, 197, 82);
        }

        $white = imagecolorallocate($image, 255, 255, 255); // background color white
        imagefilledrectangle($image,0,0,399,99,$white);
        imagecolortransparent($image, $white);

        imagettftext ($image, 30, 0, 10, 40, $color, $dir.$font, $captcha);

        header("Content-type: image/png");
        imagepng($image);        
    }
    
}