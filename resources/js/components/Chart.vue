<template>
    <div class="card mt-5">
        <div class="card-body">
            <canvas ref="canvas"></canvas>
        </div>
    </div>
</template>
<script>
    import { Line } from 'vue-chartjs'
    export default {
        data(){
            return {
                products: [
                    {time: '1', nums: 2},
                    {time: '2', nums: 3}
                ]
            }
        },
        extends: Line,
        mounted () {
            this.renderChart(
                {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 3],
                        fill: false,
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                {
                    scales: {
                        yAxes: [{
                            stacked: false
                        }]
                    }
                }
            )
        }
    }
</script>
