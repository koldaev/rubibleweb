<?php
/**
 * Created by PhpStorm.
 * User: Maaa
 * Date: 29.01.15
 * Time: 22:06
 */
class SitemapGenerator {

    private $lang;

    private $yandex;

    private $numbertotranslit = array (
        1 => 'odin',
        2 => 'dva',
        3 => 'tri',
        4 => 'chetyre',
        5 => 'pyat',
        6 => 'shest',
        7 => 'sem',
        8 => 'vosem',
        9 => 'devyat',
        10 => 'desyat',
        11 => 'odinadcat',
        12 => 'dvenadcat',
        13 => 'trinadcat',
        14 => 'chetyrnadcat',
        15 => 'pyatnadcat',
        16 => 'shestnadcat',
        17 => 'semnadcat',
        18 => 'vosemnadcat',
        19 => 'devyatnadcat',
        20 => 'dvadcat',
        30 => 'tridcat',
        40 => 'sorok',
        50 => 'pyatdesyat',
        60 => 'shestdesyat',
        70 => 'semdesyat',
        80 => 'vosemdesyat',
        90 => 'devyanosto',
        100 => 'sto'
    );

    function __construct($l='ru') {
        $this->lang = $l;
        if($l == 'ru') {
            //$this->generator();
        }
    }

    public function getchaptername($numer) {
        return $this->totranslit($numer);
    }

    private function totranslit($numer) {
        $textnumber = $numer+"";
        $numbers = str_split($textnumber);
        //var_dump($numbers);
        //echo '<br>';
        $countnumbers = sizeof($numbers);
        if($countnumbers == 1) {
            $seonumber = $this->numbertotranslit[$numbers[0]];
        } else if ($countnumbers == 2) {
            if($numbers[1] < 1 || $numer < 21) {
                $seonumber = $this->numbertotranslit[$numer];
            } else {
                if($numbers[1] < 1) {
                    $seonumber = $this->numbertotranslit[$numbers[0]];
                } else {
                    $tmpnumber = (int)("".$numbers[0]."0");
                    $seonumber = $this->numbertotranslit[$tmpnumber].'-'.$this->numbertotranslit[$numbers[1]];
                }
            }
        } else if ($countnumbers == 3) {
            $seonumber = $this->numbertotranslit[100];
            if($numer == 100) {}
            else if($numbers[2] < 1 || $numer < 121) {
                $tmpnember = $this->numbertotranslit[(int)($numbers[1].$numbers[2])];
                $seonumber .= '-'.$tmpnember;
            } else {
                if($numbers[2] < 1) {
                    $seonumber = $this->numbertotranslit[$numbers[1]];
                } else {
                    $tmpnumber = (int)("".$numbers[1]."0");
                    $seonumber .= '-'.$this->numbertotranslit[$tmpnumber].'-'.$this->numbertotranslit[$numbers[2]];
                }
            }
        }
        return $seonumber;
    }

    public function generator() {

        $rootname = dirname(Yii::app()->request->scriptFile);

        $filename = (isset($this->yandex)) ? $rootname.'/yandexmap.xml' : $rootname.'/sitemap.xml';
        echo 'sitemap filename: '.$filename.'<br>';
        try {
            $fp = fopen($filename, 'w') or die("Unable to open file!");
            if (!$fp) {
                throw new Exception('File open failed.');
            }
        } catch ( Exception $e ) {
            var_dump($e->getMessage());
        }

        fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>');
        fwrite($fp, "\r\n");
        if(!$this->yandex) {
            fwrite($fp, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">');
        } else {
            fwrite($fp, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        }

        $domainurl = 'http://test.dobro.in';
        $connection = Yii::app()->db;
        $fullsqlbiblechapters = "SELECT * FROM rubible;";
        $rows = $connection->createCommand($fullsqlbiblechapters)->queryAll();
        foreach($rows as $r) {
            $biblename = $r['seo'];
            $countchapters = $r['chapters'];
            for($i=1;$i<=$countchapters;$i++) {
                fwrite($fp, '<url>');
                fwrite($fp, "\r\n");
                fwrite($fp, '<loc>');
                fwrite($fp, "\r\n");
                if ($i == 1) {
                    if($r['idbible'] != 1) {
                        $fullurl = $domainurl.'/'.$biblename.'/';
                        fwrite($fp, $fullurl);
                        fwrite($fp, "\r\n");
                        fwrite($fp, '</loc>');
                        fwrite($fp, "\r\n");
                    } else {
                        $fullurl = $domainurl;
                        fwrite($fp, $fullurl);
                        fwrite($fp, "\r\n");
                        fwrite($fp, '</loc>');
                        fwrite($fp, "\r\n");
                    }
                } else {
                    $seonumberus = $this->totranslit($i);
                    $fullurl = $domainurl.'/'.$biblename.'-glava-'.$seonumberus.'/';
                    fwrite($fp, $fullurl);
                    fwrite($fp, "\r\n");
                    fwrite($fp, '</loc>');
                    fwrite($fp, "\r\n");
                }
                fwrite($fp, '</url>');
                fwrite($fp, "\r\n");
            }
        }
        fwrite($fp, '</urlset>');

    }


}