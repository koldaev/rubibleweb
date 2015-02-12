<?php $this->beginContent('/layouts/main'); ?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown <?php if($this->activetestament == 'old') { echo " active"; } ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ветхий Завет<span class="caret"></span></a>
                    <ul class="dropdown-menu scrollable-menu"" role="menu">
                          <?php echo $this->oldtestamentmenu ?>
<!--                        <li class="divider"></li>-->
                    </ul>
                </li>
            <li class="dropdown <?php if($this->activetestament == 'new') { echo " active"; } ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Новый Завет<span class="caret"></span></a>
                        <ul class="dropdown-menu scrollable-menu" role="menu">
                            <?php echo $this->newtestamentmenu ?>
                        </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Глава<span class="caret"></span></a>
                    <ul class="dropdown-menu scrollable-menu" role="menu">
                        <?php echo $this->chapters ?>
                    </ul>
                </li>
<!--                <li><a href="#search">Поиск</a></li>-->
<!--                <li><a href="pages"><?php /*echo $this->pagination */?></a></li>-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                echo $this->begin_nav;
                echo $this->prev_nav;
                echo $this->next_nav;
                echo $this->end_nav;
                ?>
            </ul>

        </div>
    </div>
</nav>
<div class="container">
    <div class="starter-template">
		<?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>