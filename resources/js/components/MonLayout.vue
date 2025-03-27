<script setup lang="ts">
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import TextLink from './TextLink.vue';
import axios from 'axios';


import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';


const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Light' },
    { value: 'dark', Icon: Moon, label: 'Dark' },
] as const;


const isSidebarExpanded = ref(true); // État pour desktop (étendu/réduit)
const isSidebarVisible = ref(false); // État pour mobile (visible/caché)
const isMobile = ref(false); // État pour détecter mobile

// Détecter la taille de l’écran
const updateScreenSize = () => {
    isMobile.value = window.innerWidth < 1024;
    if (isMobile.value) {
        isSidebarVisible.value = false; // Cacher par défaut sur mobile
    } else {
        isSidebarVisible.value = true; // Garder visible sur desktop
    }
};

const toggleSidebar = () => {
    if (isMobile.value) {
        isSidebarVisible.value = !isSidebarVisible.value; // Mobile : toggle visibilité
    } else {
        isSidebarExpanded.value = !isSidebarExpanded.value; // Desktop : toggle largeur
    }
};

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

const resetPWA = async () => {
    if ('serviceWorker' in navigator) {
        const registrations = await navigator.serviceWorker.getRegistrations();
        for (const registration of registrations) {
            await registration.unregister();
        }
        await navigator.serviceWorker.register('/service-worker.js');
        console.log('Service Worker réinitialisé');
    }
};

onMounted(() => {
    resetPWA();
    updateScreenSize(); // Initialiser la détection
    window.addEventListener('resize', updateScreenSize); // Mettre à jour au redimensionnement
});

onUnmounted(() => {
    window.removeEventListener('resize', updateScreenSize); // Nettoyer l’écouteur
});
</script>

