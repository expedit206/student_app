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
</script>

<template>
    <MonLayout>
        <div
            class="p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-700 text-gray-100 min-h-screen">
            <!-- En-tête -->
            <header class="mb-6 flex flex-col sm:flex-row justify-between items-center animate-slide-down">
                <div class="text-center sm:text-left">
                    <h1
                        class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 flex items-center justify-center sm:justify-start gap-2 sm:gap-3">
                        <i class="fas fa-user-shield text-cyan-400 text-xl sm:text-2xl animate-pulse"></i>
                        Admin Command Hub
                    </h1>
                    <p class="text-gray-200 mt-1 sm:mt-2 text-sm sm:text-lg animate-fade-in-delay">Bienvenue, {{
                        user.name }}</p>
                </div>
                <TextLink :href="route('logout')" method="post" as="button"
                    class="mt-4 sm:mt-0 bg-gradient-to-r from-orange-500 to-red-600 text-white px-4 py-2 text-sm sm:text-base rounded-full hover:from-orange-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i> Déconnexion
                </TextLink>
            </header>

            <!-- Statistiques avec graphique Donut -->
            <section class="grid grid-cols-1 gap-6 mb-6">
                <!-- Graphique Donut -->
                <div class="bg-gray-800 rounded-2xl shadow-2xl p-4 sm:p-6 animate-pop-in">
                    <h2
                        class="text-lg sm:text-xl font-semibold text-white mb-4 flex items-center justify-center sm:justify-start gap-2">
                        <i class="fas fa-chart-pie text-cyan-400"></i>
                        Répartition des Utilisateurs
                    </h2>
                    <svg viewBox="0 0 36 36" class="w-40 h-40 sm:w-48 sm:h-48 mx-auto">
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#1f2937" stroke-width="4" />
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
                        <text x="18" y="20" text-anchor="middle" fill="white" font-size="6" class="sm:text-8">{{
                            stats.totalUsers }}</text>
                    </svg>
                    <div class="mt-4 text-center text-xs sm:text-sm text-gray-300 space-y-1">
                        <p><span class="text-cyan-400">■</span> Admins: {{ stats.totalAdmins }}</p>
                        <p><span class="text-green-500">■</span> Formateurs: {{ stats.totalFormateurs }}</p>
                        <p><span class="text-purple-500">■</span> Apprenants: {{ stats.totalApprenants }}</p>
                    </div>
                </div>

                <!-- Cartes Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div v-for="(stat, key) in [
                        { icon: 'fas fa-users', title: 'Utilisateurs', value: stats.totalUsers, color: 'from-cyan-500 to-blue-600' },
                        { icon: 'fas fa-user-shield', title: 'Admins', value: stats.totalAdmins, color: 'from-green-500 to-teal-600' },
                        { icon: 'fas fa-chalkboard-teacher', title: 'Formateurs', value: stats.totalFormateurs, color: 'from-purple-500 to-indigo-600' },
                        { icon: 'fas fa-user-graduate', title: 'Apprenants', value: stats.totalApprenants, color: 'from-orange-500 to-yellow-600' },
                        { icon: 'fas fa-book', title: 'Formations', value: stats.totalFormations, color: 'from-pink-500 to-red-600' }
                    ]" :key="key" class="stat-card animate-pop-in" :style="{ animationDelay: `${key * 0.15}s` }">
                        <i :class="[stat.icon, 'text-white text-2xl sm:text-3xl mb-2 sm:mb-3 animate-bounce-slow']"></i>
                        <h3 class="text-sm sm:text-lg font-semibold text-white">{{ stat.title }}</h3>
                        <p class="text-2xl sm:text-4xl font-bold text-white">{{ stat.value }}</p>
                    </div>
                </div>
            </section>

            <!-- Activité récente -->
            <section class="bg-gray-800 rounded-2xl shadow-2xl p-4 sm:p-6 animate-slide-up">
                <h2
                    class="text-lg sm:text-2xl font-semibold text-white mb-4 flex items-center justify-center sm:justify-start gap-2 sm:gap-3">
                    <i class="fas fa-history text-cyan-400 animate-spin-slow"></i>
                    Dernières Activités
                </h2>
                <div class="space-y-3 sm:space-y-4 max-h-72 sm:max-h-80 overflow-y-auto custom-scrollbar">
                    <div v-for="activity in recentActivity" :key="activity.date"
                        class="p-3 sm:p-4 bg-gradient-to-r from-gray-700 to-gray-600 rounded-lg hover:from-cyan-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <p class="text-xs sm:text-sm text-white font-medium">{{ activity.description }}</p>
                        <p class="text-xs text-gray-300 mt-1">{{ new Date(activity.date).toLocaleString() }}</p>
                    </div>
                    <div v-if="!recentActivity.length" class="text-center text-gray-400 italic py-4 text-xs sm:text-sm">
                        Aucune activité pour le moment
                    </div>
                </div>
            </section>

            <!-- Actions rapides -->
            <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-6">
                <TextLink v-for="(action, index) in [
                    { href: 'apprenants.index', icon: 'fas fa-users', label: 'Apprenants', color: 'from-orange-500 to-yellow-600' },
                    { href: 'formateurs.index', icon: 'fas fa-chalkboard-teacher', label: 'Formateurs', color: 'from-purple-500 to-indigo-600' },
                    { href: 'formations.index', icon: 'fas fa-book', label: 'Formations', color: 'from-cyan-500 to-blue-600' }
                ]" :key="index" :href="route(action.href)"
                    class="bg-gradient-to-r text-white px-4 py-3 sm:px-6 sm:py-4 text-sm sm:text-base rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 flex items-center justify-center gap-2 sm:gap-3 shadow-lg hover:shadow-xl transform hover:scale-105 animate-pop-in"
                    :class="action.color" :style="{ animationDelay: `${0.6 + index * 0.15}s` }">
                    <i :class="[action.icon, 'text-white text-lg sm:text-xl']"></i>
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
        transform: translateY(-30px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes popIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }

    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes bounceSlow {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-6px);
    }
}

