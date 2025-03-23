<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import TextLink from './TextLink.vue';
import axios from 'axios';

const showSidebar = ref(false);
const toggleSidebar = () => { showSidebar.value = !showSidebar.value };

const page = usePage();
const user = page.props.auth.user;

const isAdmin = user && user.role === 'admin';
const isFormateur = user && user.role === 'formateur';
const isApprenant = user && user.role === 'apprenant';

// Notifications
const localNotifications = ref(page.props.dashboardData?.notifications || []);
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
        headers: { 'X-CSRF-TOKEN': csrfToken },
    })
        .then(response => {
            if (response.data.success) {
                localNotifications.value = localNotifications.value.filter(n => n.id !== notificationId);
            }
        })
        .catch(error => {
            console.error('Error:', error.response?.data || error.message);
        });
};

// Route active
const isActive = (routeName: string) => route().current(routeName);

// PWA Install Prompt
const deferredPrompt = ref<any>(null);
const isInstallable = ref(false);

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt.value = e;
    isInstallable.value = true;
});

const installApp = async () => {
    if (deferredPrompt.value) {
        deferredPrompt.value.prompt();
        const { outcome } = await deferredPrompt.value.userChoice;
        if (outcome === 'accepted') {
            console.log('Application installée');
        } else {
            console.log('Installation refusée');
        }
        deferredPrompt.value = null;
        isInstallable.value = false;
    }
};

const showFallback = () => {
    alert('Ajoutez cette page à votre écran d’accueil via le menu du navigateur (ex. "Ajouter à l’écran d’accueil" sur Chrome).');
};
</script>

