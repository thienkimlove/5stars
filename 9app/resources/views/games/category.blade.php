@extends('main')

@section('search')
    <section class="content">
        <div class="list-wrap">
            <ul class="list">
                @foreach ($games as $game)
                <li class="item">
                    <a class="inner j-press" href="{{url('android-'.$game->type.'/'. $game->slug)}}">
                        <div class="pic">
                            <img class="" src="{{url('images/avatars/'. $game->icon)}}" alt="icon">
                            <span class="badge badge-hot">HOT</span>
                        </div>
                        <div class="info">
                            <p class="name">{{$game->title}}</p>
                            <div class="mid">
                                <span class="star-small s9"></span>
                                <span>(568)</span>
                            </div>
                            <p class="other">
                                <span class="type">{{$category->name}}</span>
                                <span class="sp">|</span>
                                <span class="size">{{$game->total}}</span>
                            </p>
                        </div>
                    </a>
                    <a class="btn-download j-press" href="{{$game->download}}">Download</a>
                </li>
                @endforeach
             </ul>
        </div>
    </section>
    {!! with(new \App\Pagination\AcmesPresenter($games))->render() !!}
@stop