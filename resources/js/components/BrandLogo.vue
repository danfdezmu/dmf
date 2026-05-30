<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

type Props = {
    class?: HTMLAttributes['class'];
    alt?: string;
};

withDefaults(defineProps<Props>(), {
    alt: 'DMF',
});
</script>

<template>
    <img
        src="/images/logo-dmf.png"
        :alt="alt"
        :class="class"
    />
</template>
