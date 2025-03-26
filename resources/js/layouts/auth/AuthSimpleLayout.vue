<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();

import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';


const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Light' },
    { value: 'dark', Icon: Moon, label: 'Dark' },
] as const;
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div :class="['inline-flex gap-1 rounded-lg bg-neutral-100 dark:bg-neutral-800 p-1'] "
                class="absolute top-0 right-0 mt-4 mr-4">
                <button v-for="{ value, Icon, label } in tabs" :key="value" @click="updateAppearance(value)" :class="[
                    'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                    appearance === value
                        ? 'bg-white shadow-sm text-neutral-900 dark:bg-neutral-700 dark:text-neutral-100'
                        : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-700/60 dark:hover:text-neutral-100',
                ]">
                    <component :is="Icon" class="-ml-1 h-4 w-4" />
                    <span class="ml-1.5 text-sm">{{ label }}</span>
                </button>
            </div>
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">

                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
