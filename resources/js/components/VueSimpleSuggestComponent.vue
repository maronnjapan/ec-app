
<template>
    <vue-simple-suggest v-model="select" :list="suggestionList" :filter-by-query="true">
        <input placeholder=”入力してください” id=”suggest-input” autocomplete=”off”>
    </vue-simple-suggest>
</template>

<script>
import VueSimpleSuggest from "vue-simple-suggest";  //忘れずにインポート
import 'vue-simple-suggest/dist/styles.css';

export default {
    components: {
        'vue-simple-suggest': VueSimpleSuggest
    },
    data: function () {
        return {
            select: '',  //入力された文字を格納
        }
    },
    methods: {
        suggestionList() {
            return ['Canad', 'China', 'Japan', 'Iraq'];
        }
    }
}
</script>