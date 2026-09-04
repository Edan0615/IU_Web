<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import KaTex from '@/Components/KaTex.vue';
import { Radar, Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  RadialLinearScale,
  PointElement,
  LineElement,
  CategoryScale,
  LinearScale,
  Filler,
  Tooltip,
  Legend,
  Title
} from 'chart.js';

ChartJS.register(
  RadialLinearScale,
  PointElement,
  LineElement,
  CategoryScale,
  LinearScale,
  Filler,
  Tooltip,
  Legend,
  Title
);

const props = defineProps({
  stats: {
    type: Object,
    required: true
  }
});

// Chart 1: 8-Cognitive Function Cumulative Means Radar Chart
const radarData = computed(() => {
  const cis = props.stats.clt_profile?.confidence_intervals_95 || {};
  const labels = ['Ti', 'Te', 'Fi', 'Fe', 'Ni', 'Ne', 'Si', 'Se'];
  const dataPoints = labels.map(l => cis[l]?.mean ?? 0);

  return {
    labels,
    datasets: [
      {
        label: 'Sample Mean Intensity (μ)',
        backgroundColor: 'rgba(79, 70, 229, 0.18)',
        borderColor: '#4f46e5',
        pointBackgroundColor: '#4338ca',
        pointBorderColor: '#ffffff',
        borderWidth: 2,
        pointRadius: 4,
        data: dataPoints
      }
    ]
  };
});

const radarOptions = {
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
        font: { size: 10 }
      },
      grid: { color: 'rgba(226, 232, 240, 0.8)' },
      angleLines: { color: 'rgba(226, 232, 240, 0.8)' },
      pointLabels: {
        color: '#1e293b',
        font: { size: 12, weight: 'bold' }
      }
    }
  },
  plugins: {
    legend: { display: false },
    tooltip: {
      callbacks: {
        label: (ctx) => ` μ Mean: ${ctx.raw} / 30`
      }
    }
  }
};

// Chart 2: Emotional Clarity History Line Chart
const lineData = computed(() => {
  const timeline = props.stats.clarity_timeline || [];
  const labels = timeline.map(t => t.date);
  const points = timeline.map(t => (t.clarity * 100).toFixed(0));

  return {
    labels: labels.length > 0 ? labels : ['Turn 1', 'Turn 2', 'Turn 3'],
    datasets: [
      {
        label: 'Emotional Clarity (%)',
        borderColor: '#10b981',
        backgroundColor: 'rgba(16, 185, 129, 0.12)',
        borderWidth: 2,
        tension: 0.3,
        fill: true,
        pointBackgroundColor: '#059669',
        pointBorderColor: '#ffffff',
        pointRadius: 4,
        data: points.length > 0 ? points : [85, 90, 88]
      }
    ]
  };
});

const lineOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      min: 0,
      max: 100,
      ticks: { color: '#64748b', callback: (v) => `${v}%` },
      grid: { color: 'rgba(241, 245, 249, 1)' }
    },
    x: {
      ticks: { color: '#64748b', font: { size: 10 } },
      grid: { display: false }
    }
  },
  plugins: {
    legend: { display: false }
  }
};
</script>

