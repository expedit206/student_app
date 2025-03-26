<script setup lang="ts">
import { defineProps, computed } from 'vue';
import TextLink from '../../components/TextLink.vue';
import MonLayout from '@/components/MonLayout.vue';

const props = defineProps<{
    user: { name: string; email: string };
    stats: { totalUsers: number; totalAdmins: number; totalFormateurs: number; totalApprenants: number; totalFormations: number };
    recentActivity: { description: string; date: string }[];
}>();

// Calculs pour le graphique donut
const donutData = computed(() => {
    const total = props.stats.totalUsers || 1; // Éviter division par 0
    return {
        admins: (props.stats.totalAdmins / total) * 100,
        formateurs: (props.stats.totalFormateurs / total) * 100,
        apprenants: (props.stats.totalApprenants / total) * 100,
    };
});

// Couleurs dynamiques pour les icônes des stats
const statIconColors = [
    { base: 'text-cyan-400', hover: 'text-cyan-300' },
    { base: 'text-green-500', hover: 'text-green-400' },
    { base: 'text-purple-500', hover: 'text-purple-400' },
    { base: 'text-orange-500', hover: 'text-orange-400' },
    { base: 'text-pink-500', hover: 'text-pink-400' },
];

// Couleurs dynamiques pour les actions rapides
const actionIconColors = [
    { base: 'text-orange-400', hover: 'text-orange-300' },
    { base: 'text-purple-400', hover: 'text-purple-300' },
    { base: 'text-cyan-400', hover: 'text-cyan-300' },
];
</script>

<template>
    <MonLayout>
        <div
            class="p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-gray-100 dark:from-gray-900 via-gray-50 dark:via-gray-800 to-gray-200 dark:to-gray-700 text-gray-900 dark:text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex flex-col sm:flex-row justify-between items-center animate-slide-down">
                <div class="text-center sm:text-left">
                    <h1
                        class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 flex items-center justify-center sm:justify-start gap-2 sm:gap-3">
                        <i
                            class="fas fa-user-shield text-cyan-400 text-xl sm:text-2xl animate-pulse transition-colors duration-300 hover:text-cyan-300"></i>
                        Tableau de bord de l'Admin
                    </h1>
                    <p class="text-gray-600 dark:text-gray-200 mt-1 sm:mt-2 text-sm sm:text-lg animate-fade-in-delay">
                        Bienvenue, {{
                            user.name }}</p>
                </div>
                <TextLink :href="route('logout')" method="post" as="button"
                    class="mt-4 sm:mt-0 bg-gradient-to-r from-orange-500 to-red-600 text-white px-4 py-2 text-sm sm:text-base rounded-full hover:from-orange-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i
                        class="fas fa-sign-out-alt mr-1 sm:mr-2 transition-colors duration-300 hover:text-orange-300"></i>
                    Déconnexion
                </TextLink>
            </header>

            <!-- Statistiques avec graphique Donut -->
            <section class="grid grid-cols-1 gap-6 mb-6">
                <!-- Graphique Donut -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-4 sm:p-6 animate-pop-in">
                    <h2
                        class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-center sm:justify-start gap-2">
                        <i
                            class="fas fa-chart-pie text-cyan-400 transition-colors duration-300 hover:text-cyan-300"></i>
                        Répartition des Utilisateurs
                    </h2>
                    <svg viewBox="0 0 36 36" class="w-40 h-40 sm:w-48 sm:h-48 mx-auto">
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#e5e7eb dark:stroke-gray-700"
                            stroke-width="4" />
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#22d3ee" stroke-width="4"
                            :stroke-dasharray="`${donutData.admins} ${100 - donutData.admins}`" stroke-dashoffset="25"
                            transform="rotate(-90 18 18)" />
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#10b981" stroke-width="4"
                            :stroke-dasharray="`${donutData.formateurs} ${100 - donutData.formateurs}`"
                            stroke-dashoffset="25" transform="rotate(-90 18 18)"
                            :style="{ strokeDashoffset: 25 - donutData.admins }" />
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#8b5cf6" stroke-width="4"
                            :stroke-dasharray="`${donutData.apprenants} ${100 - donutData.apprenants}`"
                            stroke-dashoffset="25" transform="rotate(-90 18 18)"
                            :style="{ strokeDashoffset: 25 - donutData.admins - donutData.formateurs }" />
                        <text x="18" y="20" text-anchor="middle" fill="gray-100 dark:fill-white" font-size="6"
                            class="sm:text-8 ">{{
                                stats.totalUsers }}</text>
                    </svg>
                    <div class="mt-4 text-center text-xs sm:text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <p><span class="text-cyan-400">■</span> Admins: {{ stats.totalAdmins }}</p>
                        <p><span class="text-green-500">■</span> Formateurs: {{ stats.totalFormateurs }}</p>
                        <p><span class="text-purple-500">■</span> Apprenants: {{ stats.totalApprenants }}</p>
                    </div>
                </div>

                <!-- Cartes Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div v-for="(stat, key) in [
                        { icon: 'fas fa-users', title: 'Utilisateurs', value: stats.totalUsers, color: statIconColors[0] },
                        { icon: 'fas fa-user-shield', title: 'Admins', value: stats.totalAdmins, color: statIconColors[1] },
                        { icon: 'fas fa-chalkboard-teacher', title: 'Formateurs', value: stats.totalFormateurs, color: statIconColors[2] },
                        { icon: 'fas fa-user-graduate', title: 'Apprenants', value: stats.totalApprenants, color: statIconColors[3] },
                        { icon: 'fas fa-book', title: 'Formations', value: stats.totalFormations, color: statIconColors[4] }
                    ]" :key="key"
                        class="stat-card bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition-all duration-300 animate-pop-in"
                        :style="{ animationDelay: `${key * 0.15}s` }">
                        <i
                            :class="[stat.icon, stat.color.base, 'text-2xl sm:text-3xl mb-2 sm:mb-3 animate-bounce-slow transition-colors duration-300']"></i>
                        <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">{{ stat.title }}</h3>
                        <p class="text-2xl sm:text-4xl font-bold text-gray-900 dark:text-white">{{ stat.value }}</p>
                    </div>
                </div>
            </section>

            <!-- Activité récente -->
            <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-4 sm:p-6 animate-slide-up">
                <h2
                    class="text-lg sm:text-2xl font-semibold text-gray-900 dark:text-white  mb-4 flex items-center justify-center sm:justify-start gap-2 sm:gap-3">
                    <i
                        class="fas fa-history text-cyan-400 animate-spin-slow transition-colors duration-300 hover:text-cyan-300"></i>
                    Dernières Activités
                </h2>
                <div class="space-y-3 sm:space-y-4 max-h-72 sm:max-h-80 overflow-y-auto custom-scrollbar">
                    <div v-for="activity in recentActivity" :key="activity.date"
                        class="p-3 sm:p-4 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-cyan-100 dark:hover:bg-cyan-900 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <p class="text-xs sm:text-sm text-gray-900 dark:text-white font-medium">{{ activity.description
                            }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">{{ new
                            Date(activity.date).toLocaleString() }}</p>
                    </div>
                    <div v-if="!recentActivity.length"
                        class="text-center text-gray-500 dark:text-gray-400 italic py-4 text-xs sm:text-sm">
                        Aucune activité pour le moment
                    </div>
                </div>
            </section>

            <!-- Actions rapides -->
            <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-6">
                <TextLink v-for="(action, index) in [
                    { href: 'apprenants.index', icon: 'fas fa-users', label: 'Apprenants', color: actionIconColors[0] },
                    { href: 'formateurs.index', icon: 'fas fa-chalkboard-teacher', label: 'Formateurs', color: actionIconColors[1] },
                    { href: 'formations.index', icon: 'fas fa-book', label: 'Formations', color: actionIconColors[2] }
                ]" :key="index" :href="route(action.href)"
                    class="bg-gradient-to-r dark:text-white px-4 py-3 sm:px-6 sm:py-4 text-sm sm:text-base rounded-xl transition-all duration-300 flex items-center justify-center gap-2 sm:gap-3 shadow-lg hover:shadow-xl transform hover:scale-105 animate-pop-in"
                    :class="[action.color.base.replace('text-', 'from-') + ' to-gray-200 dark:to-gray-800', { 'hover:to-gray-100 dark:hover:to-gray-700': true }]"
                    :style="{ animationDelay: `${0.6 + index * 0.15}s` }">
                    <i
                        :class="[action.icon, action.color.base, 'text-lg sm:text-xl transition-colors duration-300']"></i>
                    <span class="font-semibold">{{ action.label }}</span>
                </TextLink>
            </section>
        </div>
    </MonLayout>
