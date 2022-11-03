<template>
    <section class="search">
        <div>
            <label for="">商品名</label>
            <input type="text" v-model="filter.name">
        </div>
        <div>
            <label for="">価格</label>
            <input type="radio" name="price" id="under" value="under" v-model="filter.price">
            <label for="under">200円未満</label>
            <input type="radio" name="price" id="over" value="over" v-model="filter.price">
            <label for="over">200円以上</label>
        </div>
        <div>
            <label for="">色</label>
            <input type="checkbox" name="red" id="red" value="red" v-model="filter.color">
            <label for="red">赤</label>
            <input type="checkbox" name="orange" id="orange" value="orange" v-model="filter.color">
            <label for="orange">オレンジ</label>
            <input type="checkbox" name="green" id="green" value="green" v-model="filter.color">
            <label for="green">緑</label>


        </div>
    </section>
    <table>
        <thead>
            <tr>
                <th>
                    <div @click="sortBy('id')" :class="sortClass('id')">No</div>
                </th>
                <th>
                    <div @click="sortBy('name')" :class="sortClass('name')">商品名</div>
                </th>
                <th>
                    <div @click="sortBy('price')" :class="sortClass('price')">価格</div>
                </th>
                <th>
                    <div @click="sortBy('colorCode')" :class="sortClass('colorCode')">色</div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in result">
                <td>{{ item.id }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.price }}</td>
                <td :style="`color:${item.colorCode}`">{{ item.colorCode }}</td>
            </tr>
        </tbody>
    </table>

</template>

<script>

const items = [
    { id: 1, name: "リンゴ", price: 100, colorName: '赤', colorCode: 'red' },
    { id: 2, name: "ミカン", price: 150, colorName: 'オレンジ', colorCode: 'orange' },
    { id: 3, name: "ブドウ", price: 300, colorName: '紫', colorCode: 'purple' },
    { id: 4, name: "バナナ", price: 50, colorName: '黄', colorCode: 'yellow' },
    { id: 5, name: "メロン", price: 550, colorName: '緑', colorCode: 'green' },
    { id: 6, name: "スイカ", price: 400, colorName: '緑', colorCode: 'green' },
    { id: 7, name: "トマト", price: 120, colorName: '赤', colorCode: 'red' },
    { id: 8, name: "レモン", price: 110, colorName: '黄', colorCode: 'yellow' },
    { id: 9, name: "イチゴ", price: 200, colorName: '赤', colorCode: 'red' },
    { id: 10, name: "モモ", price: 150, colorName: 'ピンク', colorCode: 'pink' },
]

export default {
    data() {
        return {
            sort: {
                key: '',
                isAsc: false
            },
            filter: {
                name: '',
                price: '',
                color: []
            }
        }
    },
    computed: {
        result() {
            const list = items.slice();
            const filterNaemList = this.filterName(list);
            const filterPriceList = this.filterPrice(filterNaemList);
            const lastFilterList = this.filterColor(filterPriceList);
            if (this.sort.key) {
                lastFilterList.sort((a, b) => {
                    a = a[this.sort.key];
                    b = b[this.sort.key];
                    return (a === b ? 0 : a > b ? 1 : -1) * (this.sort.isAsc ? 1 : -1);
                })
            }
            return lastFilterList;
        },

    },
    methods: {
        sortBy(key) {
            this.sort.isAsc = (this.sort.key === key) ? !this.sort.isAsc : true;
            this.sort.key = key;
        },
        sortClass(key) {
            return (this.sort.key === key) ? `sort ${this.sort.isAsc ? 'asc' : 'desc'}` : '';
        },
        filterName(list) {
            const name = this.filter.name;
            if (name.length > 0) {
                return list.filter(item => item.name.indexOf(name) > -1);
            }
            return list;
        },
        filterPrice(list) {
            if(this.filter.price.length === 0){return list;}
            const isOver = (this.filter.price === 'over') ? true : false;
            if(isOver){
                return list.filter(item => Number(item.price) >= 200);
            }
            return list.filter(item => Number(item.price) < 200);
        },
        filterColor(list){
            if(this.filter.color.length === 0){return list;}
            return list.filter(item => this.filter.color.indexOf(item.colorCode) > -1);
        }
    }
}

</script>

<style>
th>div.sort.desc::after {
    display: inline-block;
    content: '▼';
}

th>div.sort.asc::after {
    display: inline-block;
    content: '▲';
}
</style>