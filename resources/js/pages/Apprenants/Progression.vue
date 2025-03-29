<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';

const props = defineProps<{
    progressionData: {
        formation: { id: number; name: string } | null;
        progression: number;
        disciplines: Array<{ id: number; name: string; completed: boolean }>;
    };
}>();

// Date actuelle
const currentDate = new Date().toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});
</script>

<template>
    <MonLayout>
        <div
            class="max-w-5xl mx-auto p-4 sm:p-6 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex flex-col sm:flex-row items-center justify-between">
                <h1
                    class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white flex flex-wrap items-center justify-center sm:justify-start">
                    <i class="fas fa-chart-line mr-2 sm:mr-3 text-teal-500 dark:text-teal-400"></i>
                    Ma Progression
                    <span v-if="props.progressionData.formation"
                        class="ml-2 text-teal-600 dark:text-teal-300 text-lg sm:text-xl">
                        - {{ props.progressionData.formation.name }}
                    </span>
                </h1>
                <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 sm:mt-0">
                    Mis à jour le {{ currentDate }}
                </div>
            </header>

            <!-- Progression globale -->
            <div class="bg-teal-50 dark:bg-teal-900 rounded-xl p-4 sm:p-6 shadow-lg mb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-xs sm:text-sm text-teal-700 dark:text-teal-200 uppercase tracking-wide">
                            Progression Totale</p>
                        <h2
                            class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-teal-900 dark:text-white mt-1 sm:mt-2">
                            {{ props.progressionData.progression }}%
                        </h2>
                    </div>
                    <div class="w-full sm:w-2/3 md:w-1/2">
                        <div class="bg-gray-300 dark:bg-gray-700 rounded-full h-4 sm:h-6 overflow-hidden shadow-inner">
                            <div class="bg-teal-500 dark:bg-teal-400 h-full rounded-full transition-all duration-1000 ease-out relative"
                                :style="{ width: `${props.progressionData.progression}%` }">
                                <span
                                    class="absolute inset-0 flex items-center justify-center text-white text-xs sm:text-sm font-semibold"
                                    v-if="props.progressionData.progression > 30">
                                    {{ props.progressionData.progression }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des disciplines -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6">
                <h3
                    class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-center sm:justify-start">
                    <i class="fas fa-list-check mr-2 text-teal-500 dark:text-teal-400 text-lg"></i> Disciplines
                </h3>
                <div v-if="props.progressionData.disciplines.length" class="space-y-3">
                    <div v-for="discipline in props.progressionData.disciplines" :key="discipline.id"
                        class="p-3 sm:p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 flex flex-col sm:flex-row items-center justify-between gap-2">
                        <div class="flex items-center w-full sm:w-auto">
                            <i :class="discipline.completed ? 'fas fa-check-circle text-green-500 dark:text-green-400' : 'fas fa-circle text-gray-400'"
                                class="mr-2 sm:mr-3 text-lg flex-shrink-0"></i>
                            <p
                                class="text-gray-900 dark:text-white font-medium text-sm sm:text-base break-words w-full">
                                {{ discipline.name }}
                            </p>
                        </div>
                        <span class="text-xs sm:text-sm px-2 sm:px-3 py-1 rounded-full font-semibold flex-shrink-0"
                            :class="discipline.completed ? 'bg-green-500 dark:bg-green-600 text-white' : 'bg-gray-500 dark:bg-gray-600 text-gray-200'">
                            {{ discipline.completed ? 'Terminé' : 'En cours' }}
                        </span>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500 dark:text-gray-400 py-6">
                    <i class="fas fa-info-circle mr-2 text-xl"></i> Aucune discipline disponible
                </div>
            </div>
        </div>
    </MonLayout>
</template>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Inter', 'Arial', sans-serif;
}

/* Styles généraux */
.max-w-5xl {
    max-width: 100%;
}

/* Réactivité */
@media (max-width: 640px) {
    .p-4 {
        padding: 1rem;
    }

    .p-6 {
        padding: 1.5rem;
    }

    .text-3xl {
        font-size: 1.75rem;
    }

    .text-4xl {
        font-size: 2rem;
    }

    .text-5xl {
        font-size: 2.5rem;
    }

    .h-6 {
        height: 1rem;
    }

    .text-sm {
        font-size: 0.75rem;
    }

    .text-base {
        font-size: 0.875rem;
    }
}

@media (min-width: 640px) and (max-width: 768px) {
    .max-w-5xl {
        max-width: 100%;
    }

    .text-3xl {
        font-size: 2rem;
    }

    .text-4xl {
        font-size: 2.25rem;
    }

    .text-5xl {
        font-size: 2.75rem;
    }
}

/* Styles pour l'impression */
@media print {
    body {
        background: white;
        font-family: 'Inter', 'Arial', sans-serif;
        margin: 0;
        padding: 15mm;
        color: black;
    }

    .max-w-5xl {
        max-width: 100%;
        padding: 0;
    }

    header {
        margin-bottom: 15mm;
        padding-bottom: 5mm;
        border-bottom: 2px solid #333;
    }

    header h1 {
        font-size: 24pt;
        font-weight: bold;
        text-align: center;
        color: #1a73e8;
        margin-bottom: 5mm;
    }

    header div {
        font-size: 12pt;
        text-align: center;
    }

    .bg-teal-50 {
        background: #f0f0f0 !important;
        border: 1px solid #ccc;
        border-radius: 5mm;
        padding: 5mm;
        margin-bottom: 10mm;
    }

    h2 {
        font-size: 20pt;
        color: black;
        text-align: center;
    }

    .bg-gray-50 {
        display: none !important;
    }

    .bg-white {
        background: white !important;
        border: 1px solid #ccc;
        border-radius: 5mm;
        padding: 5mm;
        box-shadow: none !important;
    }

    h3 {
        font-size: 14pt;
        font-weight: bold;
        text-align: center;
        margin-bottom: 5mm;
        color: black;
    }

    .space-y-3>div {
        background: #f9f9f9 !important;
        border: 1px solid #ddd;
        border-radius: 3mm;
        padding: 3mm;
        font-size: 11pt;
        color: black;
    }

    i {
        display: none !important;
    }

    span {
        font-size: 10pt;
        padding: 2mm 4mm;
    }

    .text-teal-500,
    .text-teal-600,
    .text-green-500,
    .text-gray-500,
    .text-gray-900 {
        color: black !important;
    }
}
</style>