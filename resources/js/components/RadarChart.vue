<template>
  <div class="card bg-white border border-stone-200 shadow-sm rounded-4 p-2">
    <div class="card-body p-1 flex-grow-1 d-flex align-items-center justify-content-center" :style="{ minHeight: height }">
      <canvas ref="canvasRef"></canvas>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import {
  Chart,
  RadarController,
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler,
  Tooltip,
  Legend
} from 'chart.js';

Chart.register(RadarController, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend);

const props = defineProps({
  cognitiveScores: {
    type: Object,
    default: () => ({
      Ni: 50, Ne: 50, Si: 50, Se: 50,
      Ti: 50, Te: 50, Fi: 50, Fe: 50
    })
  },
  height: {
    type: String,
    default: '250px'
  }
});

const canvasRef = ref(null);
let chartInstance = null;

const renderChart = () => {
  if (!canvasRef.value) return;
  if (chartInstance) {
    chartInstance.destroy();
  }

  const labels = ['Ni', 'Ne', 'Si', 'Se', 'Ti', 'Te', 'Fi', 'Fe'];
  const dataValues = labels.map(key => props.cognitiveScores[key] ?? 50);

  chartInstance = new Chart(canvasRef.value, {
    type: 'radar',
    data: {
      labels,
      datasets: [
        {
          label: 'Cognitive Radar',
          data: dataValues,
          backgroundColor: 'rgba(234, 88, 12, 0.15)',
          borderColor: 'rgba(234, 88, 12, 1)',
          pointBackgroundColor: 'rgba(234, 88, 12, 1)',
          pointBorderColor: '#ffffff',
          pointHoverBackgroundColor: '#ffffff',
          pointHoverBorderColor: 'rgba(234, 88, 12, 1)',
          borderWidth: 2,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        r: {
          angleLines: { color: 'rgba(0, 0, 0, 0.08)' },
          grid: { color: 'rgba(0, 0, 0, 0.08)' },
          pointLabels: {
            color: '#44403c',
            font: { size: 11, weight: 'bold' }
          },
          ticks: {
            display: false,
            min: 0,
            max: 100
          }
        }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
};

onMounted(() => {
  renderChart();
});

watch(() => props.cognitiveScores, () => {
  renderChart();
}, { deep: true });
</script>
