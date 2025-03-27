<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';
import { defineProps, computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    notesData: {
        formation: { id: number; name: string } | null;
        notes: Array<{ id: number; discipline: string; note: number; max_note: number; date: string; commentaire: string }>;
        totalNotes: number;
        moyenne: number;
    };
}>();

// Recherche/Filtre
const search = ref('');
const filteredNotes = computed(() => {
    if (!search.value) return props.notesData.notes;
    const term = search.value.toLowerCase();
    return props.notesData.notes.filter(note =>
        note.discipline.toLowerCase().includes(term) ||
        note.commentaire.toLowerCase().includes(term)
    );
});

// Formatage de la date
const formatDate = (date: string) => new Date(date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });

// Débogage (optionnel, peut être supprimé en production)
import { onMounted } from 'vue';
onMounted(() => {
    console.log('Notes Data:', props.notesData);
});
</script>

<template>

    <Head :title="`Mes Notes${props.notesData.formation ? ` - ${props.notesData.formation.name}` : ''}`" />
    <MonLayout>
        <div class="p-4 sm:p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex flex-col sm:flex-row items-center justify-between animate-slide-in">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white flex items-center">
                    <i class="fas fa-star mr-3 text-teal-400 animate-pulse"></i>
                    Mes Notes {{ props.notesData.formation ? `- ${props.notesData.formation.name}` : '' }}
                </h1>
                <div class="text-sm text-gray-400 mt-2 sm:mt-0">{{ formatDate(new Date().toISOString()) }}</div>
            </header>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 animate-fade-in">
                <div
                    class="bg-gradient-to-r from-indigo-700 to-indigo-600 rounded-xl p-5 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-200">Total des Notes</p>
                            <h2 class="text-3xl font-extrabold text-white">{{ props.notesData.totalNotes }}</h2>
                        </div>
                        <i class="fas fa-star text-4xl text-indigo-300 opacity-80"></i>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-purple-700 to-purple-600 rounded-xl p-5 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-200">Moyenne Générale</p>
                            <h2 class="text-3xl font-extrabold text-white">{{ props.notesData.moyenne.toFixed(1) }}%
                            </h2>
                        </div>
                        <i class="fas fa-chart-line text-4xl text-purple-300 opacity-80"></i>
                    </div>
                </div>
            </div>

            <!-- Recherche -->
            <div class="mb-6 animate-slide-in">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input v-model="search" placeholder="Rechercher par discipline ou commentaire..."
                        class="w-full pl-10 pr-4 py-3 bg-gray-700 text-gray-200 rounded-lg border border-gray-600 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 shadow-md" />
                </div>
            </div>

            <!-- Liste des notes -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-list mr-2 text-teal-400"></i> Détail des Notes
                </h3>
                <div v-if="filteredNotes.length" class="space-y-3">
                    <div v-for="note in filteredNotes" :key="note.id"
                        class="p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all flex items-center justify-between">
                        <div>
                            <p class="text-sm text-teal-300 font-medium">{{ note.discipline }}</p>
                            <p class="text-white">{{ note.note }} / {{ note.max_note }}</p>
                            <p class="text-xs text-gray-400">{{ note.commentaire }}</p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ formatDate(note.date) }}</span>
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

/* Styles par défaut (mode clair) */
.bg-gradient-to-br.from-gray-900 {
    background: linear-gradient(to bottom right, #f3f4f6, #e5e7eb);
}

.text-gray-100 {
    color: #1f2937;
}

.text-white {
    color: #111827;
}

.text-teal-400 {
    color: #14b8a6;
}

.bg-gradient-to-r.from-indigo-700 {
    background: linear-gradient(to right, #4338ca, #4f46e5);
}

.text-indigo-200 {
    color: #c7d2fe;
}

.text-indigo-300 {
    color: #a5b4fc;
}

.bg-gradient-to-r.from-purple-700 {
    background: linear-gradient(to right, #7e22ce, #9333ea);
}

.text-purple-200 {
    color: #e9d5ff;
}

.text-purple-300 {
    color: #d8b4fe;
}

.bg-gray-700 {
    background-color: #f9fafb;
}

.text-gray-200 {
    color: #4b5563;
}

.border-gray-600 {
    border-color: #d1d5db;
}

.focus\:ring-teal-500:focus {
    --tw-ring-color: #14b8a6;
}

.bg-gray-800 {
    background-color: #ffffff;
}

.hover\:bg-gray-600:hover {
    background-color: #e5e7eb;
}

.text-gray-400 {
    color: #6b7280;
}

/* Mode sombre */
html.dark .bg-gradient-to-br.from-gray-900 {
    background: linear-gradient(to bottom right, #111827, #1f2937);
}

html.dark .text-gray-100 {
    color: #f3f4f6;
}

html.dark .text-white {
    color: #ffffff;
}

html.dark .text-teal-400 {
    color: #2dd4bf;
}

html.dark .bg-gradient-to-r.from-indigo-700 {
    background: linear-gradient(to right, #3730a3, #4f46e5);
}

html.dark .text-indigo-200 {
    color: #e0e7ff;
}

html.dark .text-indigo-300 {
    color: #c7d2fe;
}

html.dark .bg-gradient-to-r.from-purple-700 {
    background: linear-gradient(to right, #6b21a8, #9333ea);
}

html.dark .text-purple-200 {
    color: #f3e8ff;
}

html.dark .text-purple-300 {
    color: #e9d5ff;
}

html.dark .bg-gray-700 {
    background-color: #374151;
}

html.dark .text-gray-200 {
    color: #9ca3af;
}

html.dark .border-gray-600 {
    border-color: #4b5563;
}

html.dark .focus\:ring-teal-500:focus {
    --tw-ring-color: #2dd4bf;
}

html.dark .bg-gray-800 {
    background-color: #1f2937;
}

html.dark .hover\:bg-gray-600:hover {
    background-color: #4b5563;
}

html.dark .text-gray-400 {
    color: #9ca3af;
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes rowIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.5;
    }

    100% {
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slideIn 0.5s ease-out forwards;
}

.animate-fade-in {
    animation: fadeIn 0.7s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

.animate-pulse {
    animation: pulse 2s infinite;
}

/* Styles généraux */
.min-h-screen {
    min-height: 100vh;
}

/* Scrollbar stylisée */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #e5e7eb;
    /* Mode clair */
    border-radius: 4px;
}

html.dark ::-webkit-scrollbar-track {
    background: #2d3748;
    /* Mode sombre */
}

::-webkit-scrollbar-thumb {
    background: #4f46e5;
    /* Mode clair */
    border-radius: 4px;
}

html.dark ::-webkit-scrollbar-thumb {
    background: #818cf8;
    /* Mode sombre */
}

::-webkit-scrollbar-thumb:hover {
    background: #6366f1;
    /* Mode clair */
}

html.dark ::-webkit-scrollbar-thumb:hover {
    background: #a5b4fc;
    /* Mode sombre */
}

/* Réactivité */
@media (max-width: 640px) {
    .p-6 {
        padding: 1rem;
    }

    h1 {
        font-size: 1.75rem;
    }

    h2 {
        font-size: 1.5rem;
    }

    .text-4xl {
        font-size: 2rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .text-xs {
        font-size: 0.75rem;
    }
}
</style>