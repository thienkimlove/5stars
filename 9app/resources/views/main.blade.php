<!DOCTYPE html>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <link rel="shortcut icon" type="image/x-icon" href="{{ url('files/favicon.ico') }}">

    <title>Free Android Apps Download | Best Apps for Android Mobile Phone - AppForAndroidPhone</title>
    <meta property="og:type" content="website">
    <meta property="fb:app_id" content="579103738859108">
    <meta property="og:title" content="Free Android Apps Download | Best Apps for Android Mobile Phone - AppForAndroidPhone">
    <meta property="og:image" content="{{ url('files/9apps_present.png')  }}">
    <meta name="description" content="appforandroidphone.com supports free android apps apk download. Thousands of top best android apps at AppForAndroidPhone! Play free apps for android mobile phone now!">
    <meta name="keywords" content="android apps,android apps download,free android apps,best android apps,apps for android,AppForAndroidPhone">

    <link href="{{ url('css/'.$css.'.css')  }}" rel="stylesheet">

    </head>
<body data-ng-app="Application">
@if (empty($pageSearch) && empty($page))
<section id="mainContainer" class="main-container">
    <!-- Header begin -->
    @include('partials.header')
    <!-- Header end -->
    <!-- Nav begin -->
    <nav class="nav has-dropdown">
        <ul class="nav-list">
            <li class="item {{ !empty($pageHome)? 'active' : '' }}">
                <a href="{{url('/')}}">HOME</a>
            </li>
            <li class="item {{ !empty($pageApp)? 'active' : '' }}">
                <a href="{{url('android-apps')}}">APPS</a>
            </li>
            <li class="item {{ !empty($pageGame)? 'active' : '' }}">
                <a href="{{url('android-games')}}">GAMES</a>
            </li>
        </ul>
    </nav>
    <!-- Nav end -->

    @yield('content')

    <!-- Footer begin -->
    @include('footer')
    <!-- Footer end -->

</section>

<section id="searchWrap" class="search-wrap hide">
    <header class="header header-back">
        <div id="hideSearch" class="header-l">
            <span class="go-back"><i></i></span>
            <span class="logo"><img src="{{url('files/logo.png')}}" alt="AppForAndroidPhone"></span>
        </div>
        <div class="header-r">
            <a class="icon-home" href="{{url('/')}}"></a>
        </div>
    </header>

    <section class="search-content">
        <!-- search-combox begin -->
        @include('partials.endsearch')
        <!-- search-combox end -->
        <div class="hot-keywords">
            <h1>Weekly Hot Words</h1>
            <ul>
                @foreach ($hotTags as $tag)
                    <li>
                        <a class="j-press" href="{{url('search/tag-', str_replace(' ', '+', $tag['name']))}}">{{ $tag['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
</section>

@else
  @include('partials.searchHeader', ['page' => $page])
  @yield('search')
  @include('footer')
@endif
<script>
    var Config = {};
    Config.url = '{{ url('/') }}';
    Config.keyword = '{{ !empty($term) ? $term : $hotTags[0]['name'] }}';
</script>
<script type="text/javascript" src="{{url('js/output.js')}}"></script>
<script type="text/javascript" src="{{url('js/custom.js')}}"></script>
</body>
</html>