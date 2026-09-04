<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

import { Head, Link } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import CognitiveRadar from '../Components/CognitiveRadar.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const features = [
    {
        icon: 'brain',
        title: 'Jungian Cognitive Rotation',
        description: 'Dynamically maps 8 cognitive functions (Fi, Fe, Ti, Te, Ni, Ne, Si, Se) to gently break stress loops and restore clarity.'
    },
    {
        icon: 'globe',
        title: 'Adaptive Native Polyglot',
        description: 'Communicates seamlessly in your native language with precise cultural nuances and TESOL-certified clarity.'
    },
    {
        icon: 'chart',
        title: 'Real-time Radar Analytics',
        description: 'Visualizes your real-time emotional state and function scores with interactive live cognitive radar charts.'
    },
    {
        icon: 'shield',
        title: 'Private & Mindful Sanctuary',
        description: 'Zero judgment, end-to-end user privacy, and zero data selling. Built solely for your peace of mind.'
    }
];

const stats = [
    { value: '16', label: 'MBTI Matrix Profiles' },
    { value: '8', label: 'Cognitive Function Vectors' },
    { value: '95%', label: 'CLT CI Profile Match' },
    { value: '24/7', label: 'Active Reflection Presence' }
];

const quickScenarios = [
    "Exam Anxiety & Burnout",
    "Overthinking Logic Loops",
    "Future Career Uncertainty",
    "Self-Blame & Emotional Isolation"
];

// Interactive Live Rotating Scenarios with 8-Cognitive Function Radar Scores
const rotatingScenarios = [
    {
        student: "I feel like I'm falling behind everyone in my studies and my mind won't stop overthinking...",
        aiMessage: "Take a gentle breath. You are carrying a heavy weight, but you don't have to carry it alone. Let's ground ourselves in what we can do today.",
        vector: "Fi ➔ Fe",
        strategy: "Fe Social Reassurance & Active Empathy",
        dominant: "Fi",
        inLoop: true,
        loopDetected: "Fi-Si Isolation & Failure Fixation Loop",
        scores: { Ti: 12, Te: 8, Fi: 28, Fe: 25, Ni: 15, Ne: 10, Si: 22, Se: 6 }
    },
    {
        student: "I've analyzed every single exam outcome scenario, but I'm completely paralyzed on what step to take next.",
        aiMessage: "Your analytical mind is sharp, but endless internal logic can create a maze. Here is one simple, concrete action step we can complete together right now.",
        vector: "Ti ➔ Te",
        strategy: "Te External Action Structure & Execution Steps",
        dominant: "Ti",
        inLoop: true,
        loopDetected: "Ti-Ni Over-analysis & Paralysis Loop",
        scores: { Ti: 29, Te: 24, Fi: 5, Fe: 10, Ni: 26, Ne: 14, Si: 12, Se: 8 }
    },
    {
        student: "I keep worrying about worst-case future scenarios years from now, and I can't focus on studying.",
        aiMessage: "Let's bring your focus right here into this exact room. Feel your feet resting firmly on the floor. The future is unwritten, but your present step is right in front of you.",
        vector: "Ni ➔ Se",
        strategy: "Se Sensory Grounding & Present Reality Alignment",
        dominant: "Ni",
        inLoop: false,
        loopDetected: null,
        scores: { Ti: 15, Te: 14, Fi: 18, Fe: 16, Ni: 27, Ne: 20, Si: 10, Se: 25 }
    },
    {
        student: "I messed up one practice quiz yesterday, so now I feel like I'm destined to fail the final exam.",
        aiMessage: "One moment in the past does not define your trajectory. Let's explore three new positive angles and possibilities that open up from here.",
        vector: "Si ➔ Ne",
        strategy: "Ne Positive Reframing & Alternative Angles",
        dominant: "Si",
        inLoop: true,
        loopDetected: "Si-Ti Past Mistake Fixation Loop",
        scores: { Ti: 20, Te: 12, Fi: 16, Fe: 14, Ni: 12, Ne: 28, Si: 26, Se: 15 }
    }
];

