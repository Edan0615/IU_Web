<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center p-4 bg-slate-50 relative overflow-hidden select-none">
        <!-- Ambient Backdrop Light Orbs -->
        <div class="fixed top-[-10%] left-[-10%] w-[450px] h-[450px] rounded-full bg-indigo-200/40 blur-[130px] pointer-events-none animate-ambient-1"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[450px] h-[450px] rounded-full bg-purple-200/35 blur-[130px] pointer-events-none animate-ambient-2"></div>

        <!-- Brand Header Logo -->
        <div class="mb-6 relative z-10 text-center">
            <Link href="/" class="group inline-flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                    <span class="text-white font-black text-base tracking-wider">NOW</span>
                </div>
                <div class="flex flex-col items-center mt-1">
                    <span class="font-extrabold text-xl text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">NOW Sanctuary</span>
                    <span class="text-xs text-slate-500 font-medium">Present Moment Sanctuary</span>
                </div>
            </Link>
        </div>

        <!-- Vertically Centered Auth Card Container -->
        <div class="w-full sm:max-w-md relative z-10 bg-white/90 backdrop-blur-xl border border-slate-200/80 shadow-xl shadow-slate-200/60 rounded-2xl p-6 sm:p-8">
            <slot />
        </div>
    </div>
</template>