<template>


    <div
        class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex overflow-hidden relative">
        <!-- Sidebar -->
        <div :class="['inline-flex gap-1 rounded-lg  p-1']"
            class="fixed top-0 right-0 mt-1 dark:bg-slate-600 bg-slate-300 mr-4 z-10  md:flex">
            <button v-for="{ value, Icon, label } in tabs" :key="value" @click="updateAppearance(value)" :class="[
                'flex items-center rounded-md px-2 py-[.2rem] transition-colors',
                appearance === value
                    ? 'bg-white shadow-sm text-neutral-900 dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-700/60 dark:hover:text-neutral-100',
            ]">
                <component :is="Icon" class="-ml-1 h-4 w-4" />
                <!-- <span class="ml-1.5 text-sm">{{ label }}</span> -->
            </button>
        </div>
        <aside :class="['sidebar flex-shrink-0 bg-gradient-to-b from-gray-200 dark:from-gray-800 to-gray-300 dark:to-gray-900 shadow-xl transition-all duration-300 fixed top-0 left-0 h-full z-20',
            {
                'w-64': (!isMobile && isSidebarExpanded) || (isMobile && isSidebarVisible),
                'w-16': !isMobile && !isSidebarExpanded,
                'w-0': isMobile && !isSidebarVisible
            }]">
            <div class="flex flex-col h-full">
                <!-- Logo et Toggle -->
                <div class="p-4 flex items-center justify-between bg-gray-300 dark:bg-gray-900">
                    <div v-if="(!isMobile && isSidebarExpanded) || (isMobile && isSidebarVisible)"
                        class="flex items-center gap-3">
                        <div class="bg-white p-1 rounded-full shadow-md">
                            <img src="/img/logo.png" alt="Logo CFPCa"
                                class="h-10 w-10 rounded-full animate-spin-slow" />
                        </div>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">CFPCa</span>
                    </div>
                    <button @click="toggleSidebar"
                        class="text-gray-600 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white p-2 rounded-full hover:bg-gray-400 dark:hover:bg-gray-600 transition-all ml-auto">
                        <i :class="isMobile ? (isSidebarVisible ? 'fas fa-times' : 'fas fa-bars') : (isSidebarExpanded ? 'fas fa-chevron-left' : 'fas fa-chevron-right')"
                            class="text-lg"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 py-4">
                    <!-- Apprenant -->
                    <div v-if="isApprenant" class="space-y-2">
                        <TextLink :href="route('apprenant.dashboard')"
                            :class="['nav-link', { 'active': isActive('apprenant.dashboard') }]">
                            <i class="fas fa-home w-6 text-cyan-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                class="flex-1">Dashboard</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.disciplines')"
                            :class="['nav-link', { 'active': isActive('apprenant.disciplines') }]">
                            <i class="fas fa-graduation-cap w-6 text-indigo-500"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Mes
                                Disciplines</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.notes')"
                            :class="['nav-link', { 'active': isActive('apprenant.notes') }]">
                            <i class="fas fa-clipboard-check w-6 text-yellow-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Mes
                                Notes</span>
                            <span
                                v-if="unreadNoteNotifications > 0 && (!isMobile ? isSidebarExpanded : isSidebarVisible)"
                                class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 ml-2 animate-pulse">
                                {{ unreadNoteNotifications }}
                            </span>
                        </TextLink>
                        <TextLink :href="route('apprenant.carnet')"
                            :class="['nav-link', { 'active': isActive('apprenant.carnet') }]">
                            <i class="fas fa-address-card w-6 text-green-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Mon
                                Carnet</span>
                        </TextLink>
                        <TextLink :href="route('apprenant.progression')"
                            :class="['nav-link', { 'active': isActive('apprenant.progression') }]">
                            <i class="fas fa-arrow-trend-up w-6 text-purple-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Ma
                                Progression</span>
                        </TextLink>
                    </div>

                    <!-- Formateur -->
                    <div v-if="isFormateur" class="space-y-2">
                        <TextLink :href="route('formateur.dashboard')"
                            :class="['nav-link', { 'active': isActive('formateur.dashboard'), 'formateur-active': isActive('formateur.dashboard') }]">
                            <i class="fas fa-home w-6 text-red-500"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                class="flex-1">Dashboard</span>
                        </TextLink>
                        <TextLink :href="route('formateur.formations')"
                            :class="['nav-link', { 'active': isActive('formateur.formations'), 'formateur-active': isActive('formateur.formations') }]">
                            <i class="fas fa-chalkboard w-6 text-orange-500"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Mes
                                Formations</span>
                        </TextLink>
                        <TextLink :href="route('formateur.apprenants')"
                            :class="['nav-link', { 'active': isActive('formateur.apprenants'), 'formateur-active': isActive('formateur.apprenants') }]">
                            <i class="fas fa-user-graduate w-6 text-yellow-500"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Mes
                                Apprenants</span>
                        </TextLink>
                        <TextLink :href="route('formateur.notes')"
                            :class="['nav-link', { 'active': isActive('formateur.notes'), 'formateur-active': isActive('formateur.notes') }]">
                            <i class="fas fa-pen w-6 text-pink-500"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Gérer les
                                Notes</span>
                        </TextLink>
                        <TextLink :href="route('formateur.disciplines')"
                            :class="['nav-link', { 'active': isActive('formateur.disciplines'), 'formateur-active': isActive('formateur.disciplines') }]">
                            <i class="fas fa-graduation-cap w-6 text-teal-500"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Mes
                                Disciplines</span>
                        </TextLink>
                    </div>

                    <!-- Admin -->
                    <div v-if="isAdmin" class="space-y-2">
                        <TextLink :href="route('admin.dashboard')"
                            :class="['nav-link', { 'active': isActive('admin.dashboard') }]">
                            <i class="fas fa-home w-6 text-blue-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Dashboard
                                Admin</span>
                        </TextLink>
                        <TextLink :href="route('apprenants.index')"
                            :class="['nav-link', { 'active': isActive('apprenants.index') }]">
                            <i class="fas fa-user-graduate w-6 text-green-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                class="flex-1">Apprenants</span>
                        </TextLink>
                        <TextLink :href="route('formateurs.index')"
                            :class="['nav-link', { 'active': isActive('formateurs.index') }]">
                            <i class="fas fa-chalkboard-teacher w-6 text-purple-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                class="flex-1">Formateurs</span>
                        </TextLink>
                        <TextLink :href="route('formations.index')"
                            :class="['nav-link', { 'active': isActive('formations.index') }]">
                            <i class="fas fa-chalkboard w-6 text-orange-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                class="flex-1">Formations</span>
                        </TextLink>
                        <TextLink :href="route('disciplines.index')"
                            :class="['nav-link', { 'active': isActive('disciplines.index') }]">
                            <i class="fas fa-graduation-cap w-6 text-pink-400"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                class="flex-1">Disciplines</span>
                        </TextLink>
                    </div>

                    <!-- Notifications -->
                    <div v-if="isApprenant && (!isMobile ? isSidebarExpanded : isSidebarVisible)" class="mt-6 px-4">
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-3 flex items-center gap-2">
                            <i class="fas fa-bell text-indigo-400"></i> Notifications
                        </h3>
                        <div v-if="localNotifications.length" class="space-y-3 max-h-64 overflow-y-auto">
                            <div v-for="notification in localNotifications" :key="notification.id"
                                class="p-3 bg-gray-200 dark:bg-gray-700 rounded-lg shadow-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200">
                                <p class="text-sm text-gray-900 dark:text-white">{{ notification.message }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ notification.created_at }}
                                </p>
                                <button @click="markAsRead(notification.id)"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-xs mt-2 transition-colors">
                                    Marquer comme lu
                                </button>
                            </div>
                        </div>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400 italic">Aucune notification</p>
                    </div>

                    <!-- Bouton PWA -->
                    <div class="mt-4 px-4">
                        <button v-if="isInstallable" @click="installApp"
                            class="nav-link w-full text-left bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                            <i class="fas fa-download w-6 text-white"></i>
                            <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible" class="flex-1">Installer
                                l’app</span>
                        </button>
                    </div>
                </nav>

                <!-- Profil -->
                <div class="p-4 mt-auto">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button
                                class="flex items-center justify-between w-full px-4 py-3 bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition-all duration-300">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-user w-6 text-indigo-400"></i>
                                    <span v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                        class="flex-1">Profil</span>
                                    <span
                                        v-if="localNotifications.length && (!isMobile ? isSidebarExpanded : isSidebarVisible)"
                                        class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 animate-pulse">
                                        {{ localNotifications.length }}
                                    </span>
                                    <i v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                                        class="fas fa-chevron-down"></i>
                                </span>
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent v-if="!isMobile ? isSidebarExpanded : isSidebarVisible"
                            class="bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg shadow-lg w-48">
                            <TextLink class="block px-4 py-2 hover:bg-gray-300 dark:hover:bg-gray-700 transition-all"
                                :href="route('profile.edit')" as="button">
                                Paramètres
                            </TextLink>
                            <TextLink class="block px-4 py-2 hover:bg-gray-300 dark:hover:bg-gray-700 transition-all"
                                method="post" :href="route('logout')" as="button">
                                Déconnexion
                            </TextLink>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main
            :class="['flex-1 p-4 sm:p-6 bg-gray-50 dark:bg-gray-800 mb-16  overflow-y-auto transition-all duration-300 relative', isMobile ? 'ml-0' : (isSidebarExpanded ? 'lg:ml-64' : 'lg:ml-16')]">
            <button v-if="isMobile && !isSidebarVisible" @click="toggleSidebar"
                class="fixed top-4 left-4 z-30 bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-all">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <slot></slot>
            <div class="scroll-hint bg-indigo-500 dark:bg-indigo-400 text-white px-3 py-1 rounded-full text-xs">Défiler
            </div>
        </main>
        <footer class="py-6 px-4 bg-gray-50 border-t-2 border-black text-center text-gray-600 absolute bottom-0 right-0"
            :class="isSidebarVisible ? 'w-[80%]' : 'w-full'">
            <p class="text-sm sm:text-base">© {{ new Date().getFullYear() }} Centre de Formation Professionnel La
                Canadienne. Tous droits réservés. By <a href="mailto:mciagnessi@gmail.com">mciagnessi@gmail.com</a></p>
        </footer>

    </div>
