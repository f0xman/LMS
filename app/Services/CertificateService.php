<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Imagick;

class CertificateService
{

    /** @var string */
    protected $fontNormal;

    /** @var string */
    protected $fontBold;

    /** @var string */
    protected $storagePath;

    public function __construct()
    {
      $this->fontNormal = public_path().'/uploads/fonts/MyriadPro.otf';
      $this->fontBold = public_path().'/uploads/fonts/MyriadProBold.otf';
      $this->storagePath = public_path().'/uploads/certificates/'.Auth::id().'/';
    }


     /**
     * Generate and Save certificate from JPG to PDF
     *
     * @param:
     *    $certificateImg - шаблон сертификата в jpg,
     *    $name - имя студента (латиница не работает, хз),
     *    $courseTitle - название курса,
     *    $courseId - id курса для названия pdf.
     * @return boolean
     */

    public function generateSertificate($seminar, String $name) {

        ///// проверка сертификата у курса и берем имя препода
        if($seminar && $seminar->teacher->certificate!='') {

            $name = trim($name);
            $seminar_title = '"'.$seminar->title.'"';
            $cert = 'uploads/'.$seminar->teacher->certificate;

            ////// тут генерим сертификат, заворачиваем в пдф
            return $this->saveBaseCertificate($cert, $name, $seminar_title, $seminar->id);            

        } else {
            return redirect()->back()->with("error","Не удалось сгенерировать сертификат.
                                                        Напишите пожалуйста на почту <a href=\"mailto:info@mclass.pro\">info@mclass.pro</a>, скопировав в тело письма ошибку:
                                                        <b>errorGenerateCertificate(id#".Auth::id().", courseId#".$seminar->id.", teacherId#".$seminar->teacher->id.")</b>
                                                        ");
        }

    }


    private function saveBaseCertificate(String $certificateImg, String $name, String $courseTitle, String $courseId)
    {
        //// Шаблон сертификата
        $im = imagecreatefromjpeg($certificateImg);
        $image_width = imagesx($im);  
        $image_height = imagesy($im);

        /// Цвет текста
        $purple = imagecolorallocate($im, 72, 42, 92);

        /////////////////////////////////////////ФИО//////////////////////////////////////
        $font_size = (mb_strlen($name)>25) ? 100 : 120;
        $angle = 0;
        $text = $name;

        // Размер текста 
        $text_box = imagettfbbox($font_size,$angle,$this->fontBold,$text);
        // Get your Text Width and Height
        $text_width = $text_box[2]-$text_box[0];
        $text_height = $text_box[7]-$text_box[1];
        // Calculate coordinates of the text
        $x = ($image_width/2) - ($text_width/2);
        $y = ($image_height/2) - ($text_height/2);

        // Наносим текст
        imagettftext($im, $font_size, 0, $x, $y-40, $purple, $this->fontBold, $text); 
        /////////////////////////////////////////////////////////////////////////////////


        /////////////////////////////////////////НАЗВАНИЕ КУРСА//////////////////////////////////////
        $font_size = 75;
        $angle = 0;
        $text = $courseTitle;

        // Размер текста 
        $text_box = imagettfbbox($font_size,$angle,$this->fontBold,$text);
        $text_width = $text_box[2]-$text_box[0];
        $text_height = $text_box[7]-$text_box[1];
        $y = ($image_height/2) - ($text_height/2) + 340;

        if ($text_box[2] > $image_width-200) { ///////// Длинный текст, разобьем строку пополам

            //$middle = strrpos(substr($text, 0, floor(strlen($text) / 2)), ' ') + 1; //// Больше текста во второй строке
            $middle = strpos($text, ' ', floor(strlen($text)*0.5)); 
            $string1 = substr($text, 0, $middle); 
            $string2 = substr($text, $middle); 

            $text_box = imagettfbbox($font_size,$angle,$this->fontBold,$string1);
            $text_width = $text_box[2]-$text_box[0];
            $x = ($image_width/2) - ($text_width/2);
            imagettftext($im, $font_size, 0, $x, $y, $purple, $this->fontBold, $string1);

            $text_box = imagettfbbox($font_size,$angle,$this->fontBold,$string2);
            $text_width = $text_box[2]-$text_box[0];
            $x = ($image_width/2) - ($text_width/2);
            imagettftext($im, $font_size, 0, $x, $y+100, $purple, $this->fontBold, $string2);
            
        } else {
            $x = ($image_width/2) - ($text_width/2);
            imagettftext($im, $font_size, 0, $x, $y, $purple, $this->fontBold, $text); 
            /////////////////////////////////////////////////////////////////////////////////
        }


        /////////////////////////////////////////ДАТА//////////////////////////////////////
        $font_size = 35;
        $angle = 0;
        $text = date("d.m.Y");
        
        // Наносим текст
        imagettftext($im, $font_size, 0, 2190, 2190, $purple, $this->fontNormal, $text); 
        /////////////////////////////////////////////////////////////////////////////////


        /// Сохраняем
        if (!file_exists($this->storagePath))
            mkdir($this->storagePath, 0777, true);

        $jpg = $this->storagePath.'cert.jpg';
        $pdf = $this->storagePath.$courseId.'.pdf';

        imagejpeg($im, $jpg, $quality = 100);

        $image = new Imagick($jpg);
        $image->setImageFormat('pdf');

        if($image->writeImage($pdf)) {
            unlink($jpg);
            return redirect()->back()->with('success', 'Сертификат успешно выписан!');
        }
        else {
            return redirect()->back()->with("error","Не удалось сгенерировать сертификат.
                                        Напишите пожалуйста на почту <a href=\"mailto:info@mclass.pro\">info@mclass.pro</a>, скопировав в тело письма ошибку:
                                        <b>errorSaveBaseCertificate(id#".Auth::id().", courseId#".$course->id.", teacherId#".$course->teacher->id.")</b>
                                        ");
        }

    }


}
