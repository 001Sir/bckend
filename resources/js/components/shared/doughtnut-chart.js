import { defineComponent, h } from "vue";
import { Doughnut } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
} from "chart.js";

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

export default defineComponent({
    name: "DoughnutChart",
    components: { Doughnut },
    props: {
        chartId: {
            type: String,
            default: "doughnut-chart",
        },
        width: {
            type: Number,
            default: 400,
        },
        height: {
            type: Number,
            default: 400,
        },
        cssClasses: {
            default: "",
            type: String,
        },
        style: {
            type: Object,
            default: () => {},
        },
        plugins: {
            type: Object,
            default: () => {},
        },
        labels: {
            type: Array,
            default: [],
        },
        data: {
            type: Object,
            default: [],
        },
    },
    setup(props) {
        const chartData = {
            labels: props.labels,
            datasets: [
                { backgroundColor: ["#00B8A6", "#ccc"], data: props.data },
            ],
        };

        const chartOptions = {
            responsive: true
        };

        return () =>
            h(Doughnut, {
                chartData,
                chartOptions,
                chartId: props.chartId,
                width: props.width,
                height: props.height,
                cssClasses: props.cssClasses,
                styles: props.styles,
                plugins: props.plugins,
            });
    },
});
