<template>
    <div class="card mt-5">
        <div class="card-body">
            <h2 class="card-title">{{title}}</h2>
            <canvas ref="canvas"></canvas>
        </div>
    </div>
</template>
<script>
    import { Line } from 'vue-chartjs'
    const colors = ['blue', 'yellow', 'pink', 'black', 'red',  'lime']
    export default {
        props: ['innerData', 'title'],
        extends: Line,
        computed: {
            days() {
                return this.innerData.map(item => {
                    return item.date
                }).reduce((acc, currentValue) => {
                    if (acc.includes(currentValue)) {
                        return acc
                    }
                    return [...acc, currentValue]
                }, [])
            },
            chartData() {
                return this.innerData.reduce((acc, currentValue) => {
                    const index = acc.findIndex(i => {
                        return i.name === currentValue.name
                    })

                    if (index >= 0) {
                        acc[index].actionsByDates = [...acc[index].actionsByDates, { date: currentValue.date, quantity: currentValue.quantity  }]
                        return acc
                    }
                    return [...acc, { name: currentValue.name, actionsByDates: [ { date: currentValue.date, quantity: currentValue.quantity  } ] }]

                }, []).map((item, idx) => {
                    const color = `rgba(${Math.floor(Math.random() * Math.floor(256))},${Math.floor(Math.random() * Math.floor(256))},${Math.floor(Math.random() * Math.floor(256))},1)`;
                    return {
                        label: item.name,
                        fill: false,
                        borderWidth: 2,
                        backgroundColor: colors[idx],
                        borderColor: colors[idx],
                        data: item.actionsByDates.map(dt => {
                            return dt.quantity
                            // return Math.floor(Math.random() * Math.floor(11))
                        })
                    }
                })
            }
        },
        mounted () {
            this.renderChart(
                {
                    type: 'line',
                    labels: this.days,
                    datasets: this.chartData,
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Chart.js Line Chart - Logarithmic'
                        }
                    }
                }
            )
        }
    }
</script>