.animate-slide-down {
    animation: slideDown 0.6s ease-out;
}

.animate-pop-in {
    animation: popIn 0.5s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out;
}

.animate-bounce-slow {
    animation: bounceSlow 2.5s infinite ease-in-out;
}

.animate-fade-in-delay {
    animation: fadeIn 0.5s ease-out 0.3s both;
}

/* Styles */
.stat-card {
    background: linear-gradient(145deg, #1f2937, #2d3748);
    padding: 1rem;
    border-radius: 1rem;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
}

/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #1f2937;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #22d3ee, #10b981);
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #06b6d4, #059669);
}

/* Réactivité */
@media (max-width: 640px) {
    .p-4 {
        padding: 1rem;
    }

    .p-6 {
        padding: 1.5rem;
    }

    .mb-6 {
        margin-bottom: 1.5rem;
    }

    .gap-6 {
        gap: 1.5rem;
    }

    /* En-tête */
    h1 {
        font-size: 1.5rem;
    }

    .text-2xl {
        font-size: 1.25rem;
    }

    .text-4xl {
        font-size: 1.75rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    /* Graphique */
    svg {
        width: 10rem;
        height: 10rem;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    /* Stats */
    .stat-card {
        padding: 0.75rem;
    }

    .text-lg {
        font-size: 0.875rem;
    }

    .text-2xl {
        font-size: 1.25rem;
    }

    .text-3xl {
        font-size: 1.5rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    /* Activité */
    .text-2xl {
        font-size: 1.125rem;
    }

    .p-3 {
        padding: 0.75rem;
    }

    .space-y-3 {
        margin-top: 0.75rem;
    }

    .max-h-72 {
        max-height: 16rem;
    }

    /* Actions */
    .mt-6 {
        margin-top: 1.5rem;
    }

    .gap-4 {
        gap: 1rem;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .text-lg {
        font-size: 0.875rem;
    }
}

@media (min-width: 641px) and (max-width: 1024px) {
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .p-6 {
        padding: 1.5rem;
    }

    .text-4xl {
        font-size: 2.25rem;
    }
}
</style>