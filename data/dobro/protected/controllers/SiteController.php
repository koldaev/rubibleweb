<?php

class SiteController extends Controller
{
	public $layout='column1';

	/**
	 * Declares class-based actions.
	 */

    public $oldtestamentmenu;
    public $newtestamentmenu;
    public $chapters;
    public $activetestament;
    public $activechapter;
    public $pagination;

    public $begin_nav;
    public $prev_nav;
    public $next_nav;
    public $end_nav;

    private $rustonumber = array (
        'odin' => 1,
        'dva' => 2,
        'tri' => 3,
        'chetyre' => 4,
        'pyat' => 5,
        'shest' => 6,
        'sem' => 7,
        'vosem' => 8,
        'devyat' => 9,
        'desyat' => 10,
        'odinadcat' => 11,
        'dvenadcat' => 12,
        'trinadcat' => 13,
        'chetyrnadcat' => 14,
        'pyatnadcat' => 15,
        'shestnadcat' => 16,
        'semnadcat' => 17,
        'vosemnadcat' => 18,
        'devyatnadcat' => 19,
        'dvadcat' => 20,
        'tridcat' => 30,
        'sorok' => 40,
        'pyatdesyat' => 50,
        'shestdesyat' => 60,
        'semdesyat' => 70,
        'vosemdesyat' => 80,
        'devyanosto' => 90,
        'sto' => 100,
    );

//$word = strtr('прочее', $translit); // транслитерация. Переменная $word получит значение 'prochee'
//$word = strtr('prochee', array_flip($translit)); // обратная транслитерация. Переменная $word получит значение 'прочее'

    public function actionindex() {

        $request = Yii::app()->request->requestUri;
        $countslashes = substr_count($request,"/");
        if($countslashes == 1) {
            if ($request != '/') throw new CHttpException(404, 'Not found');
        }

        $this->generatorbookmenu();

        $connection = Yii::app()->db;
        header('Content-Type: text/html; charset=utf-8');
        $seobiblename = $fullurl = (isset($_GET['bible'])) ? $_GET['bible'] : "";
        @$position = strpos($fullurl,'-glava-');
        if((int)$position > 0) {
            $seobiblename = substr($fullurl,0,$position);
            $seochapter = substr($fullurl,($position+7));
            $numberchapter = explode("-",$seochapter);
            $ch = 0;
            foreach($numberchapter as $steck) {
                $ch += $this->rustonumber[$steck];
            }
        }

        if($seobiblename == '') {
            $sqlbible = "select seo from rubible where idbible = 1";
            $biblenamedata = $connection->createCommand($sqlbible)->queryRow();
            $seobiblename = $biblenamedata['seo'];
            $this->activetestament = 'old';
        }

        if((int)$position > 0) {
            $sqlpoem = "select t.poem,t.poemtext from rutext t, rubible b where b.idbible = t.bible and b.seo = '".$seobiblename."' and t.chapter = ".$ch;
            $sqlmain = "select biblename, idbible from rubible where seo = '".$seobiblename."'";
            $name = $connection->createCommand($sqlmain)->queryRow();
            $this->pageTitle = $name['biblename'].'. Глава '.$ch;
            $this->activetestament = ((int)$name['idbible'] < 40) ? 'old' : 'new';
            $this->activechapter = $ch;
        } else {
            $sqlpoem = "select t.poem,t.poemtext from rutext t, rubible b where b.idbible = t.bible and b.seo = '".$seobiblename."' and t.chapter = 1";
            $sqlmain = "select idbible, biblename from rubible where seo = '".$seobiblename."'";
            $name = $connection->createCommand($sqlmain)->queryRow();
            $this->activetestament = ((int)$name['idbible'] < 40) ? 'old' : 'new';
            $this->pageTitle = $name['biblename'];
            $this->activechapter = 1;
        }


        //генерируем спсок глав/////////////
        $sqlchapters = "select chapters from rubible where seo = '".$seobiblename."'";
        $chaptersdata = $connection->createCommand($sqlchapters)->queryRow();
        $chapters = (int)$chaptersdata['chapters'];
        $this->chapters = '';
        $sitemap = new SitemapGenerator(); 
        //$this->pagination = '<ul class="pagination pagination-sm">';
        for($c=1;$c<=$chapters;$c++) {
            $chapterforlist = $sitemap->getchaptername($c);
            $activeclass = ($this->activechapter == $c) ? " class='active'" : "";
            $this->chapters .= '<li'.$activeclass.'><a href="/'.$seobiblename.'-glava-'.$chapterforlist.'/">Глава '.$c.'</a></li>';
            //$this->pagination .=  '<li><a href="/'.$seobiblename.'-glava-'.$chapterforlist.'/">'.$c.'</a></li>';
        }
        $this->genpagination($this->activechapter,($chapters+1),$seobiblename);
        //$this->pagination .= '</ul>';
        /////////////////////////////////////

        $rows = $connection->createCommand($sqlpoem)->queryAll();
        $text = '';
        foreach ($rows as $poem) {
            $text .= $poem['poem'].' '.$poem['poemtext'].'<br>';
        }

        $this->render('index', array('text' => $text));

    }

