<script setup lang="ts">
import { ref } from 'vue'
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

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
})

const showSidebar = ref(false)
const toggleSidebar = () => { showSidebar.value = !showSidebar.value }

const page = usePage();
const user = page.props.auth.user as User;
</script>

<template>
    <div class="h-screen bg-gray-900 text-gray-100 overflow-hidden">
        <!-- Header -->
        <header class="bg-gradient-to-r from-gray-800 to-gray-900 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-chalkboard-teacher text-2xl mr-3 text-blue-400"></i>
                    <h1 class="text-xl font-bold">CFPCa - Tableau de Bord Formateur</h1>
                </div>
                <button @click="toggleSidebar" class="lg:hidden">
                    <i class="fas fa-bars text-xl text-gray-300"></i>
                </button>
            </div>
        </header>

        <div class="flex h-[calc(100vh-72px)]">
            <!-- Sidebar -->
            <aside :class="{ 'translate-x-0': showSidebar, '-translate-x-full': !showSidebar }"
                class="fixed lg:static lg:translate-x-0 w-64 h-full bg-gray-800 shadow-xl transition-transform duration-300 overflow-y-auto">
                <nav class="mt-8">
                    <a href="#"
                        class="flex items-center px-6 py-3 text-blue-400 bg-gray-700 border-r-4 border-blue-400 hover:bg-gray-600 transition-colors">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                        <i class="fas fa-book mr-3"></i>Mes Formations
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                        <i class="fas fa-users mr-3"></i>Mes Apprenants
                    </a>
                </nav>
                <DropdownMenu>
                    <DropdownMenuTrigger>
                        <button
                            class="flex items-center justify-between w-full px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-2"></i> Profil
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <Link class="block w-full" :href="route('profile.edit')" as="button">Settings</Link>
                        <Link class="block w-full" method="post" :href="route('logout')" as="button">Log out</Link>
                    </DropdownMenuContent>
                </DropdownMenu>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-hidden">
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
                        <h3 class="text-xl font-semibold text-white mb-4">
                            <i class="fas fa-graduation-cap mr-2 text-blue-400"></i>Mes Formations
                        </h3>
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
            </main>
        </div>
    </div>
</template>

<style>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Arial', sans-serif;
}
</style>