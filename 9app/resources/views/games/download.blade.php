@extends('main')

@section('search')
    <section class="content">
        <div class="main-info">
            <div class="text">
                <p class="name">Download free "{{$game->title}}"</p>
            </div>
        </div>


        <div class="panel panel-info">
            <h1 class="panel-title">Link download</h1>
            <div class="panel-bd">
            @if (strpos($download,  'https://play.google.com/store/apps/details?id=') !== false)

                <p>We can not get direct download link free for this, please go to :</p>
                <div class="panel-ft">
                    <a href="javascript:void(0)" rel="nofollow" onclick="window.open('{{ $download }}')" class="btn btn-download j-press" >Google Store</a>
                </div>

            @else
            <!-- Have tow buttons -->
              <div class="panel-ft">
                 <a rel="nofollow" href="{{ $download  }}" class="btn btn-download j-press">Direct Download</a>
              </div>
            @endif
            </div>
        </div>
        <div class="panel panel-reco">
            <h1 class="panel-title">You may also like</h1>
            <div class="panel-bd">
                <ul class="reco-list">
                    @foreach($relates as $relate)
                        <li class="item">
                            <a class="inner" href="{{url( 'android-'.$relate->type.'/'.$relate->slug)}}">
                                <img class="lazy" src="{{url('images/avatars/', $relate->icon)}}" alt="Picture">
                                <p class="name">{{$relate->title}}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@stop
