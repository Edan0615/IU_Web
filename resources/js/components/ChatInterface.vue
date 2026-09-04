<template>
  <div class="row g-4 text-light">
    <!-- Main Chat Window (8 Columns) -->
    <div class="col-lg-8">
      <div class="card bg-dark text-light border-secondary border-opacity-25 shadow-lg rounded-4 overflow-hidden h-100 d-flex flex-column" style="min-height: 700px;">
        <!-- Header -->
        <div class="card-header bg-body-tertiary border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between px-4 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="badge bg-primary p-2 rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
              🧠
            </div>
            <div>
              <h5 class="mb-0 fw-bold text-white">Cognitive Counselor</h5>
              <small class="text-info fs-7">3-Stage Cognitive Loop Detection & Rotation System</small>
            </div>
          </div>
          <span v-if="dominantFunction" class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-2">
            Dominant: {{ dominantFunction }}
          </span>
        </div>

        <!-- Message Body List -->
        <div ref="messagesContainer" class="card-body overflow-auto p-4 flex-grow-1 d-flex flex-column gap-3" style="max-height: 520px;">
          <!-- Empty State Placeholder -->
          <div v-if="messages.length === 0" class="my-auto text-center text-secondary py-5">
            <div class="fs-1 mb-2">💬</div>
            <h5 class="text-light fw-semibold">Welcome to Cognitive Counseling</h5>
            <p class="fs-7 text-secondary max-w-md mx-auto">Feel free to share your current thoughts or feelings. The assistant will evaluate your 8 cognitive function intensities in real time.</p>
          </div>

          <!-- Message Bubbles -->
          <div
            v-for="(msg, idx) in messages"
            :key="idx"
            :class="['d-flex mb-2', msg.role === 'user' ? 'justify-content-end' : 'justify-content-start']"
          >
            <div
              :class="[
                'p-3 rounded-4 shadow-sm text-break max-w-75',
                msg.role === 'user'
                  ? 'bg-primary text-white rounded-bottom-end-0'
                  : 'bg-body-tertiary text-light border border-secondary border-opacity-25 rounded-bottom-start-0'
              ]"
              style="max-width: 85%;"
            >
              <!-- Content -->
              <div class="lh-relaxed text-wrap">{{ msg.content }}</div>

              <!-- Metadata Tags for Assistant -->
              <div v-if="msg.role === 'assistant' && msg.analysis_metadata" class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex flex-wrap gap-2 align-items-center fs-7">
                <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-25">
                  Primary: {{ msg.analysis_metadata.primary_function }}
                </span>
                <span v-if="msg.analysis_metadata.secondary_function" class="badge bg-secondary bg-opacity-20 text-light border border-secondary border-opacity-25">
                  Auxiliary: {{ msg.analysis_metadata.secondary_function }}
                </span>
                <span class="text-secondary fs-8 ms-auto">
                  Emotional Clarity: {{ Math.round((msg.analysis_metadata.emotional_clarity_score || 0) * 100) }}%
                </span>
              </div>
            </div>
          </div>

          <!-- Loading Indicator -->
          <div v-if="isLoading" class="d-flex justify-content-start">
            <div class="bg-body-tertiary border border-secondary border-opacity-25 rounded-4 p-3 d-flex align-items-center gap-2">
              <div class="spinner-border spinner-border-sm text-info" role="status"></div>
              <span class="fs-7 text-secondary">Analyzing cognitive state...</span>
            </div>
          </div>
        </div>

        <!-- Footer Input Bar -->
        <div class="card-footer bg-body-tertiary border-top border-secondary border-opacity-25 p-3">
          <form @submit.prevent="sendMessage" class="d-flex gap-2">
            <input
              v-model="inputMessage"
              type="text"
              placeholder="Type your message or concern..."
              :disabled="isLoading"
              class="form-control bg-dark text-light border-secondary border-opacity-50 rounded-pill px-4"
            />
            <button
              type="submit"
              :disabled="isLoading || !inputMessage.trim()"
              class="btn btn-primary rounded-pill px-4 fw-semibold d-flex align-items-center gap-2"
            >
              <span>Send</span>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Sidebar Dashboard (4 Columns) -->
    <div class="col-lg-4 d-flex flex-column gap-4">
      <!-- Radar Card -->
      <div class="card bg-dark text-light border-secondary border-opacity-25 shadow-lg rounded-4 p-3">
        <div class="card-body">
          <h5 class="card-title fw-bold text-white mb-2 d-flex align-items-center gap-2">
            <span>📊</span>
            <span>8-Cognitive Function Radar</span>
          </h5>
          <p class="card-text fs-7 text-secondary mb-3">Dynamic spectrum tracking Ni, Ne, Si, Se, Ti, Te, Fi, Fe based on semantic analysis.</p>
          
          <RadarChart :cognitive-scores="currentScores" />
        </div>
      </div>

      <!-- State Summary Card -->
      <div class="card bg-dark text-light border-secondary border-opacity-25 shadow-lg rounded-4 p-3">
        <div class="card-body">
          <h6 class="card-title fw-bold text-light mb-3">Current Cognitive Summary</h6>
          <ul class="list-group list-group-flush fs-7">
            <li class="list-group-item bg-transparent text-light border-secondary border-opacity-25 d-flex justify-content-between py-2 px-0">
              <span class="text-secondary">Primary Function</span>
              <span class="fw-bold text-info font-monospace">{{ latestAnalysis.primary_function || 'Awaiting input' }}</span>
            </li>
            <li class="list-group-item bg-transparent text-light border-secondary border-opacity-25 d-flex justify-content-between py-2 px-0">
              <span class="text-secondary">Rotation Strategy</span>
              <span class="text-warning font-monospace">{{ latestAnalysis.suggested_rotation || 'Empathetic Alignment' }}</span>
            </li>
            <li class="list-group-item bg-transparent text-light border-secondary border-opacity-25 d-flex justify-content-between py-2 px-0">
              <span class="text-secondary">Emotional Clarity</span>
              <span class="fw-bold text-success font-monospace">{{ Math.round((latestAnalysis.emotional_clarity_score || 0.8) * 100) }} / 100</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import RadarChart from './RadarChart.vue';

