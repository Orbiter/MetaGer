<!DOCTYPE html>
<html>

<head>
    <title>{{ $metager->getQ() }} - MetaGer</title>
    <link href="/css/bootstrap.css" rel="stylesheet" />
    <link href="/css/styleResultPage.css" rel="stylesheet" />
    @if( isset($mobile) && $mobile )
    <link href="/css/styleResultPageMobile.css" rel="stylesheet" />
    @endif
    <link href="/css/theme.css.php" rel="stylesheet" />
    <link href="/favicon.ico" rel="icon" type="image/x-icon" />
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="{{ getmypid() }}" name="p" />
    <meta content="{{ $eingabe }}" name="q" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body id="resultBody">
    
    @if( isset($header) )
    @include('layouts.researchandtabs')
    @else
    <div class="tab-content container-fluid">
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
    @yield('results')
    </div>
    @endif
        
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