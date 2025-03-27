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

// Données du graphique
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

.bg-gradient-to-r.from-teal-600 {
    background: linear-gradient(to right, #0d9488, #14b8a6);
}

.text-teal-200 {
    color: #5eead4;
}

.text-teal-300 {
    color: #2dd4bf;
}

.bg-gradient-to-r.from-indigo-600 {
    background: linear-gradient(to right, #4f46e5, #6366f1);
}

.text-indigo-200 {
    color: #c7d2fe;
}

.text-indigo-300 {
    color: #a5b4fc;
}

.bg-gradient-to-r.from-purple-600 {
    background: linear-gradient(to right, #9333ea, #a855f7);
}

.text-purple-200 {
    color: #e9d5ff;
}

.text-purple-300 {
    color: #d8b4fe;
}

.bg-gray-800 {
    background-color: #ffffff;
}

.bg-gray-700 {
    background-color: #f9fafb;
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

html.dark .bg-gradient-to-r.from-teal-600 {
    background: linear-gradient(to right, #0f766e, #2dd4bf);
}

html.dark .text-teal-200 {
    color: #99f6e4;
}

html.dark .text-teal-300 {
    color: #5eead4;
}

html.dark .bg-gradient-to-r.from-indigo-600 {
    background: linear-gradient(to right, #4338ca, #818cf8);
}

html.dark .text-indigo-200 {
    color: #e0e7ff;
}

html.dark .text-indigo-300 {
    color: #c7d2fe;
}

html.dark .bg-gradient-to-r.from-purple-600 {
    background: linear-gradient(to right, #7e22ce, #c084fc);
}

html.dark .text-purple-200 {
    color: #f3e8ff;
}

html.dark .text-purple-300 {
    color: #e9d5ff;
}

html.dark .bg-gray-800 {
    background-color: #1f2937;
}

html.dark .bg-gray-700 {
    background-color: #374151;
}

html.dark .hover\:bg-gray-600:hover {
    background-color: #4b5563;
}

html.dark .text-gray-400 {
    color: #9ca3af;
}

/* Animations personnalisées */
.animate-fade-in {
    animation: fadeIn 0.5s ease;
}

.animate-spin-slow {
    animation: spinSlow 4s linear infinite;
}

.animate-pulse {
    animation: pulse 2s infinite;
}

.animate-bounce {
    animation: bounce 1s infinite;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
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

@keyframes bounce {

    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0);
    }

    40% {
        transform: translateY(-10px);
    }

    60% {
        transform: translateY(-5px);
    }
}

/* Réactivité */
@media (min-width: 640px) {
    .sm\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (min-width: 1024px) {
    .lg\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .lg\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>