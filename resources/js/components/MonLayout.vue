<script setup lang="ts">
import { ref } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import TextLink from './TextLink.vue';
import axios from 'axios'; // Ajoutez ceci en haut de MonLayout.vue
import {watch, computed } from 'vue';


const showSidebar = ref(true); // État initial de la sidebar
const toggleSidebar = () => { showSidebar.value = !showSidebar.value };

const page = usePage();
const user = page.props.auth.user;

// Vérifie les rôles
const isAdmin = user && user.role === 'admin';
const isFormateur = user && user.role === 'formateur';
const isApprenant = user && user.role === 'apprenant';
// Utilisez une variable réactive pour les notifications
const localNotifications = ref(page.props.dashboardData?.notifications || []);

// Synchronisez avec les props initiales si elles changent (ex. navigation)
watch(() => page.props.dashboardData?.notifications, (newNotifications) => {
    localNotifications.value = newNotifications || [];
}, { immediate: true });

const unreadNoteNotifications = computed(() => {
    return localNotifications.value.filter(n => n.type === 'note_update').length;
});

const markAsRead = (notificationId) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    axios.post(route('notifications.read', notificationId), {}, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
    })
        .then(response => {
            if (response.data.success) {
                // Mettez à jour la liste locale réactive
                localNotifications.value = localNotifications.value.filter(n => n.id !== notificationId);
            } else {
                console.error('Failed to mark as read:', response.data.message);
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error.response?.data || error.message);
        });
};
// Vérifie si une route est active
const isActive = (routeName: string) => route().current(routeName);
</script>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Inter', 'Arial', sans-serif;
}

/* Transitions fluides */
.transition-all {
    transition: all 0.3s ease-in-out;
}

/* Sidebar */
.sidebar {
    background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
    width: 250px;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
}

.sidebar.collapsed {
    width: 60px;
}

/* Header */
.header {
    background: linear-gradient(90deg, #4a5568 0%, #2d3748 100%);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Liens */
.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 24px;
    color: #d1d5db;
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: #4a5568;
    color: #ffffff;
    transform: translateX(5px);
}

.nav-link.active {
    background-color: #4a90e2;
    color: #ffffff;
    border-left-color: #ffffff;
    font-weight: 600;
}

/* Bouton toggle */
.toggle-btn {
    background: #374151;
    color: #d1d5db;
    padding: 10px;
    width: 100%;
    border: none;
    transition: background 0.3s ease;
}

.toggle-btn:hover {
    background: #4a5568;
}

/* Scrollbars */
.sidebar::-webkit-scrollbar,
main::-webkit-scrollbar {
    width: 10px;
}

.sidebar::-webkit-scrollbar-track,
main::-webkit-scrollbar-track {
    background: #1a202c;
    border-radius: 5px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #4a5568;
    border-radius: 5px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

main::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #4a90e2, #357abd);
    border-radius: 5px;
    border: 2px solid #2d3748;
}

main::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #63b3ed, #4299e1);
}

