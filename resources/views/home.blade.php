<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="content-1column-wrapper">
                        <div id="app">
                            <vue-simple-suggest></vue-simple-suggest>
                        </div>
                    </div>
                    <div class="content-1column-wrapper">
                        <form action="" method="post">
                            @csrf
                            <input class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full" id="search" type="text" name="search" autofocus="autofocus">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                                検索
                            </button>
                        </form>
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
            </div>
        </div>
    </div>

    @section('page_js')
    @vite(['resources/js/app.js'])
    @endsection
</x-app-layout>