    private function generatorbookmenu() {
        $connection = Yii::app()->db;
        $sqlmenu = "select idbible, biblename, seo from rubible";
        $names = $connection->createCommand($sqlmenu)->queryAll();
        $this->oldtestamentmenu = '';
        $this->newtestamentmenu = '';
        foreach($names as $b) {
            if($b['idbible'] < 40) {
                $this->oldtestamentmenu .= '<li><a href="/'.$b['seo'].'/">'.$b['biblename'].'</a></li>';
            } else {
                $this->newtestamentmenu .= '<li><a href="/'.$b['seo'].'/">'.$b['biblename'].'</a></li>';
            }
        }
    }

    private function actionseo() {
        //можно передавать язык; по умолчанию - русский
        //$seo = new SeoGenerator("en");
        $seo = new SeoGenerator();
    }

    private function actionsitemap() {
        new SitemapGenerator();
    }

    private function genpagination($page,$countpages,$biblename) {
     $lipages = '';
    $sitemap = new SitemapGenerator();
    //в начало
        if($page > 2) {
        $chapterforlist = $sitemap->getchaptername($page);
            $this->begin_nav = '<li><a href="/'.$biblename.'/"><span class="glyphicon glyphicon-step-backward"></span></a></li>';
        } else {
            $this->begin_nav = '<li class="disabled"><a href=""><span class="glyphicon glyphicon-step-backward"></span></a></li>';
        }
//на предыдущую страницу
        if ($page > 1) {
            if($page != 2) {
            $chapterforlist = $sitemap->getchaptername((int)$page-1);
                $this->prev_nav = '<li><a href="/'.$biblename.'-glava-'.$chapterforlist.'/"><span class="glyphicon glyphicon-arrow-left"></span></a></li>';
            } else {
                $this->prev_nav = '<li><a href="/'.$biblename.'/"><span class="glyphicon glyphicon-arrow-left"></span></a></li>';
            }
        } else {
            $this->prev_nav = '<li class="disabled"><a style="text-decoration:none;color:gray;pointer-events:none;" href="/'.$biblename.'/"><span class="glyphicon glyphicon-arrow-left"></span></a></li>';
        }
//на следующую страницу
    if ($page < ($countpages-1)) {
        $chapterforlist = $sitemap->getchaptername($page+1);
        $this->next_nav = '<li><a href="/'.$biblename.'-glava-'.$chapterforlist.'/"><span class="glyphicon glyphicon-arrow-right"></span></a></li>';
    } else {
        $this->next_nav = '<li class="disabled"><a href=""><span class="glyphicon glyphicon-arrow-right"></span></a></li>';
    }
//в конец
    if ($page < ($countpages-2) && $countpages > 1) {
        $chapterforlist = $sitemap->getchaptername($countpages-1);
        $this->end_nav = '<li><a  href="/'.$biblename.'-glava-'.$chapterforlist.'/"><span class="glyphicon glyphicon-step-forward"></span></a></li>';
    } else {
        $this->end_nav = '<li class="disabled"><a href=""><span class="glyphicon glyphicon-step-forward"></span></a></li>';
    }
}

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else {
	        	//$this->render('error', $error);
                var_dump($error);
                //потом нужно будет только выводить ниже
                //echo '404';
            }
	    }
	}


	/**
	 * Displays the contact page
	 */

}