/* Scroll hint */
.scroll-hint {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(74, 144, 226, 0.9);
    color: #fff;
    padding: 6px 12px;
    border-radius: 9999px;
    font-size: 12px;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

main:hover .scroll-hint {
    opacity: 0;
}
</style>

<template>
    <div class="h-screen bg-gray-900 text-gray-100 overflow-hidden">
        <!-- Header -->
        <header class="header">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-chalkboard-teacher text-2xl mr-3 text-blue-400 animate-pulse"></i>
                    <h1 class="text-xl font-bold tracking-tight">CFPCa - Dashboard</h1>
                </div>
                <button @click="toggleSidebar" class="lg:hidden text-gray-300 hover:text-white transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </header>

        <div class="flex h-[calc(100vh-64px)]">
            <!-- Sidebar -->
            <aside :class="['sidebar h-full transition-all overflow-y-auto', { 'collapsed': !showSidebar }]">
                <button @click="toggleSidebar" class="toggle-btn">
                    <i :class="showSidebar ? 'fas fa-chevron-left' : 'fas fa-chevron-right'" class="text-lg"></i>
                </button>
                <nav class="mt-6">
                    <!-- Liens pour apprenant -->
                    <!-- Dans la section <nav> pour apprenant -->
                    <div v-if="isApprenant">
                        <TextLink :href="route('apprenant.dashboard')"
                            :class="['nav-link', { 'active': isActive('apprenant.dashboard') }]">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            <span v-if="showSidebar">Dashboard</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.notes')"
                            :class="['nav-link', { 'active': isActive('apprenant.notes') }]">
                            <i class="fas fa-star mr-3"></i>
                            <span v-if="showSidebar">Mes Notes</span>
                            <span v-if="unreadNoteNotifications > 0 && showSidebar" class="badge bg-red-500 rounded-full    text-[10px] px-1 ">
                                {{ unreadNoteNotifications }}
                            </span>
                        </TextLink>
                        <TextLink :href="route('apprenant.carnet')"
                            :class="['nav-link', { 'active': isActive('apprenant.carnet') }]">
                            <i class="fas fa-book mr-3"></i>
                            <span v-if="showSidebar">Mon Carnet</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.progression')"
                            :class="['nav-link', { 'active': isActive('apprenant.progression') }]">
                            <i class="fas fa-chart-line mr-3"></i>
                            <span v-if="showSidebar">Ma Progression</span>
                        </TextLink>
                    </div>

                    <!-- Liens pour formateur -->
                    <div v-if="isFormateur">
                        <TextLink :href="route('formateur.dashboard')"
                            :class="['nav-link', { 'active': isActive('formateur.dashboard') }]">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            <span v-if="showSidebar">Dashboard</span>
                        </TextLink>
                        <TextLink :href="route('formateur.formations')"
                            :class="['nav-link', { 'active': isActive('formateur.formations') }]">
                            <i class="fas fa-book mr-3"></i>
                            <span v-if="showSidebar">Mes Formations</span>
                        </TextLink>
                        <TextLink :href="route('formateur.apprenants')"
                            :class="['nav-link', { 'active': isActive('formateur.apprenants') }]">
                            <i class="fas fa-users mr-3"></i>
                            <span v-if="showSidebar">Mes Apprenants</span>
                        </TextLink>
                        <TextLink :href="route('formateur.notes')"
                            :class="['nav-link', { 'active': isActive('formateur.notes') }]">
                            <i class="fas fa-star mr-3"></i>
                            <span v-if="showSidebar">Gérer les notes</span>
                        </TextLink>
                        <TextLink :href="route('formateur.disciplines')"
                            :class="['nav-link', { 'active': isActive('formateur.disciplines') }]">
                            <i class="fas fa-book-open mr-3"></i>
                            <span v-if="showSidebar">Mes Disciplines</span>
                        </TextLink>
                    </div>

                    <!-- Liens pour admin -->
                    <div v-if="isAdmin">
                        <TextLink :href="route('admin.dashboard')"
                            :class="['nav-link', { 'active': isActive('admin.dashboard') }]">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            <span v-if="showSidebar">Dashboard Admin</span>
                        </TextLink>
                        <TextLink :href="route('apprenants.index')"
                            :class="['nav-link', { 'active': isActive('apprenants.index') }]">
                            <i class="fas fa-user-tie mr-3"></i>
                            <span v-if="showSidebar">Apprenants</span>
                        </TextLink>
                        <TextLink :href="route('formateurs.index')"
                            :class="['nav-link', { 'active': isActive('formateurs.index') }]">
                            <i class="fas fa-user-tie mr-3"></i>
                            <span v-if="showSidebar">Formateurs</span>
                        </TextLink>
                        <TextLink :href="route('formations.index')"
                            :class="['nav-link', { 'active': isActive('formations.index') }]">
                            <i class="fas fa-book mr-3"></i>
                            <span v-if="showSidebar">Formations</span>
                        </TextLink>
                        <TextLink :href="route('disciplines.index')"
                            :class="['nav-link', { 'active': isActive('disciplines.index') }]">
                            <i class="fas fa-book mr-3"></i>
                            <span v-if="showSidebar">Disciplines</span>
                        </TextLink>
                    </div>

                    <!-- Section Notifications -->
                    <!-- Section Notifications -->
                    <div v-if="isApprenant && showSidebar" class="mt-4 px-4">
                        <h3 class="text-sm font-semibold text-gray-400 mb-2">Notifications</h3>
                        <div v-if="localNotifications.length" class="space-y-2 max-h-64 overflow-y-auto">
                            <div v-for="notification in localNotifications" :key="notification.id"
                                class="p-2 bg-gray-700 rounded-lg text-sm text-white hover:bg-gray-600 transition-all">
                                <p>{{ notification.message }}</p>
                                <p class="text-xs text-gray-400">{{ notification.created_at }}</p>
                                <button @click="markAsRead(notification.id)" class="text-teal-400 hover:text-teal-300">
                                    Marquer comme lu
                                </button>
                            </div>
                        </div>
                        <p v-else class="text-sm text-gray-400">Aucune notification</p>
                    </div>
                </nav>

                <!-- Profile Menu -->
                <div class="mt-auto p-4">
                    <DropdownMenu>
                        <DropdownMenuTrigger>
                            <button
                                class="flex items-center justify-between w-full px-4 py-3 text-gray-300 bg-gray-800 rounded-lg hover:bg-gray-700 transition-all">
                                <span class="flex items-center">
                                    <i class="fas fa-user mr-2"></i>
                                    <span v-if="showSidebar">Profil</span>
                                    <span v-if="localNotifications.length"
                                        class="ml-2 bg-red-500 text-white text-xs rounded-full px-2">
                                        {{ localNotifications.length }}
                                    </span>
                                </span>
                                <i class="fas fa-chevron-down" v-if="showSidebar"></i>
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="bg-gray-800 text-gray-100 rounded-lg shadow-lg">
                            <TextLink class="block w-full px-4 py-2 hover:bg-gray-700" :href="route('profile.edit')"
                                as="button">
                                Paramètres
                            </TextLink>
                            <TextLink class="block w-full px-4 py-2 hover:bg-gray-700" method="post"
                                :href="route('logout')" as="button">
                                Déconnexion
                            </TextLink>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto overflow-x-hidden relative transition-all">
                <slot></slot>
                <div class="scroll-hint">Défiler</div>
            </main>
        </div>
    </div>
</template>