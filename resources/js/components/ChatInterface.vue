<template>
  <div class="row g-4 text-dark">
    <!-- Main Chat Window (8 Columns) -->
    <div class="col-lg-8">
      <div class="card bg-white border border-stone-200 shadow-sm rounded-4 overflow-hidden h-100 d-flex flex-column chat-card-responsive">
        <!-- Header -->
        <div class="card-header bg-light border-bottom border-stone-200 d-flex align-items-center justify-content-between px-4 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 bg-orange-subtle text-orange p-2 d-flex align-items-center justify-content-center border border-orange-subtle" style="width: 42px; height: 42px;">
              <svg class="bi bi-chat-left-dots" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
              </svg>
            </div>
            <div>
              <h5 class="mb-0 fw-bold text-dark">Cognitive Counselor</h5>
              <small class="text-orange fw-medium fs-7">2-Stage Cognitive Rotation Engine (Diagnosis ➔ Bridge ➔ Target Grounding)</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span v-if="isGuest" :class="['badge rounded-pill px-3 py-2 fw-semibold fs-8', isGuestLimitReached ? 'bg-danger text-white' : 'bg-warning text-dark']">
              Guest Trial: {{ guestUserMsgCount }}/3 Messages
            </span>
            <span v-if="dominantFunction" class="badge bg-orange-subtle text-orange border border-orange-subtle rounded-pill px-3 py-2 fw-semibold fs-8">
              Dominant: {{ dominantFunction }}
            </span>
          </div>
        </div>

        <!-- Message Body Stream -->
        <div ref="messagesContainer" class="card-body overflow-auto p-4 flex-grow-1 d-flex flex-column gap-4 chat-stream-responsive" style="background-color: #fcfcfc;">
          <!-- Empty Placeholder -->
          <div v-if="messages.length === 0" class="my-auto text-center text-secondary py-5">
            <div class="mb-3 text-orange d-inline-flex p-3 rounded-circle bg-orange-subtle border border-orange-subtle">
              <svg class="bi bi-chat-quote" width="36" height="36" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-1.35 2.433-2.52.01-.064.08-.1.144-.094 1.25.109 2.5.006 3.75-.308a1 1 0 0 0 .753-.974V3a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1.678.894"/>
              </svg>
            </div>
            <h5 class="text-dark fw-bold">Welcome to Cognitive Counseling</h5>
            <p class="fs-7 text-secondary max-w-md mx-auto">Share your thoughts. Every message includes an embedded cognitive radar chart, reasoning rationale, and cognitive rotation strategy vector.</p>
          </div>

          <!-- Messages Stream with Per-Message Embedded Radar Chart & Cognitive Rotation Strategy -->
          <div
            v-for="(msg, idx) in messages"
            :key="idx"
            :class="['d-flex mb-3', msg.role === 'user' ? 'justify-content-end' : 'justify-content-start']"
          >
            <div
              :class="[
                'p-3 rounded-4 shadow-sm text-break',
                msg.role === 'user'
                  ? 'btn-orange text-white rounded-bottom-end-0 border-0'
                  : 'bg-white text-dark border border-stone-200 rounded-bottom-start-0'
              ]"
              style="max-width: 90%;"
            >
              <!-- Content -->
              <div class="lh-relaxed text-wrap fw-medium mb-1">{{ msg.content }}</div>

              <!-- Per-Message 8-Cognitive Function Metadata & Rotation Strategy (ONLY for Assistant Responses) -->
              <div v-if="msg.role === 'assistant' && msg.analysis_metadata" class="mt-3 pt-2 border-top border-stone-200 fs-7">
                <!-- Summary Badges -->
                <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                  <span class="badge bg-orange-subtle text-orange border border-orange-subtle">
                    Primary: {{ msg.analysis_metadata.primary_function }}
                  </span>
                  <span v-if="msg.analysis_metadata.secondary_function" class="badge bg-light text-secondary border border-stone-200">
                    Auxiliary: {{ msg.analysis_metadata.secondary_function }}
                  </span>
                  <span class="text-secondary fs-8 ms-auto">
                    Clarity: {{ Math.round((msg.analysis_metadata.emotional_clarity_score || 0) * 100) }}%
                  </span>
                </div>

                <!-- Cognitive Rotation Vector & Strategy Box (每段對應功能的輪盤轉動效果) -->
                <div v-if="getRotationForMsg(msg)" class="p-3 bg-orange-subtle rounded-3 border border-orange-subtle mb-2 text-dark">
                  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2 text-orange fw-bold fs-7">
                      <svg class="bi bi-arrow-repeat spin-slow" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.534 7h3.932a.25.25 0 0 0 .192-.41l-1.966-2.36a.25.25 0 0 0-.384 0l-1.966 2.36a.25.25 0 0 0 .192.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5 5 0 0 0 8.9 4.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                      </svg>
                      <span>Cognitive Rotation Wheel Vector:</span>
                    </div>
                    <!-- Stage Badges Flow -->
                    <div class="d-flex align-items-center gap-1 font-monospace fs-8">
                      <span class="badge btn-orange text-white px-2 py-1 shadow-sm">
                        State: {{ getRotationForMsg(msg).primary }}
                      </span>
                      <span class="text-orange fw-bold">➔</span>
                      <span class="badge bg-white text-orange border border-orange-subtle px-2 py-1 fw-bold">
                        Bridge: {{ getRotationForMsg(msg).bridge }}
                      </span>
                      <span class="text-orange fw-bold">➔</span>
                      <span class="badge bg-success text-white px-2 py-1 shadow-sm">
                        Target: {{ getRotationForMsg(msg).target }}
                      </span>
                    </div>
                  </div>
                  <div class="text-dark fs-8 border-top border-orange-subtle pt-2 mt-1">
                    <strong class="text-orange">2-Stage Rotation Strategy:</strong>
                    <span class="text-secondary ms-1">{{ getRotationForMsg(msg).strategy }}</span>
                  </div>
                </div>

                <!-- AI Cognitive Reasoning Rationale (判斷依據) -->
                <div v-if="msg.analysis_metadata.cognitive_reasoning" class="p-2 bg-light rounded-3 border border-stone-200 mb-2 fs-8 text-secondary">
                  <strong class="text-dark d-block mb-1">Cognitive Analysis Rationale:</strong>
                  <span>{{ msg.analysis_metadata.cognitive_reasoning }}</span>
                </div>

                <!-- 8 Cognitive Function Scores Spectrum Badges -->
                <div v-if="msg.analysis_metadata.scores" class="d-flex flex-wrap gap-1 mb-3">
                  <span
                    v-for="(scoreVal, fnKey) in msg.analysis_metadata.scores"
                    :key="fnKey"
                    :class="[
                      'badge border font-monospace fs-8',
                      fnKey === msg.analysis_metadata.primary_function
                        ? 'bg-orange-subtle text-orange border-orange-subtle fw-bold'
                        : 'bg-light text-secondary border-stone-200'
                    ]"
                  >
                    {{ fnKey }}: {{ scoreVal }}
                  </span>
                </div>

                <!-- Per-Message Embedded Mini Radar Chart -->
                <div v-if="msg.analysis_metadata.scores" class="my-2">
                  <small class="text-secondary fw-semibold d-block mb-1 fs-8">Message Cognitive Spectrum Radar:</small>
                  <RadarChart :cognitive-scores="msg.analysis_metadata.scores" height="180px" />
                </div>
              </div>
            </div>
          </div>

          <!-- Guest Trial Limit Reached Banner -->
          <div v-if="isGuestLimitReached" class="p-4 bg-orange-subtle border border-orange-subtle rounded-4 text-center my-3 shadow-sm">
            <div class="d-inline-flex p-3 rounded-circle bg-white text-orange border border-orange-subtle mb-3">
              <svg class="bi bi-lock" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
              </svg>
            </div>
            <h5 class="fw-bold text-dark mb-2">Guest Free Trial Limit Reached (3/3 Messages)</h5>
            <p class="fs-7 text-secondary max-w-md mx-auto mb-3">You have used all 3 free trial statements. Log in or create a free account to unlock unlimited 8-cognitive function counseling history & continuous rotation guidance.</p>
            <div class="d-flex align-items-center justify-content-center gap-2">
              <a href="/login" class="btn btn-outline-orange rounded-pill px-4 btn-sm fw-semibold">Log In</a>
              <a href="/register" class="btn btn-orange rounded-pill px-4 btn-sm fw-semibold shadow-sm">Register Free Account</a>
            </div>
          </div>

          <!-- Loading Indicator -->
          <div v-if="isLoading" class="d-flex justify-content-start">
            <div class="bg-white border border-stone-200 rounded-4 p-3 d-flex align-items-center gap-2 shadow-sm">
              <div class="spinner-border spinner-border-sm text-orange" role="status"></div>
              <span class="fs-7 text-secondary">Analyzing 8 cognitive functions & rotation vector...</span>
            </div>
          </div>
        </div>

        <!-- Footer Input Bar -->
        <div class="card-footer bg-light border-top border-stone-200 p-3">
          <form @submit.prevent="sendMessage" class="d-flex gap-2">
            <input
              v-model="inputMessage"
              type="text"
              :placeholder="isGuestLimitReached ? 'Guest free trial limit reached (3/3). Please log in to continue...' : 'Type your message or concern...'"
              :disabled="isLoading || isGuestLimitReached"
              class="form-control bg-white text-dark border-stone-300 rounded-pill px-4"
            />
            <button
              type="submit"
              :disabled="isLoading || !inputMessage.trim() || isGuestLimitReached"
              class="btn btn-orange rounded-pill px-4 fw-semibold d-flex align-items-center gap-2 shadow-sm"
            >
              <span>Send</span>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Sidebar Dashboard (4 Columns) -->
    <div class="col-lg-4 d-flex flex-column gap-4">
      <!-- Cumulative Session Average Radar Card -->
      <div class="card bg-white border border-stone-200 shadow-sm rounded-4 p-3">
        <div class="card-body">
          <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <span class="text-orange">
              <svg class="bi bi-pie-chart" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M7.5 1.018a7 7 0 0 0-4.79 11.566L7.5 7.793zm1 0V7.5h6.482A7 7 0 0 0 8.5 1.018M14.982 8.5H8.207l-4.79 4.79A7 7 0 0 0 14.982 8.5M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8"/>
              </svg>
            </span>
            <span>8-Cognitive Function Radar</span>
          </h5>
          <p class="card-text fs-7 text-secondary mb-3">Cumulative session average spectrum tracking Ni, Ne, Si, Se, Ti, Te, Fi, Fe across all statements.</p>
          
          <!-- Radar Chart showing Session Cumulative Average -->
          <RadarChart :cognitive-scores="averageScores" height="260px" />
        </div>
      </div>

      <!-- State Summary Card -->
      <div class="card bg-white border border-stone-200 shadow-sm rounded-4 p-3">
        <div class="card-body">
          <h6 class="card-title fw-bold text-dark mb-3">Session Cognitive Summary</h6>
          <ul class="list-group list-group-flush fs-7">
            <li class="list-group-item bg-transparent text-dark border-stone-200 d-flex justify-content-between py-2 px-0">
              <span class="text-secondary">Primary Function</span>
              <span class="fw-bold text-orange font-monospace">{{ latestAnalysis.primary_function || 'Awaiting input' }}</span>
            </li>
            <li class="list-group-item bg-transparent text-dark border-stone-200 d-flex justify-content-between py-2 px-0">
              <span class="text-secondary">Rotation Vector</span>
              <span class="text-orange font-monospace fw-bold">{{ latestAnalysis.rotation_details ? latestAnalysis.rotation_details.vector : 'Fi → Fe (Bridge) → Te (Target)' }}</span>
            </li>
            <li class="list-group-item bg-transparent text-dark border-stone-200 d-flex justify-content-between py-2 px-0">
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
import { ref, computed, onMounted, nextTick } from 'vue';
import axios from 'axios';
import RadarChart from './RadarChart.vue';

