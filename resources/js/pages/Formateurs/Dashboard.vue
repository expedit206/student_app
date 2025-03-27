<script setup lang="ts">
import { ref } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

// Enregistrer les composants Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps<{
    dashboardData: {
        totalFormations: number;
        totalApprenants: number;
        recentActivities: Array<{ id: number; type: string; description: string; date: string }>;
        formations: Array<{ id: number; name: string; apprenants: number; moyenne: number }>;
    };
}>();

// Données du graphique
const chartData = ref({
    labels: props.dashboardData.formations.map(f => f.name),
    datasets: [
        {
            label: 'Moyenne des notes (%)',
            data: props.dashboardData.formations.map(f => f.moyenne),
            backgroundColor: props.dashboardData.formations.map(f =>
                f.moyenne >= 80 ? '#34D399' : f.moyenne >= 60 ? '#FBBF24' : '#F87171'
            ),
            borderColor: '#ffffff',
            borderWidth: 1,
        },
    ],
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: true, position: 'top', labels: { color: '#ffffff' } },
        tooltip: { backgroundColor: '#1F2937', titleColor: '#ffffff', bodyColor: '#ffffff' },
    },
    scales: {
        y: {
            beginAtZero: true,
            max: 100,
            title: { display: true, text: 'Pourcentage (%)', color: '#ffffff' },
            ticks: { color: '#ffffff' },
            grid: { color: '#374151' },
        },
        x: {
            title: { display: true, text: 'Formations', color: '#ffffff' },
            ticks: { color: '#ffffff' },
            grid: { display: false },
        },
    },
};
</script>

<template>
    <FormateurLayout>
        <div
            class="p-6 bg-gradient-to-br from-gray-100 dark:from-gray-900 to-gray-200 dark:to-gray-800 text-gray-900 dark:text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex items-center justify-between animate-fade-in">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i
                        class="fas fa-tachometer-alt mr-3 text-indigo-600 dark:text-indigo-400 text-3xl animate-pulse"></i>
                    Tableau de Bord
                </h1>
                <div class="text-sm text-gray-600 dark:text-gray-400">Mis à jour le {{ new Date().toLocaleDateString()
                    }}</div>
            </header>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-indigo-500 dark:from-indigo-700 dark:to-indigo-600 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-200 dark:text-indigo-300">Formations Gérées</p>
                            <h2 class="text-4xl font-extrabold text-white"> {{ props.dashboardData.totalFormations }}
                            </h2>
                        </div>
                        <i
                            class="fas fa-book text-5xl text-indigo-300 dark:text-indigo-200 opacity-75 animate-spin-slow"></i>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-teal-600 to-teal-500 dark:from-teal-700 dark:to-teal-600 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-teal-200 dark:text-teal-300">Apprenants Inscrits</p>
                            <h2 class="text-4xl font-extrabold text-white">{{ props.dashboardData.totalApprenants }}
                            </h2>
                        </div>
                        <i class="fas fa-users text-5xl text-teal-300 dark:text-teal-200 opacity-75 animate-bounce"></i>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-purple-600 to-purple-500 dark:from-purple-700 dark:to-purple-600 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-200 dark:text-purple-300">Notes Récentes</p>
                            <h2 class="text-4xl font-extrabold text-white">{{
                                props.dashboardData.recentActivities.length }}</h2>
                        </div>
                        <i
                            class="fas fa-star text-5xl text-purple-300 dark:text-purple-200 opacity-75 animate-pulse"></i>
                    </div>
                </div>
            </div>

            <!-- Graphique pleine largeur -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-indigo-600 dark:text-indigo-400"></i> Performance des
                    Formations
                </h3>
                <div class="h-80">
                    <Bar :data="chartData" :options="chartOptions" />
                </div>
            </div>

            <!-- Sections principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Activités Récentes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 overflow-y-auto h-[calc(100vh-500px)]">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-indigo-600 dark:text-indigo-400"></i> Activités Récentes
                    </h3>
                    <div v-if="props.dashboardData.recentActivities.length" class="space-y-3">
                        <div v-for="activity in props.dashboardData.recentActivities" :key="activity.id"
                            class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all flex items-center justify-between">
                            <div>
                                <p class="text-sm text-indigo-600 dark:text-indigo-300 font-medium">{{ activity.type }}
                                </p>
                                <p class="text-gray-900 dark:text-white">{{ activity.description }}</p>
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ activity.date
                                }}</span>
                        </div>
                    </div>
                    <div v-else class="text-center text-gray-500 dark:text-gray-400 py-6">
                        <i class="fas fa-info-circle mr-2 text-xl"></i> Aucune activité récente
                    </div>
                </div>

                <!-- Mes Formations -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 overflow-y-auto h-[calc(100vh-500px)]">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-book-open mr-2 text-indigo-600 dark:text-indigo-400"></i> Mes Formations
                    </h3>
                    <ul class="space-y-3">
                        <li v-for="formation in props.dashboardData.formations" :key="formation.id"
                            class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all flex items-center justify-between">
                            <div>
                                <p class="text-sm text-indigo-600 dark:text-indigo-300 font-medium">{{ formation.name }}
                                </p>
                                <p class="text-gray-900 dark:text-white">Apprenants: {{ formation.apprenants }}</p>
                            </div>
                            <span class="text-sm text-gray-900 dark:text-white">Moyenne: {{ formation.moyenne }}%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </FormateurLayout>
</template>

<style scoped>
/* Ajoutez des styles personnalisés ici */
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
</style>