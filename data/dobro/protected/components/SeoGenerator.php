<?php
/**
 * Created by PhpStorm.
 * User: Maaa
 * Date: 29.01.15
 * Time: 22:06
 */
class SeoGenerator {

    private $lang;

    function __construct($l='ru') {
        $this->lang = $l;
        if($l == 'ru') {
            $this->seo();
        }
    }

    private function seo() {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM '.$this->lang.'bible order by idbible asc';
        $rows = $connection->createCommand($sql)->queryAll();
        header('Content-Type: text/html; charset=utf-8');
        foreach ($rows as $bible) {
            echo mb_strtolower($bible['biblename'],'UTF-8').'<br>';
            $text = mb_strtolower($bible['biblename'],'UTF-8');
            $seotext = $this->rus2translit($text);
            $seovalue = urlencode($seotext);
            $sqlupdate = 'UPDATE '.$this->lang.'bible SET seo = "'.$seovalue.'" where idbible = '.$bible['idbible'];
            $connection->createCommand($sqlupdate)->execute();
        }
    }

    private function rus2translit($string)
    {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => "",  'ы' => 'y',   'ъ' => "",
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => "",  'Ы' => 'Y',   'Ъ' => "",
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            ' ' => '-', ' - '=> '-', ',' => '', '?' => '', ':' => '-', ': ' => '-'
        );
        return strtr($string, $converter);
    }

}