const inputMessage = ref('');
const messages = ref([]);
const isLoading = ref(false);
const sessionToken = ref(localStorage.getItem('counseling_session_token') || null);
const dominantFunction = ref('');
const messagesContainer = ref(null);

// Guest free trial tracking state (Max 3 messages for unauthenticated guests)
const isGuest = ref(false);
const guestUserMsgCount = ref(0);

const isGuestLimitReached = computed(() => {
  return isGuest.value && guestUserMsgCount.value >= 3;
});

const currentScores = ref({
  Ni: 50, Ne: 50, Si: 50, Se: 50,
  Ti: 50, Te: 50, Fi: 50, Fe: 50
});

const latestAnalysis = ref({
  primary_function: '',
  secondary_function: '',
  suggested_rotation: '',
  emotional_clarity_score: 0.8,
  rotation_details: null
});

// Cognitive Rotation Vector Matrix Reference
const selectedRotationFn = ref('Fi');

const rotationMatrixMap = {
  Fi: {
    bridge: 'Fe', target: 'Te',
    vector: 'Fi → Fe (Bridge) → Te (Target)',
    strategy: 'First express Fe active empathy and emotional warmth to soothe Fi self-blame, then guide student toward Te clear, structured execution steps.'
  },
  Ti: {
    bridge: 'Ne', target: 'Se',
    vector: 'Ti → Ne (Bridge) → Se (Target)',
    strategy: 'First use Ne to open up fresh possibilities breaking Ti logic paralysis, then direct attention to Se present sensory grounding.'
  },
  Ni: {
    bridge: 'Fi', target: 'Se',
    vector: 'Ni → Fi (Bridge) → Se (Target)',
    strategy: 'First affirm inner courage with Fi, then ground catastrophic Ni future anxiety into immediate Se reality.'
  },
  Si: {
    bridge: 'Fe', target: 'Ne',
    vector: 'Si → Fe (Bridge) → Ne (Target)',
    strategy: 'First provide Fe warm social reassurance to break Si failure memories, then inspire Ne fresh positive options.'
  },
  Fe: {
    bridge: 'Ni', target: 'Fi',
    vector: 'Fe → Ni (Bridge) → Fi (Target)',
    strategy: 'First clarify long-term vision with Ni, then reconnect student back to their authentic inner Fi values.'
  },
  Te: {
    bridge: 'Se', target: 'Ti',
    vector: 'Te → Se (Bridge) → Ti (Target)',
    strategy: 'First pause frantic Te rushing with Se deep breaths, then encourage Ti deep conceptual reflection.'
  },
  Ne: {
    bridge: 'Te', target: 'Ni',
    vector: 'Ne → Te (Bridge) → Ni (Target)',
    strategy: 'First organize scattered Ne ideas using Te task priority, then narrow down into one focused Ni vision.'
  },
  Se: {
    bridge: 'Si', target: 'Ti',
    vector: 'Se → Si (Bridge) → Ti (Target)',
    strategy: 'First anchor overwhelming Se stress to Si steady routines, then analyze calm logic with Ti.'
  }
};

