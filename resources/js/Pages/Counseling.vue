<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import ChatInterface from '../Components/ChatInterface.vue';

const props = defineProps({
  chat: {
    type: Object,
    required: true
  },
  sessionToken: {
    type: String,
    required: true
  }
});
</script>

<template>
  <Head title="NOW Counseling" />
  <div class="counseling-container">
    <!-- Ambient Backdrop Light Orbs (Pastel Light Mode) -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-indigo-200/50 blur-[130px] pointer-events-none animate-ambient-1"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-purple-200/45 blur-[130px] pointer-events-none animate-ambient-2"></div>
    <div class="fixed top-[35%] right-[25%] w-[400px] h-[400px] rounded-full bg-teal-100/60 blur-[110px] pointer-events-none"></div>

    <!-- Navbar Component -->
    <Navbar />

    <!-- Main Content Canvas (Flex Centered) -->
    <main class="relative z-10 flex-1 flex flex-col justify-center items-center py-4 px-3 sm:px-6 w-full my-auto">
      <ChatInterface 
        :initial-messages="chat.messages || []"
        :session-token="sessionToken"
      />
    </main>
  </div>
</template>

