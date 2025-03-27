<template>
    <FormateurLayout>
        <div class="p-4 sm:p-6 bg-gray-900 text-gray-100 min-h-screen overflow-hidden">
            <!-- Header -->
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-8 flex items-center animate-slide-in">
                <i :class="['fas fa-book mr-3 text-3xl sm:text-4xl', iconColors.base, 'animate-spin-slow']"></i>
                Liste de mes Disciplines
            </h1>

            <!-- Sélection de Formation -->
            <div class="mb-4 animate-fade-up">
                <div class="relative">
                    <i
                        :class="['fas fa-graduation-cap absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base, 'animate-pulse']"></i>
                    <select v-model="selectedFormation" @change="resetSearch"
                        class="w-full pl-12 pr-10 py-2 sm:py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-700 text-sm sm:text-base">
                        <option value="">Toutes mes formations</option>
                        <option v-for="formation in formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                    <i
                        :class="['fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2', iconColors.base, 'animate-bounce pointer-events-none']"></i>
                </div>
            </div>

            <!-- Statistiques Compactes -->
            <div class="grid grid-cols-1 gap-4 mb-8">
                <div
                    class="bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                    <div class="flex items-center">
                        <i :class="['fas fa-book-open text-2xl', iconColors.base, 'animate-pulse']"></i>
                        <div class="ml-3">
                            <p class="text-xs text-gray-400">Disciplines</p>
                            <h2 class="text-lg font-bold text-white animate-number">{{ totalDisciplines }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="relative mb-8 animate-fade-up">
                <i
                    :class="['fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base, 'animate-pulse']"></i>
                <input v-model="searchQuery" type="text" placeholder="Rechercher..."
                    class="w-full pl-12 pr-4 py-2 sm:py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-700 text-sm sm:text-base" />
            </div>

            <!-- Desktop : Table -->
            <div
                class="hidden sm:block bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)] animate-fade-in">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-700 to-gray-800 text-gray-200 animate-slide-in">
                            <th class="py-4 px-6 text-left font-semibold">
                                <i :class="['fas fa-book mr-2', iconColors.base, 'animate-pulse']"></i>Nom de Discipline
                            </th>
                            <th class="py-4 px-6 text-left font-semibold">
                                <i
                                    :class="['fas fa-info-circle mr-2', iconColors.base, 'animate-pulse']"></i>Description
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(discipline, index) in filteredDisciplines" :key="discipline.id"
                            class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300 animate-row-in"
                            :style="{ animationDelay: `${0.7 + index * 0.1}s` }">
                            <td class="py-4 px-6 text-gray-100 hover:text-blue-300 transition-colors duration-200">{{
                                discipline.nom }}</td>
                            <td class="py-4 px-6 text-gray-300 hover:text-blue-300 transition-colors duration-200">
                                {{ discipline.description || 'Aucune description disponible' }}
                            </td>
                        </tr>
                        <tr v-if="filteredDisciplines.length === 0">
                            <td colspan="2" class="p-6 text-center text-gray-400 animate-pulse">
                                <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i>Aucune discipline
                                trouvée
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile : Cartes -->
            <div class="sm:hidden grid grid-cols-1 gap-4 bg-gray-800 rounded-xl shadow-2xl p-4 animate-fade-in">
                <div v-for="(discipline, index) in filteredDisciplines" :key="discipline.id"
                    class="dark:bg-gray-700 rounded-lg shadow-md border border-gray-600 p-4 hover:shadow-xl transition-all duration-300 animate-card-in"
                    :style="{ animationDelay: `${0.7 + index * 0.1}s` }">
                    <div class="flex flex-col space-y-2">
                        <h2 class="text-lg font-semibold text-gray-100 truncate" :title="discipline.nom">
                            <i :class="['fas fa-book mr-2', iconColors.base]"></i>{{ discipline.nom }}
                        </h2>
                        <p class="text-sm text-gray-300 line-clamp-2"
                            :title="discipline.description || 'Aucune description disponible'">
                            <i :class="['fas fa-info-circle mr-2', iconColors.base]"></i>
                            {{ discipline.description || 'Aucune description disponible' }}
                        </p>
                    </div>
                </div>
                <div v-if="filteredDisciplines.length === 0"
                    class="col-span-full p-4 text-center text-gray-400 animate-pulse bg-gray-700 rounded-lg shadow-md border border-gray-600">
                    <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i>Aucune discipline trouvée
                </div>
            </div>
        </div>
    </FormateurLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';

const props = defineProps<{
    disciplines: Array<{
        id: number;
        nom: string;
        description: string | null;
        formation_ids: number[];
    }>;
    formations: Array<{
        id: number;
        titre: string;
    }>;
}>();

const searchQuery = ref('');
const selectedFormation = ref('');

const filteredDisciplines = computed(() => {
    const searchTerm = searchQuery.value.toLowerCase();
    return props.disciplines.filter((discipline) => {
        const matchesFormation = selectedFormation.value
            ? discipline.formation_ids.includes(parseInt(selectedFormation.value))
            : true;
        const matchesSearch = discipline.nom.toLowerCase().includes(searchTerm);
        return matchesFormation && matchesSearch;
    });
});

const totalDisciplines = computed(() => filteredDisciplines.value.length);

const resetSearch = () => {
    searchQuery.value = '';
};

const iconColors = {
    base: 'text-blue-600',
    hover: 'text-blue-700',
};
</script>

<style scoped>
/* Styles par défaut (mode clair) */
.bg-gray-900 {
    background-color: #f3f4f6;
}

.text-gray-100 {
    color: #1f2937;
}

.bg-gray-800 {
    background-color: #ffffff;
}

.border-gray-700 {
    border-color: #d1d5db;
}

.text-blue-600 {
    color: #2563eb;
}

.hover\:text-blue-300:hover {
    color: #1d4ed8;
}

.text-white {
    color: #111827;
}

.text-gray-400 {
    color: #6b7280;
}

.bg-gradient-to-r.from-gray-700 {
    background: linear-gradient(to right, #e5e7eb, #d1d5db);
}

.text-gray-200 {
    color: #4b5563;
}

.border-gray-600 {
    border-color: #e5e7eb;
}

.text-gray-300 {
    color: #4b5563;
}

.focus\:ring-blue-500:focus {
    --tw-ring-color: #3b82f6;
}

.hover\:bg-gray-700:hover {
    background-color: #f9fafb;
}

/* Mode sombre */
html.dark .bg-gray-900 {
    background-color: #111827;
}

html.dark .text-gray-100 {
    color: #f3f4f6;
}

html.dark .bg-gray-800 {
    background-color: #1f2937;
}

html.dark .border-gray-700 {
    border-color: #4b5563;
}

html.dark .text-blue-600 {
    color: #60a5fa;
}

html.dark .hover\:text-blue-300:hover {
    color: #93c5fd;
}

html.dark .text-white {
    color: #ffffff;
}

html.dark .text-gray-400 {
    color: #9ca3af;
}

html.dark .bg-gradient-to-r.from-gray-700 {
    background: linear-gradient(to right, #374151, #1f2937);
}

html.dark .text-gray-200 {
    color: #d1d5db;
}

html.dark .border-gray-600 {
    border-color: #374151;
}

html.dark .text-gray-300 {
    color: #9ca3af;
}

html.dark .focus\:ring-blue-500:focus {
    --tw-ring-color: #60a5fa;
}

html.dark .hover\:bg-gray-700:hover {
    background-color: #374151;
}

/* Animations personnalisées */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes rowIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes cardIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }

    50% {
        opacity: 1;
        transform: scale(1.05);
    }

    70% {
        transform: scale(0.9);
    }

    100% {
        transform: scale(1);
    }
}

@keyframes spinSlow {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

@keyframes number {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

.animate-fade-up {
    animation: fadeUp 0.6s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

.animate-card-in {
    animation: cardIn 0.5s ease-out forwards;
}

.animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
}

.animate-spin-slow {
    animation: spinSlow 4s linear infinite;
}

.animate-number {
    animation: number 0.5s ease-out;
}

/* Styles optimisés */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Réactivité et attractivité */
@media (max-width: 640px) {
    .p-6 {
        padding: 1rem;
    }

    .text-3xl {
        font-size: 1.5rem;
    }

    .text-lg {
        font-size: 1.125rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .py-4 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .gap-4 {
        gap: 1rem;
    }

    .shadow-md {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
}
</style>