<template>
    <div class="min-h-screen bg-gray-900 text-gray-100 flex overflow-hidden">
        <!-- Sidebar -->
        <aside
            :class="['sidebar flex-shrink-0 bg-gradient-to-b from-gray-800 to-gray-900 shadow-xl transition-all duration-300', showSidebar ? 'w-64' : 'w-0 lg:w-64']">
            <div class="flex flex-col h-full">
                <!-- Logo et Toggle interne -->
                <div class="p-4 flex items-center justify-between bg-gray-900">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-1 rounded-full shadow-md">
                            <img src="/img/logo.png" alt="Logo CFPCa"
                                class="h-10 w-10 rounded-full animate-spin-slow" />
                        </div>
                        <span class="text-lg font-bold text-white">CFPCa</span>
                    </div>
                    <button @click="toggleSidebar"
                        class="lg:hidden text-gray-200 hover:text-white p-2 rounded-full hover:bg-gray-600 transition-all">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 py-4">
                    <!-- Apprenant -->
                    <div v-if="isApprenant" class="space-y-2">
                        <TextLink :href="route('apprenant.dashboard')"
                            :class="['nav-link', { 'active': isActive('apprenant.dashboard') }]">
                            <i class="fas fa-tachometer-alt w-6 text-indigo-400"></i>
                            <span class="flex-1">Dashboard</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.disciplines')"
                            :class="['nav-link', { 'active': isActive('apprenant.disciplines') }]">
                            <i class="fas fa-book-open w-6 text-indigo-400"></i>
                            <span class="flex-1">Mes Disciplines</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.notes')"
                            :class="['nav-link', { 'active': isActive('apprenant.notes') }]">
                            <i class="fas fa-star w-6 text-indigo-400"></i>
                            <span class="flex-1">Mes Notes</span>
                            <span v-if="unreadNoteNotifications > 0"
                                class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 ml-2 animate-pulse">
                                {{ unreadNoteNotifications }}
                            </span>
                        </TextLink>
                        <TextLink :href="route('apprenant.carnet')"
                            :class="['nav-link', { 'active': isActive('apprenant.carnet') }]">
                            <i class="fas fa-book w-6 text-indigo-400"></i>
                            <span class="flex-1">Mon Carnet</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.progression')"
                            :class="['nav-link', { 'active': isActive('apprenant.progression') }]">
                            <i class="fas fa-chart-line w-6 text-indigo-400"></i>
                            <span class="flex-1">Ma Progression</span>
                        </TextLink>
                    </div>

                    <!-- Formateur -->
                    <div v-if="isFormateur" class="space-y-2">
                        <TextLink :href="route('formateur.dashboard')"
                            :class="['nav-link', { 'active': isActive('formateur.dashboard'), 'formateur-active': isActive('formateur.dashboard') }]">
                            <i class="fas fa-tachometer-alt w-6 text-red-400"></i>
                            <span class="flex-1">Dashboard</span>
                        </TextLink>
                        <TextLink :href="route('formateur.formations')"
                            :class="['nav-link', { 'active': isActive('formateur.formations'), 'formateur-active': isActive('formateur.formations') }]">
                            <i class="fas fa-book w-6 text-red-400"></i>
                            <span class="flex-1">Mes Formations</span>
                        </TextLink>
                        <TextLink :href="route('formateur.apprenants')"
                            :class="['nav-link', { 'active': isActive('formateur.apprenants'), 'formateur-active': isActive('formateur.apprenants') }]">
                            <i class="fas fa-users w-6 text-red-400"></i>
                            <span class="flex-1">Mes Apprenants</span>
                        </TextLink>
                        <TextLink :href="route('formateur.notes')"
                            :class="['nav-link', { 'active': isActive('formateur.notes'), 'formateur-active': isActive('formateur.notes') }]">
                            <i class="fas fa-star w-6 text-red-400"></i>
                            <span class="flex-1">Gérer les Notes</span>
                        </TextLink>
                        <TextLink :href="route('formateur.disciplines')"
                            :class="['nav-link', { 'active': isActive('formateur.disciplines'), 'formateur-active': isActive('formateur.disciplines') }]">
                            <i class="fas fa-book-open w-6 text-red-400"></i>
                            <span class="flex-1">Mes Disciplines</span>
                        </TextLink>
                    </div>

                    <!-- Admin -->
                    <div v-if="isAdmin" class="space-y-2">
                        <TextLink :href="route('admin.dashboard')"
                            :class="['nav-link', { 'active': isActive('admin.dashboard') }]">
                            <i class="fas fa-tachometer-alt w-6 text-white"></i>
                            <span class="flex-1">Dashboard Admin</span>
                        </TextLink>
                        <TextLink :href="route('apprenants.index')"
                            :class="['nav-link', { 'active': isActive('apprenants.index') }]">
                            <i class="fas fa-user-tie w-6 text-white"></i>
                            <span class="flex-1">Apprenants</span>
                        </TextLink>
                        <TextLink :href="route('formateurs.index')"
                            :class="['nav-link', { 'active': isActive('formateurs.index') }]">
                            <i class="fas fa-user-tie w-6 text-white"></i>
                            <span class="flex-1">Formateurs</span>
                        </TextLink>
                        <TextLink :href="route('formations.index')"
                            :class="['nav-link', { 'active': isActive('formations.index') }]">
                            <i class="fas fa-book w-6 text-white"></i>
                            <span class="flex-1">Formations</span>
                        </TextLink>
                        <TextLink :href="route('disciplines.index')"
                            :class="['nav-link', { 'active': isActive('disciplines.index') }]">
                            <i class="fas fa-book-open w-6 text-white"></i>
                            <span class="flex-1">Disciplines</span>
                        </TextLink>
                    </div>

                    <!-- Notifications -->
                    <div v-if="isApprenant" class="mt-6 px-4">
                        <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center gap-2">
                            <i class="fas fa-bell text-indigo-400"></i> Notifications
                        </h3>
                        <div v-if="localNotifications.length" class="space-y-3 max-h-64 overflow-y-auto">
                            <div v-for="notification in localNotifications" :key="notification.id"
                                class="p-3 bg-gray-700 rounded-lg shadow-md hover:bg-gray-600 transition-all duration-200">
                                <p class="text-sm text-white">{{ notification.message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ notification.created_at }}</p>
                                <button @click="markAsRead(notification.id)"
                                    class="text-indigo-400 hover:text-indigo-300 text-xs mt-2 transition-colors">
                                    Marquer comme lu
                                </button>
                            </div>
                        </div>
                        <p v-else class="text-sm text-gray-400 italic">Aucune notification</p>
                    </div>

                    <!-- Bouton PWA -->
                    <div class="mt-4 px-4">
                        <button v-if="isInstallable" @click="installApp"
                            class="nav-link w-full text-left bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-download w-6 text-white"></i>
                            <span class="flex-1">Installer l’app</span>
                        </button>
                        <button v-else @click="showFallback"
                            class="nav-link w-full text-left bg-gray-600 hover:bg-gray-700">
                            <i class="fas fa-plus w-6 text-white"></i>
                            <span class="flex-1">Ajouter manuellement</span>
                        </button>
                    </div>
                </nav>

                <!-- Profil -->
                <div class="p-4 mt-auto">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button
                                class="flex items-center justify-between w-full px-4 py-3 bg-gray-800 text-gray-200 rounded-lg hover:bg-gray-700 transition-all duration-300">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-user w-6 text-indigo-400"></i>
                                    <span class="flex-1">Profil</span>
                                    <span v-if="localNotifications.length"
                                        class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 animate-pulse">
                                        {{ localNotifications.length }}
                                    </span>
                                </span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="bg-gray-800 text-gray-100 rounded-lg shadow-lg w-48">
                            <TextLink class="block px-4 py-2 hover:bg-gray-700 transition-all"
                                :href="route('profile.edit')" as="button">
                                Paramètres
                            </TextLink>
                            <TextLink class="block px-4 py-2 hover:bg-gray-700 transition-all" method="post"
                                :href="route('logout')" as="button">
                                Déconnexion
                            </TextLink>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main
            class="flex-1 p-4 sm:p-6 bg-gray-800 overflow-y-auto transition-all duration-300 relative animate-fade-in">
            <button v-if="!showSidebar" @click="toggleSidebar"
                class="lg:hidden fixed top-4 left-4 z-30 bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:bg-indigo-700 transition-all">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <slot></slot>
            <div class="scroll-hint bg-indigo-500 text-white px-3 py-1 rounded-full text-xs">Défiler</div>
        </main>
    </div>
