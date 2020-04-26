<template>
<div>
    <template v-if="loaded">
        <a class="btn btn-outline-primary mb-5" :href="url">Загрузить данные</a><br />
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-primary" :disabled="period === 7" @click="changePeriod(7)">7 дней</button>
            <button type="button" class="btn btn-primary" :disabled="period === 30" @click="changePeriod(30)">30 дней</button>
        </div>
        <chart title="Тарифный план «Даркор»" :innerData="darkor" :colors="['#f7931e', '#0071bc', '#ed1c24', '#39b54a', '#754c24',  '#662d91']" />
        <chart title="Тарифный план «Алло»" :innerData="alo" :colors="['#f7931e', '#0071bc', '#ed1c24', '#39b54a', '#754c24',  '#662d91']" />
        <chart title="Социальные сети" :innerData="socials" :colors="['#39b54a', '#0071bc', '#ed1c24']" />
    </template>
</div>
</template>

<script>
    export default {
        name: "Charts",
        props: ['url'],
        data() {
            return {
                period: 7,
                loaded: false,
                darkor: [],
                alo: [],
                socials: []
            }
        },
        methods: {
            changePeriod(period) {
                this.period = period
                this.loadData();
            },
            loadData() {
                axios.all([axios.get(`/api/getStats?group=darkor&period=${this.period}`), axios.get(`/api/getStats?group=alo&period=${this.period}`), axios.get(`/api/getStats?group=socials&period=${this.period}`)])
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
