<template>
    <cool-select v-model="selected" :items="items" placeholder="Select name" />
</template>

<script>
import { CoolSelect } from "vue-cool-select";

export default {
    component: {
        CoolSelect
    },
    data() {
        return {
            selected: null,
            items: ["Anton", "Andrey", "富む", "マイケル"]
        }
    }
}
</script>