<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';

const props = defineProps<{
    notesData: {
        formation: { id: number; name: string } | null;
        notes: Array<{ id: number; discipline: string; note: number; max_note: number; date: string; commentaire: string }>;
        totalNotes: number;
        moyenne: number;
    };
}>();

// Débogage pour vérifier les données reçues
import { onMounted } from 'vue';
onMounted(() => {
    console.log('Notes Data:', props.notesData);
});
</script>

<template>
    <MonLayout>
        <div class="p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex items-center justify-between animate-fade-in">
                <h1 class="text-4xl font-bold text-white flex items-center">
                    <i class="fas fa-star mr-3 text-teal-400 text-3xl animate-pulse"></i>
                    Mes Notes {{ props.notesData.formation ? `- ${props.notesData.formation.name}` : '' }}
                </h1>
                <div class="text-sm text-gray-400">Mis à jour le {{ new Date().toLocaleDateString() }}</div>
            </header>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-200">Total des Notes</p>
                            <h2 class="text-4xl font-extrabold text-white">{{ props.notesData.totalNotes }}</h2>
                        </div>
                        <i class="fas fa-star text-5xl text-indigo-300 opacity-75 animate-pulse"></i>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-purple-600 to-purple-500 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-200">Moyenne Générale</p>
                            <h2 class="text-4xl font-extrabold text-white">{{ props.notesData.moyenne }}%</h2>
                        </div>
                        <i class="fas fa-chart-line text-5xl text-purple-300 opacity-75 animate-bounce"></i>
                    </div>
                </div>
            </div>

            <!-- Liste des notes -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-list mr-2 text-teal-400"></i> Détail des Notes
                </h3>
                <div v-if="props.notesData.notes.length" class="space-y-3">
                    <div v-for="note in props.notesData.notes" :key="note.id"
                        class="p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all flex items-center justify-between">
                        <div>
                            <p class="text-sm text-teal-300 font-medium">{{ note.discipline }}</p>
                            <p class="text-white">{{ note.note }} / {{ note.max_note }}</p>
                            <p class="text-xs text-gray-400">{{ note.commentaire }}</p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ note.date }}</span>
                    </div>
                </div>
                <div v-else class="text-center text-gray-400 py-6">
                    <i class="fas fa-info-circle mr-2 text-xl"></i> Aucune note disponible
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