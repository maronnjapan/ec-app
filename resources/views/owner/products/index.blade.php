<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('製品管理') }}
        </h2>
    </x-slot>
    <div class="content-1column">
        <div class="content-1column-wrapper">
            <form action="{{route('owner.products.post')}}" method="POST" class="form">
                @csrf
                <div class="form__item">
                    <input type="text" name="name" id="name">
                    <button type="submit" class="btn">送信</button>
                    <a href="{{route('owner.products.index')}}" class="btn">クリア</a>
                </div>
            </form>
        </div>
        <div class="content-1column-wrapper">
            <a href="{{route('owner.products.create')}}" class="btn">新規作成</a>
        </div>
        <div class="content-1column-wrapper">
            <div class="cards cards--col3">
                @foreach($items as $key => $item)
                <div class="cards__item">
                    <div class="card">
                        <a href="{{route('owner.products.edit',['product' => $item->id])}}" class="card--link">
                            <figure class="card__img-wrapper">
                                <img src="{{asset('storage/images/owner/product/'.$item->id.'/'.$item->getFirstImageModel($item->id)->getFilePath())}}" alt="" class="card__img">
                            </figure>
                        </a>
                        <div class="card__body">
                            <a href="{{route('owner.products.edit',['product' => $item->id])}}" class="card--link">
                                <h3 class="card__title">{{$item->name}}</h3>
                            </a>
                            <p class="card__txt">
                                @foreach($item->productTag as $key => $tag)
                                <a href="{{route('owner.products.index',['tag' => $tag->id])}}" class="rounded-label">{{ $tag->name }}</a>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>