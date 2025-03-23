<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale);

const props = defineProps<{
    isAuthenticated: boolean;
    evolution?: {
        labels: string[];
        data: number[];
    };
}>();

// Valeur par défaut pour evolution
const evolutionData = props.evolution || {
    labels: ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4'],
    data: [5, 10, 15, 20],
};

const chartData = {
    labels: evolutionData.labels,
    datasets: [{
        label: 'Progression',
        data: evolutionData.data,
        borderColor: '#4f46e5',
        backgroundColor: 'rgba(79, 70, 229, 0.2)',
        fill: true,
        tension: 0.4,
    }],
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: { beginAtZero: true, title: { display: true, text: 'Heures/Niveau' } },
        x: { title: { display: true, text: 'Temps' } },
    },
};
</script>

<template>

    <Head title="Bienvenue - Centre de Formation Professionnel La Canadienne" />
    <div class="min-h-screen bg-gray-900 text-white flex flex-col overflow-hidden">
        <!-- En-tête -->
        <header class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-800 shadow-lg sticky top-0 z-10">
            <div class="max-w-7xl mx-auto flex justify-between items-center animate-slide-in">
                <div class="flex items-center">
                    <img src="/img/logo.png" alt="Logo CFP La Canadienne"
                        class="h-16 w-16 mr-3 p-1 bg-white rounded-full shadow-md animate-spin-slow" />
                    <h1 class="text-2xl sm:text-3xl font-bold">
                        Centre de Formation Professionnel La Canadienne
                    </h1>
                </div>
                <nav class="flex gap-4">
                    <Link v-if="isAuthenticated" :href="route('dashboard')"
                        class="bg-indigo-500 px-4 py-2 rounded-lg hover:bg-indigo-600 transition-all duration-300 flex items-center animate-pulse">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>Tableau de bord
                    </Link>
                    <Link v-else :href="route('login')"
                        class="bg-indigo-500 px-4 py-2 rounded-lg hover:bg-indigo-600 transition-all duration-500 flex items-center animate-pulse">
                    <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </Link>
                </nav>
            </div>
        </header>

        <!-- Section héroïque -->
        <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center relative">
            <div class="max-w-5xl mx-auto text-center animate-fade-in-up">
                <h2 class="text-4xl sm:text-5xl font-extrabold mb-6 leading-tight">
                    <span class="block">Formez-vous</span>
                    <span class="block text-indigo-400">pour réussir</span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-300 mb-8 animate-fade-in" style="animation-delay: 0.3s;">
                    Le Centre de Formation Professionnel La Canadienne vous accompagne vers l’excellence avec des
                    formations adaptées à vos ambitions.
                </p>
                <div class="flex justify-center gap-6 animate-fade-in" style="animation-delay: 0.6s;">
                    <Link v-if="!isAuthenticated" :href="route('login')"
                        class="bg-indigo-600 text-white px-8 py-4 rounded-lg hover:bg-indigo-700 transition-all duration-500 flex items-center text-lg shadow-lg hover:shadow-xl">
                    <i class="fas fa-briefcase mr-3 animate-spin-slow"></i>Boostez votre avenir
                    </Link>
                    <Link v-if="isAuthenticated" :href="route('dashboard')"
                        class="bg-green-600 text-white px-8 py-4 rounded-lg hover:bg-green-700 transition-all duration-500 flex items-center text-lg shadow-lg hover:shadow-xl">
                    <i class="fas fa-tachometer-alt mr-3 animate-spin-slow"></i>Mon espace pro
                    </Link>
                </div>
            </div>
            <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
                <i class="fas fa-tools text-indigo-400 text-6xl absolute top-10 left-10 animate-float"></i>
                <i class="fas fa-laptop-code text-yellow-400 text-5xl absolute bottom-20 right-20 animate-float"
                    style="animation-delay: 1s;"></i>
            </div>
        </main>

        <!-- Fonctionnalités -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-800">
            <div class="max-w-7xl mx-auto">
                <h3 class="text-3xl font-bold text-center mb-12 animate-slide-in">Nos atouts pour votre réussite</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="p-6 bg-gray-700 rounded-lg shadow-lg animate-card-in" style="animation-delay: 0.2s;">
                        <i class="fas fa-certificate text-indigo-400 text-4xl mb-4 animate-bounce"></i>
                        <h4 class="text-xl font-semibold mb-2">Formations certifiantes</h4>
                        <p class="text-gray-400">Obtenez des compétences reconnues sur le marché.</p>
                    </div>
                    <div class="p-6 bg-gray-700 rounded-lg shadow-lg animate-card-in" style="animation-delay: 0.4s;">
                        <i class="fas fa-chart-line text-green-400 text-4xl mb-4 animate-bounce"></i>
                        <h4 class="text-xl font-semibold mb-2">Suivi personnalisé</h4>
                        <p class="text-gray-400">Progressez à votre rythme avec un accompagnement dédié.</p>
                    </div>
                    <div class="p-6 bg-gray-700 rounded-lg shadow-lg animate-card-in" style="animation-delay: 0.6s;">
                        <i class="fas fa-users-cog text-yellow-400 text-4xl mb-4 animate-bounce"></i>
                        <h4 class="text-xl font-semibold mb-2">Équipe professionnelle</h4>
                        <p class="text-gray-400">Des formateurs experts pour vous guider.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Graphique d'évolution -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-900">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-3xl font-bold text-center mb-8 animate-slide-in">Exemple de progression</h3>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg h-80 animate-fade-in">
                    <Line :data="chartData" :options="chartOptions" />
                </div>
            </div>
        </section>

        <!-- Pied de page -->
        <footer class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-800 text-center text-gray-400">
            <p>© {{ new Date().getFullYear() }} Centre de Formation Professionnel La Canadienne. Tous droits réservés.
            </p>
        </footer>
    </div>
</template>

<style scoped>
/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

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

@keyframes cardIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-20px);
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

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

.animate-fade-in {
    animation: fadeInUp 0.8s ease-out forwards;
}

.animate-slide-in {
    animation: slideIn 0.6s ease-out forwards;
}

.animate-card-in {
    animation: cardIn 0.6s ease-out forwards;
}

.animate-float {
    animation: float 4s ease-in-out infinite;
}

.animate-bounce {
    animation: bounce 2s infinite;
}

.animate-spin-slow {
    animation: spinSlow 3s linear infinite;
}

/* Réactivité */
@media (max-width: 640px) {
    h2 {
        font-size: 2.5rem;
    }

    p {
        font-size: 1.125rem;
    }

    .text-4xl {
        font-size: 2rem;
    }

    img {
        height: 2.5rem;
        width: 2.5rem;
    }
}
</style>