import { defineComponent, h } from "vue";

import { Line } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  PointElement,
  CategoryScale,
  Filler
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  PointElement,
  CategoryScale,
  Filler
);

export default defineComponent({
  name: "LineChart",
  components: {
    Line
  },
  props: {
    chartId: {
      type: String,
      default: "line-chart"
    },
    width: {
      type: Number,
      default: 400
    },
    height: {
      type: Number,
      default: 400
    },
    cssClasses: {
      type: String,
      default: ""
    },
    styles: {
      type: Object,
      default: () => {}
    },
    plugins: {
      type: Object,
      default: () => []
    },
    labels: {
      type: Array,
      default: () => []
    },
    data: {
      type: Array,
      default: () => {}
    }
  },
  setup(props) {
    const chartData = {
      labels: props.labels,
      datasets: props.data,
    };

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false
    };

    return () =>
      h(Line, {
        chartData,
        chartOptions,
        chartId: props.chartId,
        width: props.width,
        height: props.height,
        cssClasses: props.cssClasses,
        styles: props.styles,
        plugins: props.plugins
      });
  }
});
