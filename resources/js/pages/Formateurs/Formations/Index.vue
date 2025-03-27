<script setup lang="ts">
import { defineProps } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
import { Link } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';

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
        <div class="p-6 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen overflow-hidden">
            <!-- Header avec animation -->
            <h1 class="text-3xl font-extrabold mb-8 flex items-center animate-slide-in">
                <i class="fas fa-book-open mr-3 text-purple-600 dark:text-purple-400 text-4xl animate-pulse"></i>
                Mes Formations
            </h1>

            <!-- Grille de cartes -->
            <div class="grid grid-cols-1 sm:grid-cols-2  gap-6">
                <div v-for="(formation, index) in formations" :key="formation.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-6 transform transition-all duration-300 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)] animate-row-in"
                    :style="{ animationDelay: `${index * 0.1}s` }">
                    <!-- Titre -->
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-purple-600 dark:text-purple-400"></i>
                        {{ formation.titre }}
                    </h2>

                    <!-- Description -->
                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ formation.description }}</p>

               
                    <!-- Action -->
                    <TextLink :href="route('formateur.apprenants', { formation: formation.id })"
                        class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200 group">
                        <i class="fas fa-eye mr-2 group-hover:animate-bounce"></i>
                        Voir Apprenants
                    </TextLink>
                </div>

                <!-- Message si aucune formation -->
                <div v-if="formations.length === 0"
                    class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-6 text-center text-gray-500 dark:text-gray-400 animate-pulse">
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

/* Style pour ombre dynamique */
.shadow-2xl:hover {
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
}

/* Limiter la hauteur du texte de la description */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>