const inputMessage = ref('');
const messages = ref([]);
const isLoading = ref(false);
const sessionToken = ref(localStorage.getItem('counseling_session_token') || null);
const dominantFunction = ref('');
const messagesContainer = ref(null);

const currentScores = ref({
  Ni: 50, Ne: 50, Si: 50, Se: 50,
  Ti: 50, Te: 50, Fi: 50, Fe: 50
});

const latestAnalysis = ref({
  primary_function: '',
  secondary_function: '',
  suggested_rotation: '',
  emotional_clarity_score: 0.8
});

// Fetch history
const fetchHistory = async () => {
  if (!sessionToken.value) return;
  try {
    const res = await axios.get('/api/chat/history', {
      params: { session_token: sessionToken.value }
    });
    if (res.data.status === 'success') {
      messages.value = res.data.messages || [];
      dominantFunction.value = res.data.dominant_function || '';
      
      const lastAssistantMsg = [...messages.value].reverse().find(m => m.role === 'assistant');
      if (lastAssistantMsg && lastAssistantMsg.analysis_metadata) {
        latestAnalysis.value = lastAssistantMsg.analysis_metadata;
        if (lastAssistantMsg.analysis_metadata.cognitive_scores) {
          currentScores.value = lastAssistantMsg.analysis_metadata.cognitive_scores;
        }
      }
      scrollToBottom();
    }
  } catch (err) {
    console.error('Fetch history failed:', err);
  }
};

// Send message
const sendMessage = async () => {
  const text = inputMessage.value.trim();
  if (!text || isLoading.value) return;

  messages.value.push({
    role: 'user',
    content: text
  });
  inputMessage.value = '';
  isLoading.value = true;
  scrollToBottom();

  try {
    const res = await axios.post('/api/chat/send', {
      message: text,
      session_token: sessionToken.value
    });

    if (res.data.status === 'success') {
      sessionToken.value = res.data.session_token;
      localStorage.setItem('counseling_session_token', sessionToken.value);

      const payload = res.data.data;
      const assistantMsg = payload.assistant_message;

      messages.value.push(assistantMsg);

      if (payload.latest_analysis) {
        latestAnalysis.value = payload.latest_analysis;
        if (payload.latest_analysis.cognitive_scores) {
          currentScores.value = payload.latest_analysis.cognitive_scores;
        }
        if (payload.latest_analysis.primary_function) {
          dominantFunction.value = payload.latest_analysis.primary_function;
        }
      }
    }
  } catch (err) {
    console.error('Send message failed:', err);
    messages.value.push({
      role: 'assistant',
      content: 'An error occurred during communication. Please try again later.'
    });
  } finally {
    isLoading.value = false;
    scrollToBottom();
  }
};

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
};

onMounted(() => {
  fetchHistory();
});
</script>
