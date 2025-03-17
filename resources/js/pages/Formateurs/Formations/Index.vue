<script setup lang="ts">
import { defineProps } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    formations: Array<{
        id: number;
        titre: string;
        description: string;
        nbh_hebdomadaire: number;
        nbh_total: number;
    }>;
}>();
</script>

<template>
    <FormateurLayout>
        <div class="p-6 bg-gray-900 text-gray-100 min-h-screen overflow-hidden">
            <!-- Header avec animation -->
            <h1 class="text-3xl font-extrabold mb-8 flex items-center animate-slide-in">
                <i class="fas fa-book-open mr-3 text-purple-400 text-4xl animate-pulse"></i>
                Mes Formations
            </h1>

            <!-- Tableau stylisé -->
            <div
                class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-700 to-gray-800 text-gray-200">
                            <th class="py-4 px-6 text-left font-semibold">
                                <i class="fas fa-graduation-cap mr-2 text-purple-400"></i>Titre
                            </th>
                            <th class="py-4 px-6 text-left font-semibold">
                                <i class="fas fa-info-circle mr-2 text-blue-400"></i>Description
                            </th>
                            <th class="py-4 px-6 text-left font-semibold">
                                <i class="fas fa-clock mr-2 text-green-400"></i>Heures Hebdo
                            </th>
                            <th class="py-4 px-6 text-left font-semibold">
                                <i class="fas fa-hourglass-end mr-2 text-yellow-400"></i>Heures Totales
                            </th>
                            <th class="py-4 px-6 text-left font-semibold">
                                <i class="fas fa-cog mr-2 text-red-400"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(formation, index) in formations" :key="formation.id"
                            class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300 animate-row-in"
                            :style="{ animationDelay: `${index * 0.1}s` }">
                            <td class="py-4 px-6 font-medium text-gray-100">{{ formation.titre }}</td>
                            <td class="py-4 px-6 text-gray-300 max-w-xs truncate">{{ formation.description }}</td>
                            <td class="py-4 px-6 text-gray-100">
                                <span class="bg-green-900 text-green-300 px-2 py-1 rounded-full text-sm">{{
                                    formation.nbh_hebdomadaire }}</span>
                            </td>
                            <td class="py-4 px-6 text-gray-100">
                                <span class="bg-yellow-900 text-yellow-300 px-2 py-1 rounded-full text-sm">{{
                                    formation.nbh_total }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <Link
                                    class="flex items-center text-blue-400 hover:text-blue-300 transition-colors duration-200 group">
                                <i class="fas fa-eye mr-2 group-hover:animate-bounce"></i>
                                Voir Apprenants
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="formations.length === 0" class="p-6 text-center text-gray-400 animate-pulse">
                    <i class="fas fa-exclamation-triangle mr-2 text-xl"></i>
                    Aucune formation disponible
                </div>
            </div>
        </div>
    </FormateurLayout>
</template>

<style scoped>
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

.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

/* Style supplémentaire pour ombre dynamique */
.shadow-2xl:hover {
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
}
</style>