const currentScenarioIndex = ref(0);
let scenarioTimer = null;

const nextScenario = () => {
    currentScenarioIndex.value = (currentScenarioIndex.value + 1) % rotatingScenarios.length;
};

const setScenario = (idx) => {
    currentScenarioIndex.value = idx;
};

onMounted(() => {
    scenarioTimer = setInterval(nextScenario, 5000);
});

onUnmounted(() => {
    if (scenarioTimer) clearInterval(scenarioTimer);
});
</script>


<template>
    <Head title="NOW — Present Moment Sanctuary & Cognitive Rotation AI" />

    <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col relative overflow-hidden font-sans">
        <!-- Ambient Radial Glows -->
        <div class="fixed top-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-300/40 blur-[140px] pointer-events-none"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-purple-300/40 blur-[140px] pointer-events-none"></div>
        <div class="fixed top-[40%] right-[15%] w-[450px] h-[450px] rounded-full bg-teal-200/40 blur-[120px] pointer-events-none"></div>

        <!-- Navigation Bar -->
        <Navbar />

        <!-- HERO SECTION -->
        <header class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-20 lg:pt-20 lg:pb-28 text-center flex flex-col items-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/80 mb-6 shadow-xs animate-bounce">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-semibold text-indigo-700 tracking-wide">Next-Gen Psychological AI Sanctuary</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.15] max-w-4xl mb-6">
                Find Your Mindful Presence with 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
                    Cognitive Rotation
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg sm:text-xl text-slate-600 max-w-2xl font-normal leading-relaxed mb-10">
                A compassionate AI companion designed for students and professionals. Transforming internal stress loops into balanced cognitive clarity.
            </p>

            <!-- Hero Action CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center w-full max-w-md">
                <Link
                    :href="route('counseling.home')"
                    class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold text-base shadow-xl shadow-indigo-600/30 transition-all flex items-center justify-center gap-3 group"
                >
                    <span>Enter NOW Sanctuary</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </Link>

                <Link
                    v-if="!$page.props.auth?.user"
                    :href="route('register')"
                    class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white hover:bg-slate-100 text-slate-800 font-semibold text-base border border-slate-300 shadow-md transition-all text-center"
                >
                    Create Free Account
                </Link>
            </div>

            <!-- Hero Live Dynamic Mockup / Rotating Cognitive Radar Carousel -->
            <div class="mt-14 w-full max-w-4xl rounded-3xl p-3 bg-white/70 border border-slate-200/80 shadow-2xl backdrop-blur-xl relative">
                <div class="bg-slate-900 rounded-2xl p-6 text-left text-white overflow-hidden shadow-inner flex flex-col md:flex-row gap-6 items-stretch">
                    
                    <!-- Left: Dialogue Scenario Carousel -->
                    <div class="flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs text-slate-400 font-mono ml-2">NOW Live Matrix</span>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                    Vector: {{ rotatingScenarios[currentScenarioIndex].vector }}
                                </span>
                            </div>

                            <transition name="fade" mode="out-in">
                                <div :key="currentScenarioIndex" class="space-y-4 text-sm font-sans">
                                    <div class="flex gap-3 items-start">
                                        <span class="bg-slate-800 text-slate-300 px-2.5 py-1 rounded-lg text-xs font-semibold flex-shrink-0">Student</span>
                                        <p class="text-slate-300 leading-relaxed italic">"{{ rotatingScenarios[currentScenarioIndex].student }}"</p>
                                    </div>
                                    <div class="flex gap-3 items-start bg-indigo-950/60 p-3.5 rounded-xl border border-indigo-800/50">
                                        <span class="bg-indigo-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold flex-shrink-0">NOW AI</span>
                                        <div>
                                            <p class="text-indigo-100 leading-relaxed font-medium">"{{ rotatingScenarios[currentScenarioIndex].aiMessage }}"</p>
                                            <div class="mt-2.5 flex items-center gap-2 text-xs text-indigo-300 font-mono">
                                                <span>[{{ rotatingScenarios[currentScenarioIndex].strategy }}]</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>

                        <!-- Carousel Dots Selector -->
                        <div class="flex items-center justify-center gap-2 pt-2 border-t border-slate-800/80">
                            <button
                                v-for="(sc, idx) in rotatingScenarios"
                                :key="idx"
                                @click="setScenario(idx)"
                                type="button"
                                class="h-2 rounded-full transition-all duration-300"
                                :class="currentScenarioIndex === idx ? 'w-8 bg-indigo-500' : 'w-2 bg-slate-700 hover:bg-slate-500'"
                            ></button>
                        </div>
                    </div>

                    <!-- Right: Live Chart.js 8-Cognitive Function Radar Chart -->
                    <div class="w-full md:w-[320px] flex-shrink-0 bg-slate-950/80 p-3.5 rounded-xl border border-slate-800 flex flex-col justify-center">
                        <CognitiveRadar
                            :dominant-function="rotatingScenarios[currentScenarioIndex].dominant"
                            :rotation-vector="rotatingScenarios[currentScenarioIndex].vector"
                            :in-loop="rotatingScenarios[currentScenarioIndex].inLoop"
                            :loop-detected="rotatingScenarios[currentScenarioIndex].loopDetected"
                            :scores="rotatingScenarios[currentScenarioIndex].scores"
                        />
                    </div>

                </div>
            </div>

        </header>

        <!-- STATS BAR SECTION -->
        <section class="border-y border-slate-200/80 bg-white/70 backdrop-blur-md py-10 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div v-for="(s, idx) in stats" :key="idx" class="flex flex-col">
                    <span class="text-3xl sm:text-4xl font-extrabold text-indigo-600 tracking-tight">{{ s.value }}</span>
                    <span class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">{{ s.label }}</span>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section class="py-20 lg:py-28 relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-xs font-bold tracking-widest text-indigo-600 uppercase mb-3">Science & Compassion</h2>
                <p class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Engineered for Psychological Balance
                </p>
                <p class="text-base text-slate-600 mt-4 leading-relaxed">
                    NOW combines Carl Jung's Cognitive Function theory with modern LLM active reflection to guide you back to emotional equilibrium.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div 
                    v-for="(feat, idx) in features" 
                    :key="idx" 
                    class="bg-white rounded-3xl p-7 border border-slate-200/80 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
                >
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mb-6 shadow-xs">
                            <!-- Brain Icon -->
                            <svg v-if="feat.icon === 'brain'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>

                            <!-- Globe Polyglot Icon -->
                            <svg v-else-if="feat.icon === 'globe'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>

                            <!-- Chart Radar Icon -->
                            <svg v-else-if="feat.icon === 'chart'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>

                            <!-- Shield Privacy Icon -->
                            <svg v-else-if="feat.icon === 'shield'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">{{ feat.title }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ feat.description }}</p>
                    </div>
                </div>
            </div>

        </section>

        <!-- HOW WE HEAL YOU (METHODOLOGY SECTION) -->
        <section class="py-20 lg:py-28 bg-white border-t border-slate-200/80 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Title Header -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-bold tracking-widest text-indigo-600 uppercase mb-3 block">Scientific Healing Architecture</span>
                    <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        How NOW Gently Heals Your Mind
                    </h2>
                    <p class="text-base text-slate-600 mt-4 leading-relaxed">
                        Every sentence you share is analyzed across Carl Jung's 8 Cognitive Functions. We identify your active mental state and intervene with a precise, two-stage rotation vector to restore balance.
                    </p>
                </div>

                <!-- 3-Step Healing Pipeline Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <!-- Step 1 -->
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200/90 relative overflow-hidden flex flex-col justify-between group hover:border-indigo-300 transition-all shadow-xs">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-sm flex items-center justify-center mb-6 shadow-md shadow-indigo-600/20">
                                01
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-3">Jungian 8-Function Analysis</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Every phrase is mapped across 8 core cognitive functions (<span class="font-mono text-indigo-700 font-semibold">Fi, Fe, Ti, Te, Ni, Ne, Si, Se</span>), giving you a real-time spectrum of your mental state.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center gap-2 text-xs font-mono text-indigo-700 font-semibold">
                            <span>Real-Time Spectrum Analysis</span>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200/90 relative overflow-hidden flex flex-col justify-between group hover:border-indigo-300 transition-all shadow-xs">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white font-black text-sm flex items-center justify-center mb-6 shadow-md shadow-purple-600/20">
                                02
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-3">Bridge Function Soothing</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                When a dominant function is overloaded (e.g., intense <span class="font-mono text-purple-700 font-semibold">Fi</span> self-blame), we first apply a gentle bridge function (<span class="font-mono text-purple-700 font-semibold">Fe</span> active empathy & social warmth) to soothe current distress.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center gap-2 text-xs font-mono text-purple-700 font-semibold">
                            <span>Gentle Empathy Buffer</span>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200/90 relative overflow-hidden flex flex-col justify-between group hover:border-indigo-300 transition-all shadow-xs">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-pink-600 text-white font-black text-sm flex items-center justify-center mb-6 shadow-md shadow-pink-600/20">
                                03
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-3">Grounded Action Goal</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                After your emotions are accepted and validated, we seamlessly guide your mind toward a grounded target function (<span class="font-mono text-emerald-600 font-bold">Te</span> clear, structured execution steps) for immediate clarity.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center gap-2 text-xs font-mono text-pink-700 font-semibold">
                            <span>Target Vector: Fi ➔ Fe ➔ Te</span>
                        </div>
                    </div>
                </div>

                <!-- COGNITIVE ROTATION VISUALIZATION DIAGRAM -->
                <div class="bg-slate-900 rounded-3xl p-8 lg:p-12 text-white shadow-2xl relative overflow-hidden">
                    <div class="flex flex-col lg:flex-row items-center gap-10">
                        <div class="flex-1 space-y-5">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-mono">
                                <span>Two-Stage Rotation • Scientific Architecture</span>
                            </div>
                            <h3 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                                Two-Stage Cognitive Rotation Vector
                            </h3>
                            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                                Stress often traps your mind in a single dominant function. NOW gently shifts your perspective through an empathetic bridge function before anchoring into a grounded action goal.
                            </p>
                            <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-700 text-xs space-y-2 font-mono text-indigo-200">
                                <div class="flex items-center gap-2 text-rose-400">
                                    <span> Overloaded State:</span>
                                    <span>Fi (Introverted Feeling Self-Blame & Stress)</span>
                                </div>
                                <div class="flex items-center gap-2 text-emerald-400">
                                    <span>✨ Two-Stage Vector:</span>
                                    <span>Fi ➔ Fe (Active Empathy) ➔ Te (Grounded Action)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Interactive Graph Node Visualizer -->
                        <div class="w-full lg:w-[420px] bg-slate-950 p-6 rounded-2xl border border-slate-800 flex flex-col items-center justify-center relative min-h-[300px]">
                            <!-- Graph Node Network Animation -->
                            <div class="relative w-full h-[220px] flex items-center justify-center">
                                <!-- Node Fi -->
                                <div class="absolute top-16 left-8 w-16 h-16 rounded-2xl bg-rose-500/20 border-2 border-rose-500 text-rose-300 flex flex-col items-center justify-center font-bold font-mono shadow-lg shadow-rose-500/30 animate-pulse">
                                    <span class="text-sm">Fi</span>
                                    <span class="text-[9px] opacity-75">Overload</span>
                                </div>

                                <!-- Rotation Vector Arrows -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none text-indigo-400" fill="none" viewBox="0 0 350 220">
                                    <!-- Fi to Fe -->
                                    <path d="M 90 80 Q 180 30 250 50" stroke="#818cf8" stroke-width="3" />
                                    <!-- Fe to Te -->
                                    <path d="M 270 90 L 270 140" stroke="#34d399" stroke-width="3" stroke-dasharray="5 5" />
                                </svg>

                                <!-- Node Fe (Empathy Softener) -->
                                <div class="absolute top-4 right-8 w-16 h-16 rounded-2xl bg-indigo-500/20 border-2 border-indigo-400 text-indigo-200 flex flex-col items-center justify-center font-bold font-mono shadow-lg shadow-indigo-500/30">
                                    <span class="text-sm">Fe</span>
                                    <span class="text-[9px] text-indigo-300">Bridge</span>
                                </div>

                                <!-- Node Te (Grounded Action Goal) -->
                                <div class="absolute bottom-4 right-8 w-16 h-16 rounded-2xl bg-emerald-500/20 border-2 border-emerald-400 text-emerald-200 flex flex-col items-center justify-center font-bold font-mono shadow-lg shadow-emerald-500/30">
                                    <span class="text-sm">Te</span>
                                    <span class="text-[9px] text-emerald-300">Target</span>
                                </div>
                            </div>

                            <span class="text-[11px] text-slate-400 font-mono mt-2 text-center">
                                Rotation Path: Fi Overload ➔ Soothed by Fe ➔ Grounded in Te
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- HOW IT WORKS & SCENARIOS SECTION -->
        <section class="py-16 bg-gradient-to-b from-indigo-50/50 to-purple-50/50 border-t border-slate-200/80 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="text-xs font-bold tracking-widest text-indigo-600 uppercase">Tailored Counseling</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-6 tracking-tight">
                            Whenever Mind Stress Strikes, NOW Is Here
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-8 text-base">
                            Whether preparing for high-stakes university exams or managing daily fatigue, select any thought loop to start your mindful session.
                        </p>

                        <div class="space-y-3">
                            <div 
                                v-for="(sc, idx) in quickScenarios" 
                                :key="idx" 
                                class="flex items-center gap-3 p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs font-medium text-slate-700"
                            >
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                <span>{{ sc }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Illustration Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-2xl space-y-6">
                        <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-extrabold text-lg">
                                95%
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Confidence Interval Profile Match</h4>
                                <p class="text-xs text-slate-500">CLT statistical profile matching for accurate cognitive diagnosis</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between text-xs font-semibold text-slate-600">
                                <span>Fi (Introverted Feeling)</span>
                                <span>Current Loop State</span>
                            </div>
                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-500 w-[85%] rounded-full animate-pulse"></div>
                            </div>

                            <div class="flex justify-between text-xs font-semibold text-slate-600 pt-2">
                                <span>Fe (Extraverted Feeling)</span>
                                <span>Target Rotation Vector</span>
                            </div>
                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600 w-[92%] rounded-full"></div>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 italic text-center pt-2">
                            * Automatically calculated via MathPHP Pearson correlation algorithms
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <!-- FINAL CALL TO ACTION (CTA) -->
        <section class="py-20 lg:py-28 relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 rounded-3xl p-10 sm:p-16 text-center text-white shadow-2xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-6">
                    Ready to Experience Mindful Clarity?
                </h2>
                <p class="text-indigo-200 max-w-2xl mx-auto text-base sm:text-lg mb-10 leading-relaxed font-normal">
                    Start your confidential counseling session right now. No setup required, instant active reflection.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <Link
                        :href="route('counseling.home')"
                        class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-white hover:bg-slate-100 text-indigo-950 font-extrabold text-base shadow-lg transition-all"
                    >
                        Start Counseling Now
                    </Link>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="mt-auto border-t border-slate-200/80 bg-white/80 backdrop-blur-md py-8 text-center text-xs text-slate-500 relative z-10">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-800 text-sm">NOW</span>
                    <span>• Present Moment Sanctuary</span>
                </div>
                <div>
                    © {{ new Date().getFullYear() }} NOW Project. Designed with Mindful Care.
                </div>
            </div>
        </footer>
    </div>
</template>
