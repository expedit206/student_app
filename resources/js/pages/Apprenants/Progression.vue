<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';

const props = defineProps<{
    progressionData: {
        formation: { id: number; name: string } | null;
        progression: number;
        disciplines: Array<{ id: number; name: string; completed: boolean }>;
    };
}>();
</script>

<template>
    <MonLayout>
        <div class="p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex items-center justify-between animate-fade-in">
                <h1 class="text-4xl font-bold text-white flex items-center">
                    <i class="fas fa-chart-line mr-3 text-teal-400 text-3xl animate-pulse"></i>
                    Ma Progression
                    <span v-if="props.progressionData.formation" class="ml-2">- {{ props.progressionData.formation.name
                        }}</span>
                </h1>
                <div class="text-sm text-gray-400">Mis à jour le {{ new Date().toLocaleDateString() }}</div>
            </header>

            <!-- Progression globale -->
            <div
                class="bg-gradient-to-r from-teal-600 to-teal-500 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-teal-200">Progression Totale</p>
                        <h2 class="text-4xl font-extrabold text-white">{{ props.progressionData.progression }}%</h2>
                    </div>
                    <i class="fas fa-trophy text-5xl text-teal-300 opacity-75 animate-bounce"></i>
                </div>
                <div class="mt-4 bg-gray-700 rounded-full h-4 overflow-hidden">
                    <div class="bg-teal-400 h-full transition-all duration-500"
                        :style="{ width: `${props.progressionData.progression}%` }"></div>
                </div>
            </div>

            <!-- Liste des disciplines -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-list-check mr-2 text-teal-400"></i> Disciplines
                </h3>
                <div v-if="props.progressionData.disciplines.length" class="space-y-3">
                    <div v-for="discipline in props.progressionData.disciplines" :key="discipline.id"
                        class="p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all flex items-center justify-between">
                        <div class="flex items-center">
                            <i :class="discipline.completed ? 'fas fa-check-circle text-green-400' : 'fas fa-circle text-gray-400'"
                                class="mr-3"></i>
                            <p class="text-white">{{ discipline.name }}</p>
                        </div>
                        <span class="text-sm px-2 py-1 rounded-full"
                            :class="discipline.completed ? 'bg-green-500 text-white' : 'bg-gray-500 text-gray-200'">
                            {{ discipline.completed ? 'Terminé' : 'En cours' }}
                        </span>
                    </div>
                </div>
                <div v-else class="text-center text-gray-400 py-6">
                    <i class="fas fa-info-circle mr-2 text-xl"></i> Aucune discipline disponible
                </div>
            </div>
        </div>
    </MonLayout>
</template>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

.animate-fade-in {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.animate-pulse {
    animation: pulse 2s infinite;
}

.animate-bounce {
    animation: bounce 1s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.05);
    }

    100% {
        transform: scale(1);
    }
}

@keyframes bounce {

    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0);
    }

    40% {
        transform: translateY(-8px);
    }

    60% {
        transform: translateY(-4px);
    }
}
</style>