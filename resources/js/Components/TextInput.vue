<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 rounded-xl shadow-xs px-3.5 py-2 text-sm bg-slate-50/50 transition-all"
        v-model="model"
        ref="input"
    />
</template>
