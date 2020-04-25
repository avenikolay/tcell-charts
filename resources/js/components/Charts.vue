<template>
<div>
    <template v-if="loaded">
        <chart title="Линейка «Даркор»" :innerData="darkor" />
        <chart title="Линейка «Алло»" :innerData="alo" />
        <chart title="Социальные сети" :innerData="socials" />
    </template>
</div>
</template>

<script>
    const darkor = axios.get('/api/getDarkor');
    const alo = axios.get('/api/getAlo');
    const socials = axios.get('/api/getSocials');
    export default {
        name: "Charts",
        data() {
            return {
                loaded: false,
                darkor: [],
                alo: [],
                socials: []
            }
        },
        methods: {
            loadData: function () {
                axios.all([darkor, alo, socials])
                    .then(axios.spread((...responses) => {
                        this.darkor = responses[0].data
                        this.alo = responses[1].data
                        this.socials = responses[2].data
                        this.loaded = true
                    }))
            }
        },
        mounted() {
            this.loadData()
        }
    }
</script>
]