const activeRotationInfo = computed(() => {
  return rotationMatrixMap[selectedRotationFn.value] || rotationMatrixMap['Fi'];
});

// Helper to retrieve guaranteed Cognitive Rotation details for any assistant message
const getRotationForMsg = (msg) => {
  if (msg.analysis_metadata && msg.analysis_metadata.rotation_details) {
    return msg.analysis_metadata.rotation_details;
  }
  const primary = (msg.analysis_metadata && msg.analysis_metadata.primary_function) || 'Fi';
  const info = rotationMatrixMap[primary] || rotationMatrixMap['Fi'];
  return {
    primary,
    bridge: info.bridge,
    target: info.target,
    vector: `${primary} → ${info.bridge} (Bridge) → ${info.target} (Target)`,
    strategy: info.strategy
  };
};

// Compute cumulative session average of 8 cognitive function scores across all messages
const averageScores = computed(() => {
  const functions = ['Ni', 'Ne', 'Si', 'Se', 'Ti', 'Te', 'Fi', 'Fe'];
  const totals = { Ni: 0, Ne: 0, Si: 0, Se: 0, Ti: 0, Te: 0, Fi: 0, Fe: 0 };
  let count = 0;

  messages.value.forEach(msg => {
    if (msg.analysis_metadata) {
      const scores = msg.analysis_metadata.cognitive_scores || msg.analysis_metadata.scores;
      if (scores) {
        functions.forEach(fn => {
          totals[fn] += Number(scores[fn] || 0);
        });
        count++;
      }
    }
  });

  if (count === 0) return currentScores.value;

  const averages = {};
  functions.forEach(fn => {
    averages[fn] = Math.round(totals[fn] / count);
  });
  return averages;
});

