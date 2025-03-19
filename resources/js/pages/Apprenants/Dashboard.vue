<script setup lang="ts">
import { ref, onMounted } from 'vue';
import MonLayout from '@/components/MonLayout.vue';
import { router } from 'ziggy-js';
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

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps<{
    dashboardData: {
        totalFormations: number;
        totalNotes: number;
        moyenneGenerale: number;
        recentActivities: Array<{ id: number; type: string; description: string; date: string }>;
        formations: Array<{ id: number; name: string; formateur: string; moyenne: number; notes_count: number }>;
    };
}>();

console.log(props.dashboardData.formations);

// Débogage frontend
onMounted(() => {
    console.log('Props reçus:', props.dashboardData);
    console.log('Formations:', props.dashboardData.formations);
});
// Données du graphique (identique à la logique du formateur)
const chartData = ref({
    labels: props.dashboardData.formations.map(f => f.name),
    datasets: [
        {
            label: 'Moyenne (%)',
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
            title: { display: true, text: 'Formation', color: '#ffffff' },
            ticks: { color: '#ffffff' },
            grid: { display: false },
        },
    },
};
</script>

<template>
    <MonLayout>
        <div class="p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex items-center justify-between animate-fade-in">
                <h1 class="text-4xl font-bold text-white flex items-center">
                    <i class="fas fa-tachometer-alt mr-3 text-teal-400 text-3xl animate-pulse"></i>
                    Mon Tableau de Bord
                </h1>
                <div class="text-sm text-gray-400">Mis à jour le {{ new Date().toLocaleDateString() }}</div>
            </header>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div
                    class="bg-gradient-to-r from-teal-600 to-teal-500 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-teal-200">Formation Suivie</p>
                            <h2 class="text-4xl font-extrabold text-white">{{ props.dashboardData.totalFormations }}
                            </h2>
                        </div>
                        <i class="fas fa-book text-5xl text-teal-300 opacity-75 animate-spin-slow"></i>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-200">Notes Reçues</p>
                            <h2 class="text-4xl font-extrabold text-white">{{ props.dashboardData.totalNotes }}</h2>
                        </div>
                        <i class="fas fa-star text-5xl text-indigo-300 opacity-75 animate-pulse"></i>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-purple-600 to-purple-500 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-200">Moyenne Générale</p>
                            <h2 class="text-4xl font-extrabold text-white">{{ props.dashboardData.moyenneGenerale }}%
                            </h2>
                        </div>
                        <i class="fas fa-chart-line text-5xl text-purple-300 opacity-75 animate-bounce"></i>
                    </div>
                </div>
            </div>

            <!-- Graphique pleine largeur -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-teal-400"></i> Ma Performance
                </h3>
                <div class="h-80">
                    <Bar :data="chartData" :options="chartOptions" />
                </div>
            </div>

            <!-- Sections principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Activités Récentes -->
                <div class="bg-gray-800 rounded-xl shadow-lg p-6 overflow-y-auto h-[calc(100vh-500px)]">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-teal-400"></i> Activités Récentes
                    </h3>
                    <div v-if="props.dashboardData.recentActivities.length" class="space-y-3">
                        <div v-for="activity in props.dashboardData.recentActivities" :key="activity.id"
                            class="p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all flex items-center justify-between">
                            <div>
                                <p class="text-sm text-teal-300 font-medium">{{ activity.type }}</p>
                                <p class="text-white">{{ activity.description }}</p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ activity.date }}</span>
                        </div>
                    </div>
                    <div v-else class="text-center text-gray-400 py-6">
                        <i class="fas fa-info-circle mr-2 text-xl"></i> Aucune activité récente
                    </div>
                </div>

                <!-- Ma Formation -->
                <div class="bg-gray-800 rounded-xl shadow-lg p-6 overflow-y-auto h-[calc(100vh-500px)]">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-book-open mr-2 text-teal-400"></i> Ma Formation
                    </h3>
                    <ul class="space-y-3">
                        <li v-if="props.dashboardData.formations && props.dashboardData.formations.length > 0"
                            class="p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-all flex items-center justify-between cursor-pointer"
                            @click="$router.push({ name: 'apprenant.notes', params: { formation: props.dashboardData.formations[0].id } })">
                            <div>
                                <p class="text-sm text-teal-300 font-medium">
                                    {{ props.dashboardData.formations[0].name || 'Nom non disponible' }}
                                </p>
                                <p class="text-white">Formateur: {{ props.dashboardData.formations[0].formateur || 'Non assigné' }}</p>
                            </div>
                            <span class="text-sm text-white">Moyenne: {{ props.dashboardData.formations[0].moyenne || 0
                                }}%</span>
                        </li>
                        <li v-else class="text-center text-gray-400 py-6">
                            <i class="fas fa-info-circle mr-2 text-xl"></i> Aucune formation assignée
                        </li>
                    </ul>
                </div>
                
            </div>
        </div>
    </MonLayout>
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