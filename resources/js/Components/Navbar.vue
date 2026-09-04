<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
  <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <!-- Brand Title & Home Link -->
        <div class="flex items-center gap-6">
          <Link :href="route('counseling.home')" class="group flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
              <span class="text-white font-black text-xs tracking-wider">NOW</span>
            </div>
            <div class="flex flex-col">
              <span class="font-bold text-lg text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">NOW</span>
              <span class="text-[10px] text-slate-500 font-medium hidden md:inline">Present Moment Sanctuary</span>
            </div>
          </Link>
          
          <div class="hidden sm:flex items-center gap-2 text-sm font-medium">
            <Link 
              :href="route('counseling.home')" 
              class="text-slate-600 hover:text-slate-900 transition-colors px-3.5 py-1.5 rounded-xl border border-transparent"
              :class="{ 'bg-indigo-50 border-indigo-200/80 text-indigo-700 font-semibold shadow-xs': route().current('counseling.home') }"
            >
              NOW Counseling
            </Link>
            
            <Link 
              v-if="user" 
              :href="route('dashboard')" 
              class="text-slate-600 hover:text-slate-900 transition-colors px-3.5 py-1.5 rounded-xl border border-transparent"
              :class="{ 'bg-indigo-50 border-indigo-200/80 text-indigo-700 font-semibold shadow-xs': route().current('dashboard') }"
            >
              Member Statistics
            </Link>
          </div>
        </div>

        <!-- Auth Navigation Action Buttons -->
        <div class="flex items-center gap-3 text-sm">
          <template v-if="user">
            <span class="text-slate-700 font-medium text-xs hidden sm:inline bg-slate-100 px-3 py-1 rounded-lg border border-slate-200">
              👤 {{ user.name }}
            </span>
            <Link 
              :href="route('profile.edit')" 
              class="text-slate-700 hover:text-slate-900 px-3.5 py-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 transition-all text-xs font-medium shadow-xs"
            >
              Profile
            </Link>
            <Link 
              :href="route('logout')" 
              method="post" 
              as="button" 
              class="bg-rose-50 hover:bg-rose-100 text-rose-700 px-3.5 py-1.5 rounded-xl font-medium text-xs transition-all border border-rose-200 shadow-xs"
            >
              Log Out
            </Link>
          </template>
          
          <template v-else>
            <Link 
              :href="route('login')" 
              class="text-slate-700 hover:text-slate-900 px-3.5 py-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 transition-all text-xs font-medium shadow-xs"
            >
              Log in
            </Link>
            <Link 
              :href="route('register')" 
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-xl font-medium text-xs transition-all shadow-md shadow-indigo-600/20"
            >
              Register
            </Link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>


