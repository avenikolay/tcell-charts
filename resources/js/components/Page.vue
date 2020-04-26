<template>
    <div>
        <a class="btn btn-outline-primary mb-5" :href="url + '/stats'">Статистика</a>
        <upload @uploaded="uploaded"></upload>
        <products :flag="flag"></products>

    </div>
</template>
<script>
    export default {
        props: ['url'],
        data() {
            return {
                flag: false
            }
        },
        methods: {
            uploaded() {
                this.flag = !this.flag
            }
        }
    }
</script>