</template>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Inter', 'Arial', sans-serif;
}

/* Animations */
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

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.animate-spin-slow {
    animation: spinSlow 6s linear infinite;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    z-index: 20;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    color: #d1d5db;
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: #4b5563;
    color: #ffffff;
    transform: translateX(4px);
}

.nav-link.active {
    background-color: #4f46e5;
    color: #ffffff;
    border-left-color: #ffffff;
    font-weight: 600;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
}

.nav-link.active i {
    color: #ffffff !important;
}

.nav-link.formateur-active {
    background-color: #b91c1c;
}

/* Scrollbars */
.sidebar::-webkit-scrollbar,
main::-webkit-scrollbar {
    width: 8px;
}

.sidebar::-webkit-scrollbar-track,
main::-webkit-scrollbar-track {
    background: #1f2937;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 4px;
}

main::-webkit-scrollbar-thumb {
    background: #4f46e5;
    border-radius: 4px;
}

main::-webkit-scrollbar-thumb:hover {
    background: #6366f1;
}

/* Scroll Hint */
.scroll-hint {
    position: fixed;
    bottom: 16px;
    right: 16px;
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

main:hover .scroll-hint {
    opacity: 0;
}

/* Réactivité */
@media (max-width: 1023px) {
    .sidebar.w-64 {
        width: 100%;
        max-width: 280px;
    }

    .sidebar.w-0 {
        width: 0;
        overflow: hidden;
    }

    main {
        margin-left: 0;
        width: 100%;
    }
}

@media (min-width: 1024px) {
    .sidebar {
        width: 256px;
    }

    main {
        margin-left: 256px;
    }
}
</style>