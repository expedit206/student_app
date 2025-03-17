<script setup lang="ts">
import { ref } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { usePage } from '@inertiajs/vue3';
import TextLink from './TextLink.vue';

const showSidebar = ref(true); // État initial de la sidebar
const toggleSidebar = () => { showSidebar.value = !showSidebar.value };

const page = usePage();
const user = page.props.auth.user;

// Vérifie si l'utilisateur est admin
const isAdmin = user && user.role === 'admin';
const isFormateur = user && user.role === 'formateur';
</script>

<style>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Arial', sans-serif;
}

.transition {
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.transition:hover {
    background-color: #4a4a4a;
    transform: scale(1.05);
}

.sidebar {
    background: linear-gradient(to bottom, #2d3748, #1a202c);
    width: 250px;
    /* Largeur de la sidebar */
    transition: width 0.3s ease;
    /* Transition pour la largeur */
}

.sidebar.collapsed {
    width: 52px;
    /* Largeur lorsque la sidebar est réduite */
}

.header {
    background: linear-gradient(to right, #4a5568, #2d3748);
}
</style>

<template>
    <div class="h-screen bg-gray-900 text-gray-100 overflow-hidden">
        <!-- Header -->
        <header class="header shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-chalkboard-teacher text-2xl mr-3 text-blue-400 animate-spin"></i>
                    <h1 class="text-xl font-bold">CFPCa - Tableau de Bord Formateur</h1>
                </div>
                <button @click="toggleSidebar" class="lg:hidden transition">
                    <i class="fas fa-bars text-xl text-gray-300"></i>
                </button>
            </div>
        </header>

        <div class="flex h-[calc(100vh-62px)]">
            <!-- Sidebar -->
            <aside
                :class="['sidebar h-full shadow-xl transition-transform duration-300 overflow-y-auto', { 'collapsed': !showSidebar }]">
                <button @click="toggleSidebar"
                    class="flex items-center justify-center bg-gray-700 text-gray-300 w-full py-2">
                    <i :class="showSidebar ? 'fas fa-chevron-left' : 'fas fa-chevron-right'"></i>
                </button>
                <nav class="mt-8">
                    <div v-if="isFormateur">
                        <TextLink :href="route('formateur.dashboard')"
                            class="flex items-center px-6 py-3 text-blue-400 bg-gray-700 border-r-4 border-blue-400 transition">
                            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                        </TextLink>
                        <TextLink :href="route('formateur.formations')"
                            class="flex items-center px-6 py-3 text-gray-300 transition hover:bg-gray-600">
                            <i class="fas fa-book mr-3"></i>Mes Formations
                        </TextLink>
                        <TextLink :href="route('formateur.apprenants')"
                            class="flex items-center px-6 py-3 text-gray-300 transition hover:bg-gray-600">
                            <i class="fas fa-users mr-3"></i>Mes Apprenants
                        </TextLink>
                        <TextLink :href="route('formateur.notes')"
                            class="flex items-center px-6 py-3 text-gray-300 transition hover:bg-gray-600">
                            <i class="fas fa-star mr-3"></i>Voir Notes
                        </TextLink>
                        <TextLink :href="route('formateur.disciplines')"
                            class="flex items-center px-6 py-3 text-gray-300 transition hover:bg-gray-600">
                            <i class="fas fa-book-open mr-3"></i>Mes Disciplines
                        </TextLink>
                    </div>

                    <div v-if="isAdmin">
                        <TextLink :href="route('admin.dashboard')"
                            class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard Admin
                        </TextLink>
                        <TextLink :href="route('formateurs.index')"
                            class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                            <i class="fas fa-user-tie mr-3"></i>Formateurs
                        </TextLink>
                        <TextLink :href="route('formations.index')"
                            class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                            <i class="fas fa-book mr-3"></i>Formations
                        </TextLink>
                    </div>
                </nav>

                <!-- Profile Menu -->
                <div class="mt-auto">
                    <DropdownMenu>
                        <DropdownMenuTrigger>
                            <button
                                class="flex items-center justify-between w-full px-6 py-3 text-gray-300 transition hover:bg-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <TextLink class="block w-full" :href="route('profile.edit')" as="button">Paramètres
                            </TextLink>
                            <TextLink class="block w-full" method="post" :href="route('logout')" as="button">Déconnexion
                            </TextLink>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-scroll">
                <slot></slot> <!-- Contenu spécifique aux pages sera inséré ici -->
            </main>
        </div>
    </div>
</template>