</template>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

/* Animations */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInDelay {
    0% {
        opacity: 0;
    }

    50% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

@keyframes popIn {
    0% {
        opacity: 0;
        transform: scale(0.9);
    }

    50% {
        opacity: 1;
        transform: scale(1.05);
    }

    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceSlow {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-5px);
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

.animate-slide-down {
    animation: slideDown 0.5s ease-out;
}

.animate-fade-in-delay {
    animation: fadeInDelay 1s ease-out;
}

.animate-pop-in {
    animation: popIn 0.5s ease-out forwards;
}

.animate-slide-up {
    animation: slideUp 0.5s ease-out;
}

.animate-bounce-slow {
    animation: bounceSlow 2s infinite;
}

.animate-spin-slow {
    animation: spinSlow 4s linear infinite;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #e5e7eb;
}
html.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: #686969 ;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #4f46e5;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #6366f1;
}
html.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c26ab5 ;
    border-radius: 4px;
}

html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #deb2e4 ;
}

/* Dynamisation des couleurs au survol */
html .stat-card:hover i.text-cyan-400 {
    color: #cffafe;
}

/* cyan-300 */
html.dark .stat-card:hover i.text-cyan-400 {
    color: #cffafe;
}

html .stat-card:hover i.text-green-500 {
    color: #34d399;
}

/* green-400 */
html.dark .stat-card:hover i.text-green-500 {
    color: #34d399;
}

html .stat-card:hover i.text-purple-500 {
    color: #c084fc;
}

/* purple-400 */
html.dark .stat-card:hover i.text-purple-500 {
    color: #c084fc;
}

html .stat-card:hover i.text-orange-500 {
    color: #fb923c;
}

/* orange-400 */
html.dark .stat-card:hover i.text-orange-500 {
    color: #fb923c;
}

html .stat-card:hover i.text-pink-500 {
    color: #f472b6;
}

/* pink-400 */
html.dark .stat-card:hover i.text-pink-500 {
    color: #f472b6;
}

html .bg-gradient-to-r:hover i.text-orange-400 {
    color: #fdba74;
}

/* orange-300 */
html.dark .bg-gradient-to-r:hover i.text-orange-400 {
    color: #fdba74;
}

html .bg-gradient-to-r:hover i.text-purple-400 {
    color: #d8b4fe;
}

/* purple-300 */
html.dark .bg-gradient-to-r:hover i.text-purple-400 {
    color: #d8b4fe;
}

html .bg-gradient-to-r:hover i.text-cyan-400 {
    color: #cffafe;
}

/* cyan-300 */
html.dark .bg-gradient-to-r:hover i.text-cyan-400 {
    color: #cffafe;
}
</style>