</template>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
    font-family: 'Inter', 'Arial', sans-serif;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes spinSlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-fade-in { animation: fadeIn 0.5s ease-out; }
.animate-spin-slow { animation: spinSlow 6s linear infinite; }

/* Sidebar */
.sidebar { z-index: 20; }

html.dark .nav-link{
    color: #d1d5db; /* gray-300 */
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    color: #4b5563; /* gray-600 */
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

html.dark .nav-link:hover{

    background-color: #4b5563; /* gray-600 */
    color: #ffffff; /* white */
}
.nav-link:hover {
    background-color: #d1d5db; /* gray-300 */
    color: #1f2937; /* gray-800 */
    transform: translateX(4px);
}

html.dark .nav-link.active{
    background-color: #4f46e5; /* indigo-600 */
    border-left-color: #ffffff; /* white */
}

.nav-link.active {
    background-color: #4f46e5; /* indigo-600 */
    color: #ffffff; /* white */
    border-left-color: #1f2937; /* gray-800 */
    font-weight: 600;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
}

.nav-link.active i {
    color: #ffffff !important;
}

.nav-link.formateur-active {
    background-color: #b91c1c;
}

/* Ajustement pour sidebar réduite (desktop uniquement) */
.w-16 .nav-link {
    justify-content: center;
    padding: 12px 0;
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
    .sidebar.w-0 {
        width: 0;
        overflow: hidden;
    }

    .sidebar.w-64 {
        width: 100%;
        max-width: 280px;
    }

    main {
        margin-left: 0;
        width: 100%;
    }
}

@media (min-width: 1024px) {
    .sidebar.w-64 {
        width: 256px;
    }

    .sidebar.w-16 {
        width: 64px;
    }
}
</style>