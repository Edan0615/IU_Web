<script setup>
import { computed } from 'vue';
import katex from 'katex';
import 'katex/dist/katex.min.css';

const props = defineProps({
  expression: {
    type: String,
    required: true
  },
  displayMode: {
    type: Boolean,
    default: false
  }
});

const htmlContent = computed(() => {
  try {
    return katex.renderToString(props.expression, {
      displayMode: props.displayMode,
      throwOnError: false
    });
  } catch (error) {
    console.error('KaTeX rendering error:', error);
    return props.expression;
  }
});
</script>

<template>
  <span v-html="htmlContent" class="inline-block"></span>
</template>
