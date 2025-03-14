<script setup lang="ts">
import { ref } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { usePage } from '@inertiajs/vue3';
import TextLink from '../TextLink.vue';
const showSidebar = ref(false);
const toggleSidebar = () => { showSidebar.value = !showSidebar.value };

const page = usePage();
</script>

<style>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Arial', sans-serif;
}
</style>
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
                    <TextLink :href="route('formateur.dashboard')"
                        class="flex items-center px-6 py-3 text-blue-400 bg-gray-700 border-r-4 border-blue-400 hover:bg-gray-600 transition-colors">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </TextLink>
                    <TextLink :href="route('formateur.formations')"
                        class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                    <i class="fas fa-book mr-3"></i>Mes Formations
                    </TextLink>
                    <TextLink 
                    :href="route('formateur.apprenants')"
                        class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 transition-colors">
                    <i class="fas fa-users mr-3"></i>Mes Apprenants
                    </TextLink>
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
                        <TextLink class="block w-full" :href="route('profile.edit')" as="button">Paramètres</TextLink>
                        <TextLink class="block w-full" method="post" :href="route('logout')" as="button">Déconnexion</TextLink>
                    </DropdownMenuContent>
                </DropdownMenu>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-hidden">
                <slot></slot> <!-- Contenu spécifique aux pages sera inséré ici -->
            </main>
        </div>
    </div>
</template>
