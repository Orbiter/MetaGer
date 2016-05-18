<!DOCTYPE html>
<html>

<head>
    <title>test - MetaGer</title>
    <link href="/css/bootstrap.css" rel="stylesheet" />
    <link href="/css/styleResultPage.css" rel="stylesheet" />
    <link href="/css/theme.css.php" rel="stylesheet" />
    <link href="/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="3316" name="p" />
    <meta content="test" name="q" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body id="resultBody">
    <div class="content-wrapper">
        <header id="research">
            <nav>
                <ul class="list-inline">
                    <li class="hidden-xs hidden-sm pull-left">
                        <div class="logo"><a href="/"><h1>MetaGer</h1></a>
                        </div>
                    </li>
                    <li class="visible-xs visible-sm pull-left">
                        <div class="logo"><a href="/"><h1>MG</h1></a>
                        </div>
                    </li>
                    <li class="pull-right">
                        <form method="get" action="/meta/meta.ger3" enctype="multipart/form-data" accept-charset="UTF-8" class="form" id="submitForm">
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" form="submitForm" id="eingabeTop" name="eingabe" placeholder="Suchbegriffe erweitern/verändern, oder völlig neue Suche:" tabindex="1" type="text" value="{{ $eingabe }}" />
                                <div class="input-group-addon">
                                    <button type='submit' form="submitForm" id='search'><span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            </div>
                            <input type='hidden' name='focus' value='web' form='submitForm' />
                            <input type='hidden' name='encoding' value='utf8' form='submitForm' />
                            <input type='hidden' name='lang' value='all' form='submitForm' />
                            <input type='hidden' name='mobile' value='0' form='submitForm' />
                        </form>
                    </li>
                </ul>
            </nav>
        </header>
        <ul class="nav nav-tabs" id="foki" role="tablist">
            <li class="active" data-loaded="1" id="webTabSelector" role="presentation"><a aria-controls="web" data-href="#web;out=results" href="#web"><span class='glyphicon glyphicon-globe'></span> <span class="hidden-xs">Web</span></a>
            </li>
            <li class="" data-loaded="0" id="bilderTabSelector" role="presentation"><a aria-controls="bilder" data-href="https://metager.de/meta/meta.ger3?focus=bilder&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results" href="https://metager.de/meta/meta.ger3?focus=bilder&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0"><span class='glyphicon glyphicon-picture'></span> <span class="hidden-xs">Bilder</span></a>
            </li>
            <li class="" data-loaded="0" id="nachrichtenTabSelector" role="presentation"><a aria-controls="nachrichten" data-href="https://metager.de/meta/meta.ger3?focus=nachrichten&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results" href="https://metager.de/meta/meta.ger3?focus=nachrichten&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0"><span class='glyphicon glyphicon-bullhorn'></span> <span class="hidden-xs">Nachrichten</span></a>
            </li>
            <li class="" data-loaded="0" id="wissenschaftTabSelector" role="presentation"><a aria-controls="wissenschaft" data-href="https://metager.de/meta/meta.ger3?focus=wissenschaft&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results" href="https://metager.de/meta/meta.ger3?focus=wissenschaft&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0"><span class='glyphicon glyphicon-file'></span> <span class="hidden-xs">Wissenschaft</span></a>
            </li>
            <li class="" data-loaded="0" id="produktsucheTabSelector" role="presentation"><a aria-controls="produktsuche" data-href="https://metager.de/meta/meta.ger3?focus=produktsuche&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results" href="https://metager.de/meta/meta.ger3?focus=produktsuche&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0"><span class='glyphicon glyphicon-shopping-cart'></span> <span class="hidden-xs">Produktsuche</span></a>
            </li>
        </ul>
        <div class="tab-content container-fluid">
            <div class="tab-pane active" data-focus="web" id="web" role="tabpanel">
                @if( sizeof($errors) > 0 )
                    <div class="alert alert-danger">
                        <ul>
                        @foreach($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
                @if( sizeof($warnings) > 0)
                    <div class="alert alert-warning">
                        <ul>
                        @foreach($warnings as $warning)
                            <li>{{ $warning }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-8">
                        @yield('results')
                    </div>
                    <div class="col-md-4" id="quicktips">
                       
                    </div>
                </div>
                <nav class="pager">
                    <ul class="pagination">
                        <li class="disabled"><a data-href="" href="#"><span aria-hidden="true">&laquo;</span></a>
                        </li>
                        <li class="active"><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=1" href="">1 <span class="sr-only">(current)</span></a>
                        </li>
                        <li class=""><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=2" href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;page=2">2 </a>
                        </li>
                        <li class=""><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=3" href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;page=3">3 </a>
                        </li>
                        <li class=""><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=4" href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;page=4">4 </a>
                        </li>
                        <li class=""><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=5" href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;page=5">5 </a>
                        </li>
                        <li class=""><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=6" href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;page=6">6 </a>
                        </li>
                        <li class=""><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=7" href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;page=7">7 </a>
                        </li>
                        <li class=""><a data-href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;out=results;page=2" href="https://metager.de/meta/meta.ger3?focus=web&amp;eingabe=test&amp;encoding=utf8&amp;lang=all&amp;mobile=0;page=2"><span aria-hidden="true">&raquo;</span></a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="tab-pane " data-focus="bilder" id="bilder" role="tabpanel">
                <div class="loader"><img alt="" src="/img/ajax-loader.gif" />
                </div>
            </div>
            <div class="tab-pane " data-focus="nachrichten" id="nachrichten" role="tabpanel">
                <div class="loader"><img alt="" src="/img/ajax-loader.gif" />
                </div>
            </div>
            <div class="tab-pane " data-focus="wissenschaft" id="wissenschaft" role="tabpanel">
                <div class="loader"><img alt="" src="/img/ajax-loader.gif" />
                </div>
            </div>
            <div class="tab-pane " data-focus="produktsuche" id="produktsuche" role="tabpanel">
                <div class="loader"><img alt="" src="/img/ajax-loader.gif" />
                </div>
            </div>
        </div>
    </div>
    <footer>
        <ul class="list-unstyled list-inline footer">
            <li class="left"><a class="btn btn-default" href="/">MetaGer-Startseite</a>
            </li>
            <li class="right"><a class="btn btn-default" href="/impressum/">Impressum</a>
            </li>
        </ul>
    </footer>
    <script src="/js/jquery.js" type="text/javascript"></script>
    <script src="/js/bootstrap.js" type="text/javascript"></script>
    <script src="/js/masonry.js" type="text/javascript"></script>
    <script src="/js/imagesloaded.js" type="text/javascript"></script>
    <script src="/js/scriptResultPage.js" type="text/javascript"></script>
    <!--[if lte IE 8]><script type="text/javascript" src="/js/html5shiv.min.js"></script><![endif]-->
</body>

</html>