<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('製品登録') }}
        </h2>
    </x-slot>

    <form method="POST" class="form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="tag_name" id="tag_name" value="{{ old('tag_name',$tags->implode('|') ?? '') }}">
        <div class="form__item" id="tag-wrapper">
            <p class="form__title">商品タグ</p>
            <div contenteditable="true" id="tag" style="position: relative;">
            </div>
        </div>

        <div class="form__item">
            <p class="form__title">商品名</p>
            <input type="text" name="name" class="form__text" value="{{old('name',$item['name'] ?? '')}}">
        </div>
        <div class="form__item">
            <p class="form__title">商品説明</p>
            <textarea name="explain" id="" cols="30" rows="10" class="form__text">{{old('explain',$item['explain'] ?? '')}}</textarea>
        </div>
        <div id="fileInput">
            <div class="form__item">
                <button type="button" class="btn" id="addTemplate">追加</button>
            </div>

            @if($role === "create")
            <div class="form__item" id="fileItem0">
                <input type="hidden" name="image_name[0]" id="image_name0">
                <input type="file" name="images[0]" id="file0" accept=".png,.jpg,.jpeg,.gif">
                <input type="hidden" name="pre_image_name[0]" value="">
                <button type="button" class="btn" id="fileBtn0">ファイル保存</button>
                <button type="button" class="btn" id="delElm0">削除</button>
            </div>
            @endif

            @if(isset($item))
            @foreach($item->productImage as $key => $image)
            <div class="form__item" id="fileItem{{$key}}">
                <input type="hidden" name="image_name[{{$key}}]" id="image_name{{$key}}" value="{{$image['image_name']}}">
                <input type="hidden" name="pre_image_name[{{$key}}]" value="{{$image['image_name']}}">
                <input type="file" name="images[{{$key}}]" id="file{{$key}}" accept=".png,.jpg,.jpeg,.gif">
                <button type="button" class="btn" id="fileBtn{{$key}}">ファイル保存</button>
                <button type="button" class="btn" id="delElm{{$key}}">削除</button>
                <img src="{{asset('storage/images/owner/product/'.$item->id.'/'.$image->getFilePath())}}" alt="">
            </div>
            @endforeach
            @endif
        </div>

        <div class="form__item">
            @if($role === 'edit')
            <input type="hidden" name="_method" value="PUT">
            <input type="submit" value="更新" class="btn" formaction="{{route('owner.products.update',['product' => $item->id])}}">
            @else
            <input type="submit" value="作成" class="btn" formaction="{{route('owner.products.store')}}">
            @endif

        </div>
    </form>
    <template id="fileTemplate">
        <div class="form__item" id="fileItem">
            <input type="hidden" name="image_name[]" id="image_name">
            <input type="file" name="images[]" id="file" accept=".png,.jpg,.jpeg,.gif">
            <input type="hidden" name="pre_image_name[]" value="">
            <button type="button" class="btn" id="fileBtn">ファイル保存</button>
            <button type="button" class="btn" id="delElm">削除</button>
        </div>
    </template>

    @section('page_js')
    @vite(['resources/js/uuid.js','resources/js/owner/product/index.js'])
    <script>
        const tagParentElm = document.getElementById('tag');
        const tagNameInput = document.getElementById('tag_name');

        function isExistTagName(tag_name_input) {
            return (tag_name_input.value.length > 0) ? true : false;
        }

        function createTagElmList(tag_name_list) {
            return tag_name_list.map((value) => {
                const span = document.createElement('span');
                span.classList.add('rounded-label');
                const text = document.createTextNode(value);
                span.appendChild(text);
                return span;
            });
        }

        function insertTagElm(parent_elm, tag_elm_list) {
            tag_elm_list.forEach((value) => {
                parent_elm.appendChild(value);
                const text = document.createTextNode('|');
                parent_elm.appendChild(text);
            });
        }

        function preventEnter(event) {
            if (event.code === 'Enter') {
                event.preventDefault();
            }
        }

        window.addEventListener('DOMContentLoaded', (e) => {
            if (isExistTagName(tagNameInput)) {
                const tagElmList = createTagElmList(tagNameInput.value.split('|'));
                insertTagElm(tagParentElm, tagElmList);
            }
        }, false);

        // 改行を禁ずる(なお下記処理で、全角文字の確定Enterは問題無くできる)
        tagParentElm.addEventListener('keydown', (e) => {
            preventEnter(e);
        }, false);

        tagParentElm.addEventListener('input', (e) => {
            const childElms = tagParentElm.childNodes;

            let width = 0;
            childElms.forEach(elm => {
                if (elm.nodeName === "#text" && elm.data !== '|') {}
                if (elm.nodeName !== "#text") {
                    width += elm.clientWidth;
                }
            });

            const suggestElms = document.querySelectorAll('#suggest-tag');
            if (suggestElms.length === 0) {
                const suggestParent = document.createElement('div');
                suggestParent.id = "suggest-tag";
                const selectElm = document.createElement('a');
                selectElm.textContent = 'testButton';
                selectElm.href = '#';
                suggestParent.appendChild(selectElm);
                const tagWrapperElm = document.getElementById('tag-wrapper');
                tagWrapperElm.style.position = 'relative';
                suggestParent.style.position = 'absolute';
                suggestParent.style.left = width + 'px';
                suggestParent.style.width = 120 + 'px';
                suggestParent.style.zIndex = 99;
                suggestParent.style.backgroundColor = '#fff';
                tagWrapperElm.appendChild(suggestParent);
            }

        }, false);

        tagParentElm.addEventListener('blur', () => {
            const childElms = tagParentElm.childNodes;
            let dataList = [];

            childElms.forEach((elm) => {

                if (elm.innerText !== undefined) {
                    dataList.push(elm.innerText);
                    return;
                }
                const elmText = elm.data.replace(/\n|\n\r|\r/, '');
                const splitTextList = elmText.split(/ |　|\|/);
                const setTextList = Array.from(new Set(splitTextList));
                const filiterTextList = setTextList.filter((word) => {
                    return word.length > 0 && dataList.indexOf(word) === -1;
                });
                filiterTextList.forEach(word => dataList.push(word));
            })

            while (tagParentElm.firstChild) {
                tagParentElm.removeChild(tagParentElm.firstChild);
            }


            const suggestElms = document.querySelectorAll('#suggest-tag');
            if (suggestElms.length > 0) {

                suggestElms.forEach(value => {
                    value.addEventListener('click', (e) => {
                        dataList.pop();
                        dataList.push(e.target.textContent);
                        const tagElmList = createTagElmList(dataList);
                        insertTagElm(tagParentElm, tagElmList);
                        tagNameInput.value = dataList.join('|');
                        value.remove();
                        return;
                    })
                    const tagElmList = createTagElmList(dataList);
                    insertTagElm(tagParentElm, tagElmList);
                    tagNameInput.value = dataList.join('|');
                    value.remove();
                });

            } else {
                const tagElmList = createTagElmList(dataList);
                insertTagElm(tagParentElm, tagElmList);
                tagNameInput.value = dataList.join('|');
            }
        });
    </script>
    @endsection
</x-app-layout>