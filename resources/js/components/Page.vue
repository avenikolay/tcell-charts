<template>
    <div>
        <upload @uploaded="uploaded"></upload>
        <products :flag="flag"></products>
    </div>
</template>
<script>
    export default {
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