// Fetch history
const fetchHistory = async () => {
  try {
    const res = await axios.get('/api/chat/history', {
      params: { session_token: sessionToken.value }
    });
    if (res.data.status === 'success') {
      messages.value = res.data.messages || [];
      dominantFunction.value = res.data.dominant_function || '';
      isGuest.value = Boolean(res.data.is_guest);
      guestUserMsgCount.value = Number(res.data.guest_user_msg_count || 0);

      if (dominantFunction.value && rotationMatrixMap[dominantFunction.value]) {
        selectedRotationFn.value = dominantFunction.value;
      }
      
      const lastAssistantMsg = [...messages.value].reverse().find(m => m.role === 'assistant');
      if (lastAssistantMsg && lastAssistantMsg.analysis_metadata) {
        latestAnalysis.value = lastAssistantMsg.analysis_metadata;
        const scores = lastAssistantMsg.analysis_metadata.cognitive_scores || lastAssistantMsg.analysis_metadata.scores;
        if (scores) {
          currentScores.value = scores;
        }
        if (lastAssistantMsg.analysis_metadata.primary_function && rotationMatrixMap[lastAssistantMsg.analysis_metadata.primary_function]) {
          selectedRotationFn.value = lastAssistantMsg.analysis_metadata.primary_function;
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
  if (!text || isLoading.value || isGuestLimitReached.value) return;

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
      isGuest.value = Boolean(res.data.is_guest);
      guestUserMsgCount.value = Number(res.data.guest_user_msg_count || 0);

      const payload = res.data.data;
      const userMsg = payload.user_message;
      const assistantMsg = payload.assistant_message;

      if (userMsg && userMsg.analysis_metadata) {
        const lastUserIdx = messages.value.map(m => m.role).lastIndexOf('user');
        if (lastUserIdx !== -1) {
          messages.value[lastUserIdx] = userMsg;
        }
      }

      messages.value.push(assistantMsg);

      if (payload.latest_analysis) {
        latestAnalysis.value = payload.latest_analysis;
        const scores = payload.latest_analysis.cognitive_scores || payload.latest_analysis.scores;
        if (scores) {
          currentScores.value = scores;
        }
        if (payload.latest_analysis.primary_function) {
          dominantFunction.value = payload.latest_analysis.primary_function;
          if (rotationMatrixMap[dominantFunction.value]) {
            selectedRotationFn.value = payload.latest_analysis.primary_function;
          }
        }
      }
    }
  } catch (err) {
    console.error('Send message failed:', err);
    if (err.response && err.response.status === 403) {
      isGuest.value = true;
      guestUserMsgCount.value = 3;
      messages.value.pop(); // Remove unsent draft message from UI stream
    } else {
      messages.value.push({
        role: 'assistant',
        content: 'An error occurred during communication. Please try again later.'
      });
    }
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
