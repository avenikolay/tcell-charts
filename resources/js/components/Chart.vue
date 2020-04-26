<template>
    <div class="card mt-5">
        <div class="card-header" style="background-color: #cccccc">
            <h2 class="card-title">{{title}}</h2>
        </div>
        <div class="card-body">
            <canvas ref="canvas"></canvas>
        </div>
    </div>
</template>
<script>
    import { Line, mixins } from 'vue-chartjs'
    // const colors = ['#f7931e', '#0071bc', '#ed1c24', '#39b54a', '#754c24',  '#662d91']
    const moment = require('moment');
    const Chart = require('chart.js');
    moment.locale();
    export default {
        props: ['innerData', 'title', 'colors'],
        data: () => ({
            options: {
                responsive: true,
                scales: {
                    xAxes: [{
                        gridLines: {
                            display:false
                        }
                    }]

                }
            }
        }),
        watch: {
          'innerData': function() {
              this.$forceUpdate();
              this.render();
          }
        },
        computed: {
            days() {
                return this.innerData.map(item => {
                    if (moment(item.date).format( 'DD' ) === '01')
                    {
                        return moment(item.date).format( 'DD' ) + '\n' + moment(item.date).format( 'MMMM' );
                    }
                    return moment(item.date).format( 'DD' )
                }).reduce((acc, currentValue) => {
                    if (acc.includes(currentValue)) {
                        return acc
                    }
                    return [...acc, currentValue]
                }, [])
                .map(item => {
                    return item.split('\n')
                })
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
                    return {
                        label: item.name,
                        fill: false,
                        borderWidth: 4,
                        backgroundColor: this.colors[idx],
                        borderColor: this.colors[idx],
                        data: item.actionsByDates.map(dt => {
                            return dt.quantity
                        })
                    }
                })
            }
        },
        methods: {
            render() {
              const ctx = this.$el.querySelector('canvas').getContext('2d');
              Chart.Line(ctx, {
                  data: {
                      labels: this.days,
                      datasets: this.chartData,
                  },
                  options: this.options
              })
            }
        },
        mounted () {
            this.render()
        }
    }
</script>
