<script setup>
import { defineProps, computed } from 'vue';
import { Radar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler,
  Tooltip,
  Legend
} from 'chart.js';

// Register Chart.js components
ChartJS.register(
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler,
  Tooltip,
  Legend
);

const props = defineProps({
  dominantFunction: {
    type: String,
    default: 'Fi'
  },
  rotationVector: {
    type: String,
    default: null
  },
  inLoop: {
    type: Boolean,
    default: false
  },
  loopDetected: {
    type: String,
    default: null
  },
  emotionalClarityScore: {
    type: Number,
    default: 0.85
  },
  scores: {
    type: Object,
    default: () => ({
      Ti: 0, Te: 0, Fi: 0, Fe: 0,
      Ni: 0, Ne: 0, Si: 0, Se: 0
    })
  }
});

// Configure Chart.js Radar Data (1-30 scale for 8 Jungian Cognitive Functions)
const chartData = computed(() => {
  const s = props.scores || {};
  return {
    labels: ['Ti', 'Te', 'Fi', 'Fe', 'Ni', 'Ne', 'Si', 'Se'],
    datasets: [
      {
        label: 'Cognitive Function Intensity (1 - 30)',
        backgroundColor: 'rgba(79, 70, 229, 0.15)',
        borderColor: '#4f46e5',
        pointBackgroundColor: '#4338ca',
        pointBorderColor: '#ffffff',
        pointHoverBackgroundColor: '#6366f1',
        pointHoverBorderColor: '#ffffff',
        borderWidth: 2,
        pointRadius: 4,
        data: [
          s.Ti ?? 0,
          s.Te ?? 0,
          s.Fi ?? 0,
          s.Fe ?? 0,
          s.Ni ?? 0,
          s.Ne ?? 0,
          s.Si ?? 0,
          s.Se ?? 0
        ]
      }
    ]
  };
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    r: {
      min: 0,
      max: 30,
      ticks: {
        stepSize: 10,
        backdropColor: 'transparent',
        color: '#64748b',
        font: { size: 9 }
      },
      grid: { color: 'rgba(226, 232, 240, 0.8)' },
      angleLines: { color: 'rgba(226, 232, 240, 0.8)' },
      pointLabels: {
        color: '#1e293b',
        font: { size: 11, weight: 'bold' }
      }
    }
  },
  plugins: {
    legend: { display: false },
    tooltip: {
      callbacks: {
        label: (ctx) => ` Intensity: ${ctx.raw} / 30`
      }
    }
  }
};
</script>

<template>
  <div class="mt-3 p-4 bg-white/95 border border-slate-200/90 rounded-xl space-y-3 shadow-md backdrop-blur-md">
    <!-- Analysis Summary Header -->
    <div class="flex items-center justify-between text-xs font-semibold text-slate-800 border-b border-slate-200/80 pb-2.5">
      <span class="flex items-center gap-1.5 text-indigo-700">
        <span>🧠</span> Cognitive State Spectrum
      </span>
      <div class="flex items-center gap-2">
        <span v-if="rotationVector" class="bg-indigo-50 text-indigo-700 px-2.5 py-0.5 rounded-full border border-indigo-200/80 text-[11px] font-bold">
          Rotation: {{ rotationVector }}
        </span>
        <span class="bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200 text-slate-700 text-[11px]">
          Dominant: {{ dominantFunction }}
        </span>
      </div>
    </div>

    <!-- Radar Chart -->
    <div class="relative w-full h-[220px]">
      <Radar :data="chartData" :options="chartOptions" />
    </div>

    <!-- Loop Alert if Present -->
    <div v-if="inLoop" class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-start gap-2.5">
      <span class="text-base leading-none">⚠️</span>
      <div>
        <strong class="font-bold text-rose-900 block mb-0.5">Cognitive Loop Warning:</strong>
        <span class="text-rose-800/90">{{ loopDetected }}</span>
      </div>
    </div>
  </div>
</template>


