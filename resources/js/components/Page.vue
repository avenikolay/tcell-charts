<template>
    <div>
        <upload @uploaded="uploaded"></upload>
        <products :flag="flag"></products>
        <!--<chart></chart>-->
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
