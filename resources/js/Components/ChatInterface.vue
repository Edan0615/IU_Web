<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import CognitiveRadar from './CognitiveRadar.vue';

const props = defineProps({
  initialMessages: {
    type: Array,
    default: () => []
  },
  sessionToken: {
    type: String,
    required: true
  }
});

const messages = ref([...props.initialMessages]);
const inputMessage = ref('');
const isLoading = ref(false);
const chatContainer = ref(null);

// Suggested quick prompts for mindful reflection
const quickPrompts = [
  "I'm feeling overwhelmed by upcoming exams...",
  "My mind feels trapped in a repetitive loop...",
  "I need help finding emotional clarity right now.",
  "What cognitive function pattern am I experiencing?"
];

const selectPrompt = (promptText) => {
  inputMessage.value = promptText;
};

// Map to track expanded state of cognitive radar per message ID
const expandedMessages = ref({});

const toggleExpand = (msgId) => {
  expandedMessages.value[msgId] = !expandedMessages.value[msgId];
};

const scrollToBottom = () => {
  nextTick(() => {
    if (chatContainer.value) {
      chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
  });
};

const sendUserMessage = async () => {
  const content = inputMessage.value.trim();
  if (!content || isLoading.value) return;

  // Add optimistic user message
  const userMsgId = Date.now();
  messages.value.push({
    id: userMsgId,
    role: 'user',
    content: content,
    created_at: new Date().toISOString()
  });

  inputMessage.value = '';
  isLoading.value = true;
  scrollToBottom();

  try {
    const res = await axios.post('/api/chat/send', {
      message: content,
      session_token: props.sessionToken
    });

    const data = res.data;
    if (data.assistant_message) {
      messages.value.push(data.assistant_message);
    }
  } catch (error) {
    console.error('Failed to send message:', error);
    messages.value.push({
      id: Date.now(),
      role: 'assistant',
      content: 'I felt a brief disconnection, but I am right here with you. Please tell me again what was on your mind.',
      created_at: new Date().toISOString()
    });
  } finally {
    isLoading.value = false;
    scrollToBottom();
  }
};

onMounted(() => {
  scrollToBottom();
});
</script>

<template>
  <div class="max-w-4xl mx-auto my-auto py-2 sm:py-4 px-2 sm:px-4 w-full flex-1 flex flex-col justify-center">
    <!-- Header Banner -->
    <div class="text-center mb-6 sm:mb-8">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/80 mb-3 shadow-xs">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span class="text-xs font-semibold text-indigo-700 tracking-wide">Mindful Presence • Active Reflection</span>
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 via-indigo-900 to-purple-900 tracking-tight mb-2">
        Now Sanctuary
      </h1>
      <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto italic font-normal">
        "The secret to happiness is simple: when you have an apple, just think of this apple."
      </p>
    </div>

    <!-- Main Interactive Chat Glass Card -->
    <div class="glass-panel overflow-hidden flex flex-col h-[680px] shadow-xl relative">
      
      <!-- Messages Conversation Body -->
      <div ref="chatContainer" class="flex-1 p-4 sm:p-6 overflow-y-auto space-y-5 scroll-smooth">
        <!-- Empty State Welcome -->
        <div v-if="messages.length === 0" class="h-full flex flex-col items-center justify-center text-center p-6 my-auto max-w-lg mx-auto">
          <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-200 flex items-center justify-center mb-4 text-2xl shadow-md shadow-indigo-100">
            🧘
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Take a quiet, gentle breath.</h3>
          <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6">
            Exam preparation and life decisions can carry intense emotional weight. You don't have to navigate this alone. Share what's occupying your thoughts.
          </p>

          <!-- Quick Suggestion Prompts -->
          <div class="flex flex-wrap gap-2 justify-center max-w-md">
            <button
              v-for="(prompt, idx) in quickPrompts"
              :key="idx"
              @click="selectPrompt(prompt)"
              type="button"
              class="text-xs px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-indigo-700 hover:bg-indigo-50/80 hover:border-indigo-300 transition-all text-left duration-200 shadow-xs"
            >
              💭 {{ prompt }}
            </button>
          </div>
        </div>

        <!-- Chat Messages -->
        <div 
          v-for="msg in messages" 
          :key="msg.id" 
          class="flex items-start gap-3"
          :class="msg.role === 'user' ? 'justify-end' : 'justify-start'"
        >
          <!-- Assistant Avatar -->
          <div v-if="msg.role === 'assistant'" class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
            ✨
          </div>

          <div :class="msg.role === 'user' ? 'chat-bubble-user' : 'chat-bubble-bot'">
            <div class="whitespace-pre-wrap text-sm leading-relaxed">{{ msg.content }}</div>
            
            <!-- Assistant Message Analysis Expandable Trigger -->
            <div v-if="msg.role === 'assistant' && msg.analysis_metadata" class="mt-3 pt-2.5 border-t border-slate-200/80">
              <button 
                @click="toggleExpand(msg.id)" 
                type="button" 
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-200/80"
              >
                <span>📊 {{ expandedMessages[msg.id] ? 'Hide Cognitive Function Analysis' : 'View 8-Cognitive Function Radar' }}</span>
              </button>

              <!-- Collapsible Cognitive Radar -->
              <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 transform translate-y-0"
                leave-to-class="opacity-0 transform -translate-y-2"
              >
                <div v-if="expandedMessages[msg.id]">
                  <CognitiveRadar 
                    :dominant-function="msg.analysis_metadata.primary_function || 'Fi'"
                    :rotation-vector="msg.analysis_metadata.rotation_vector || null"
                    :in-loop="msg.analysis_metadata.in_loop || false"
                    :loop-detected="msg.analysis_metadata.loop_detected"
                    :emotional-clarity-score="msg.analysis_metadata.emotional_clarity_score || 0.85"
                    :scores="msg.analysis_metadata.scores"
                  />
                </div>
              </transition>
            </div>

            <!-- Cognitive Tags -->
            <div v-if="msg.cognitive_tags" class="mt-2.5 flex flex-wrap gap-1.5">
              <span v-for="tag in msg.cognitive_tags" :key="tag" class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                #{{ tag }}
              </span>
            </div>
          </div>
        </div>

        <!-- Loading Indicator -->
        <div v-if="isLoading" class="flex items-center gap-3 justify-start">
          <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-xs flex-shrink-0 animate-pulse">
            ✨
          </div>
          <div class="chat-bubble-bot flex items-center gap-2.5 py-3">
            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
            <span class="text-xs text-slate-600 font-medium">Reflecting on cognitive state & emotions...</span>
          </div>
        </div>
      </div>

      <!-- Chat Footer Input -->
      <div class="p-3.5 sm:p-4 bg-white/90 border-t border-slate-200/80 backdrop-blur-xl">
        <form @submit.prevent="sendUserMessage" class="flex gap-2.5 items-center">
          <input 
            v-model="inputMessage"
            type="text" 
            class="flex-1 rounded-xl border border-slate-300 bg-slate-50/60 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 placeholder:text-slate-400 shadow-inner"
            placeholder="Express what's on your mind or how you're feeling right now..."
            :disabled="isLoading"
          />
          <button 
            type="submit" 
            class="bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all text-white font-semibold rounded-xl px-5 py-3 text-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center gap-2 flex-shrink-0 shadow-md shadow-indigo-600/20"
            :disabled="isLoading || !inputMessage.trim()"
          >
            <span>Send</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </form>
      </div>

    </div>
  </div>
</template>