<template>
  <Head title="Member Cognitive Statistics - NOW" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h2 class="font-bold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
            <span>Member Cognitive Analytics</span>
          </h2>
          <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
            <span>Central Limit Theorem & 95% Confidence Interval:</span>
            <KaTex expression="\text{CI}_{95\%} = \mu \pm 1.96 \cdot \text{SE}" />
          </p>
        </div>
        <Link 
          :href="route('counseling.home')" 
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5"
        >
          <span>💬 Start Counseling Session</span>
        </Link>
      </div>
    </template>

    <div class="py-8 bg-slate-50 min-h-screen">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Summary KPI Stat Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <!-- Total Sessions -->
          <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-xl">
              💬
            </div>
            <div>
              <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Dialogue Turns</span>
              <span class="text-2xl font-black text-slate-900">{{ stats.total_turns }}</span>
              <span class="text-[11px] text-slate-400 block mt-0.5">{{ stats.total_sessions }} Active Sessions</span>
            </div>
          </div>

          <!-- Top MBTI Match -->
          <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-xl">
              🧩
            </div>
            <div>
              <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">MBTI Alignment</span>
              <span class="text-2xl font-black text-purple-700">{{ stats.top_mbti_match }}</span>
              <span class="text-[11px] text-purple-600/80 block mt-0.5 font-mono">
                <KaTex expression="r_{\text{pearson}}" /> Metric
              </span>
            </div>
          </div>

          <!-- Average Emotional Clarity -->
          <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-xl">
              🌱
            </div>
            <div>
              <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Avg Emotional Clarity</span>
              <span class="text-2xl font-black text-emerald-600">{{ (stats.average_emotional_clarity * 100).toFixed(0) }}%</span>
              <span class="text-[11px] text-emerald-600/80 block mt-0.5">Stability Score</span>
            </div>
          </div>

          <!-- Loop Interventions -->
          <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-xl">
              🔄
            </div>
            <div>
              <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block">Loop Disruption</span>
              <span class="text-2xl font-black text-rose-600">{{ stats.cognitive_loop_interventions }}</span>
              <span class="text-[11px] text-rose-600/80 block mt-0.5">Interventions Applied</span>
            </div>
          </div>
        </div>

        <!-- Chart Section Grid (Chart.js Visualizations) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Chart 1: Radar Spectrum -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs flex flex-col">
            <div class="flex justify-between items-center mb-4">
              <div>
                <h3 class="text-base font-bold text-slate-900">Cumulative Cognitive Spectrum (Chart.js Radar)</h3>
                <p class="text-xs text-slate-500 mt-0.5">8 Psychological Functions Sample Means (<KaTex expression="\mu" />)</p>
              </div>
              <span class="text-xs font-mono text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">
                <KaTex expression="\mu = \frac{1}{n}\sum x_i" />
              </span>
            </div>
            <div class="relative w-full h-[280px]">
              <Radar :data="radarData" :options="radarOptions" />
            </div>
          </div>

          <!-- Chart 2: Clarity Timeline -->
          <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs flex flex-col">
            <div class="flex justify-between items-center mb-4">
              <div>
                <h3 class="text-base font-bold text-slate-900">Emotional Clarity Progression (Chart.js Line)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Real-time clarity tracking across sessions</p>
              </div>
              <span class="text-xs font-mono text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                <KaTex expression="S_{\text{clarity}} \in [0.1, 1.0]" />
              </span>
            </div>
            <div class="relative w-full h-[280px]">
              <Line :data="lineData" :options="lineOptions" />
            </div>
          </div>
        </div>

        <!-- CLT 95% Confidence Interval Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
              <h3 class="text-lg font-bold text-slate-900">8-Cognitive Function Cumulative Profile (CLT)</h3>
              <p class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                <span>Sample Size: <strong class="text-slate-800"><KaTex expression="n" /> = {{ stats.clt_profile?.sample_size || 0 }} turns</strong></span>
                <span>•</span>
                <span>Formula: <KaTex expression="\text{CI}_{95\%} = \mu \pm 1.96 \cdot \text{SE}" /></span>
                <span>•</span>
                <span>Standard Error: <KaTex expression="\text{SE} = \frac{\sigma}{\sqrt{n}}" /></span>
              </p>
            </div>
            <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-xl border border-indigo-200">
              Model: <KaTex expression="\text{CLT Sample Mean } \mu" />
            </span>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-semibold uppercase tracking-wider">
                  <th class="py-3.5 px-6">Function</th>
                  <th class="py-3.5 px-6">Sample Mean (<KaTex expression="\mu" />)</th>
                  <th class="py-3.5 px-6">Std Dev (<KaTex expression="\sigma" />)</th>
                  <th class="py-3.5 px-6">Std Error (<KaTex expression="\text{SE}" />)</th>
                  <th class="py-3.5 px-6">95% Confidence Interval (<KaTex expression="\text{CI}_{95\%}" />)</th>
                  <th class="py-3.5 px-6">Intensity Level</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr 
                  v-for="(ci, func) in (stats.clt_profile?.confidence_intervals_95 || {})" 
                  :key="func"
                  class="hover:bg-slate-50/80 transition-colors"
                >
                  <td class="py-3.5 px-6 font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-700 font-mono">
                      {{ func }}
                    </span>
                    <span>{{ func }}</span>
                  </td>
                  <td class="py-3.5 px-6 font-mono font-bold text-indigo-700 text-sm">
                    {{ ci.mean }} / 30
                  </td>
                  <td class="py-3.5 px-6 font-mono text-slate-600">{{ ci.std_dev }}</td>
                  <td class="py-3.5 px-6 font-mono text-slate-600">{{ ci.standard_error }}</td>
                  <td class="py-3.5 px-6 font-mono font-medium text-slate-800">
                    [{{ ci.ci_95_lower }}, {{ ci.ci_95_upper }}]
                  </td>
                  <td class="py-3.5 px-6">
                    <div class="w-36 bg-slate-100 h-2.5 rounded-full overflow-hidden border border-slate-200">
                      <div 
                        class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full rounded-full"
                        :style="{ width: `${(ci.mean / 30) * 100}%` }"
                      ></div>
                    </div>
                  </td>
                </tr>
                <tr v-if="!stats.clt_profile?.confidence_intervals_95 || Object.keys(stats.clt_profile.confidence_intervals_95).length === 0">
                  <td colspan="6" class="py-8 text-center text-slate-400 italic">
                    No dialogue turns recorded yet. Start a counseling chat session to build your cumulative cognitive statistics!
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- MBTI Personality Type Match Rankings -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-6">
            <div>
              <h3 class="text-lg font-bold text-slate-900">Top Matched MBTI Stacks (Pearson & Cosine Metrics)</h3>
              <p class="text-xs text-slate-500 mt-0.5">
                Correlating student 8-cognitive function vector against standard MBTI matrices.
              </p>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-600 bg-slate-50 p-2 rounded-xl border border-slate-200">
              <span class="font-mono flex items-center gap-1">
                <span>Pearson:</span> <KaTex expression="r = \frac{\sum (x_i - \bar{x})(y_i - \bar{y})}{\sqrt{\sum (x_i - \bar{x})^2 \sum (y_i - \bar{y})^2}}" />
              </span>
              <span>•</span>
              <span class="font-mono flex items-center gap-1">
                <span>Cosine:</span> <KaTex expression="\cos(\theta) = \frac{A \cdot B}{\|A\|_2 \|B\|_2}" />
              </span>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            <div 
              v-for="(item, idx) in (stats.clt_profile?.overall_mbti_match?.ranking || [])" 
              :key="item.type"
              class="p-4 rounded-xl border transition-all"
              :class="idx === 0 ? 'bg-gradient-to-b from-indigo-50/80 to-purple-50/80 border-indigo-200 shadow-xs' : 'bg-slate-50/60 border-slate-200/80'"
            >
              <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-slate-400">#{{ idx + 1 }} Match</span>
                <span v-if="idx === 0" class="text-[10px] font-extrabold bg-indigo-600 text-white px-2 py-0.5 rounded-md">PRIMARY</span>
              </div>
              <h4 class="text-xl font-black text-slate-900 mb-1">{{ item.type }}</h4>
              <div class="space-y-1 text-[11px] font-mono text-slate-600">
                <div>Pearson <KaTex expression="r" />: <strong class="text-indigo-700">{{ (item.pearson_correlation * 100).toFixed(1) }}%</strong></div>
                <div>Cosine <KaTex expression="\cos\theta" />: <strong class="text-purple-700">{{ (item.cosine_similarity * 100).toFixed(1) }}%</strong></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>


