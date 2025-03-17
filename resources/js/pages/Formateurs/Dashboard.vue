<template>
    <FormateurLayout>
        <div class="overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-gray-800 rounded-xl shadow-md p-6 transition-transform transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Formations Gérées</p>
                            <h2 class="text-3xl font-bold text-white">{{ dashboardData.totalFormations }}</h2>
                        </div>
                        <i class="fas fa-book text-3xl text-blue-400"></i>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-xl shadow-md p-6 transition-transform transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Apprenants Inscrits</p>
                            <h2 class="text-3xl font-bold text-white">{{ dashboardData.totalApprenants }}</h2>
                        </div>
                        <i class="fas fa-users text-3xl text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[calc(100%-12rem)]">
                <div
                    class="bg-gray-800 rounded-xl shadow-md p-6 overflow-y-auto transition-transform transform hover:scale-105">
                    <h3 class="text-xl font-semibold text-white mb-4">
                        <i class="fas fa-clock mr-2 text-blue-400"></i>Activités Récentes
                    </h3>
                    <div class="space-y-4">
                        <div v-for="activity in dashboardData.recentActivities" :key="activity.id"
                            class="p-3 bg-gray-700 rounded-lg transition-transform transform hover:scale-105">
                            <p class="text-sm text-gray-300">{{ activity.type }}</p>
                            <p class="text-white">{{ activity.description }}</p>
                            <span class="text-xs text-gray-400">{{ activity.date }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gray-800 rounded-xl shadow-md p-6 overflow-y-auto transition-transform transform hover:scale-105">
                    <a :href="route('formateur.formations')" class="text-xl font-semibold text-white mb-4">
                        <i class="fas fa-graduation-cap mr-2 text-blue-400"></i>Mes Formations
                    </a>
                    <div class="space-y-4">
                        <div v-for="formation in dashboardData.formations" :key="formation.name"
                            class="p-3 bg-gray-700 rounded-lg transition-transform transform hover:scale-105">
                            <h4 class="font-medium text-white">{{ formation.name }}</h4>
                            <p class="text-sm text-gray-300">
                                Apprenants: {{ formation.apprenants }} | Moyenne: {{ formation.moyenne }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FormateurLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
const dashboardData = ref({
    totalFormations: 3, // Formations gérées par le formateur
    totalApprenants: 50, // Apprenants inscrits à ses formations
    recentActivities: [
        { id: 1, type: 'Note ajoutée', description: 'Jean Dupont - Informatique: 85%', date: '2025-03-13 09:15' },
        { id: 2, type: 'Évaluation complétée', description: 'Marie Curie - Infographie', date: '2025-03-12 14:30' },
    ],
    formations: [
        { name: 'Informatique', apprenants: 20, moyenne: 82 },
        { name: 'Infographie', apprenants: 15, moyenne: 89 },
        { name: 'Web Dev', apprenants: 15, moyenne: 79 },
    ]
});
</script>

<style>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Arial', sans-serif;
}
</style>