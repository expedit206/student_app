<script setup lang="ts">
import { ref, computed } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
import TextLink from '@/components/TextLink.vue';

const props = defineProps<{
    apprenants: Array<{
        id: number;
        nom: string;
        prenom: string;
        telephone: string;
        formation_id: number;
        user: { email: string };
    }>;
    formations: Array<{ id: number; titre: string }>;
    selectedFormation: number | null;
}>();

const searchQuery = ref('');
const selectedFormation = ref<number | null>(props.selectedFormation);

const filteredApprenants = computed(() => {
    return props.apprenants.filter(apprenant => {
        const searchTerm = searchQuery.value.toLowerCase();
        const matchesSearch =
            apprenant.nom.toLowerCase().includes(searchTerm) ||
            apprenant.prenom.toLowerCase().includes(searchTerm) ||
            apprenant.user.email.toLowerCase().includes(searchTerm);
        const matchesFormation = selectedFormation.value
            ? apprenant.formation_id === selectedFormation.value
            : true;
        return matchesSearch && matchesFormation;
    });
});

const totalApprenants = computed(() => filteredApprenants.value.length);
const totalFormations = computed(() => props.formations.length);
const apprenantsParFormation = computed(() => {
    const formationsStats = props.formations
        .map(formation => ({
            titre: formation.titre,
            count: props.apprenants.filter(a => a.formation_id === formation.id).length,
        }))
        .sort((a, b) => b.count - a.count);
    const maxCount = formationsStats[0]?.count || 0;
    const topFormations = formationsStats.filter(f => f.count === maxCount);
    return topFormations.length > 0
        ? topFormations.map(f => `${f.titre} (${f.count})`).join(', ')
        : 'Aucune';
});

const getFormationTitre = (formationId: number) => {
    const formation = props.formations.find(f => f.id === formationId);
    return formation ? formation.titre : 'Non assignée';
};

const changeFormation = (formationId: number | null) => {
    selectedFormation.value = formationId;
};

const iconColors = {
    base: 'text-blue-600 dark:text-blue-400',
    hover: 'text-blue-700 dark:text-blue-300',
    phone: 'text-green-600 dark:text-green-400',
    phoneHover: 'text-green-700 dark:text-green-300',
    formation: 'text-purple-600 dark:text-purple-400',
    formationHover: 'text-purple-700 dark:text-purple-300',
    email: 'text-yellow-600 dark:text-yellow-400',
    emailHover: 'text-yellow-700 dark:text-yellow-300',
};
</script>

<template>
    <FormateurLayout>
        <div
            class="p-4 sm:p-6 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen overflow-hidden">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-8 animate-slide-in">
                <h1 class="text-2xl sm:text-3xl font-extrabold flex items-center">
                    <i :class="['fas fa-users mr-3 text-3xl sm:text-4xl', iconColors.base, 'animate-spin-slow']"></i>
                    Mes Apprenants
                </h1>
                <TextLink :href="route('formateur.notes')"
                    class="mt-4 sm:mt-0 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center">
                    <i class="fas fa-star mr-2 animate-pulse"></i> Gérer les notes
                </TextLink>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                    <div class="flex items-center">
                        <i :class="['fas fa-user-graduate text-2xl', iconColors.base, 'animate-pulse']"></i>
                        <div class="ml-3">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Apprenants</p>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white animate-number">{{
                                totalApprenants }}</h2>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                    <div class="flex items-center">
                        <i :class="['fas fa-book-open text-2xl', iconColors.base, 'animate-pulse']"></i>
                        <div class="ml-3">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Formations</p>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white animate-number">{{
                                totalFormations }}</h2>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                    <div class="flex items-center">
                        <i :class="['fas fa-star text-2xl', iconColors.base, 'animate-pulse']"></i>
                        <div class="ml-3">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Top Formation</p>
                            <h2
                                class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white truncate animate-text">
                                {{
                                    apprenantsParFormation }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <div class="relative flex-1 animate-fade-up">
                    <i
                        :class="['fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base, 'animate-pulse']"></i>
                    <input v-model="searchQuery" type="text" placeholder="Rechercher..."
                        class="w-full pl-12 pr-4 py-2 sm:py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm sm:text-base" />
                </div>
                <div class="relative animate-fade-up">
                    <i
                        :class="['fas fa-graduation-cap absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base, 'animate-pulse']"></i>
                    <select v-model="selectedFormation" @change="changeFormation(selectedFormation)"
                        class="w-full sm:w-64 pl-12 pr-10 py-2 sm:py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm sm:text-base">
                        <option :value="null">Toutes les formations</option>
                        <option v-for="formation in props.formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                    <i
                        :class="['fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2', iconColors.base, 'animate-bounce pointer-events-none']"></i>
                </div>
            </div>
            <p class="mb-4 text-gray-600 dark:text-gray-400 text-sm sm:text-base">{{ filteredApprenants.length }}
                Apprenants trouvés</p>

            <!-- Desktop : Tableau -->
            <div
                class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-x-auto transform transition-all duration-500 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)] animate-fade-in">
                <table class="min-w-full">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-gray-200 dark:from-gray-700 to-gray-300 dark:to-gray-800 text-gray-700 dark:text-gray-200 animate-slide-in">
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    :class="['fas fa-user mr-2', iconColors.base, 'animate-pulse']"></i>Nom</th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    :class="['fas fa-user-circle mr-2', iconColors.base, 'animate-pulse']"></i>Prénom
                            </th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    :class="['fas fa-phone mr-2', iconColors.phone, 'animate-pulse']"></i>Téléphone</th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    :class="['fas fa-graduation-cap mr-2', iconColors.formation, 'animate-pulse']"></i>Formation
                            </th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    :class="['fas fa-envelope mr-2', iconColors.email, 'animate-pulse']"></i>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(apprenant, index) in filteredApprenants" :key="apprenant.id"
                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 animate-row-in"
                            :style="{ animationDelay: `${0.7 + index * 0.1}s` }">
                            <td
                                class="py-4 px-6 text-gray-900 dark:text-gray-100 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
                                {{
                                    apprenant.nom }}</td>
                            <td
                                class="py-4 px-6 text-gray-900 dark:text-gray-100 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
                                {{
                                    apprenant.prenom }}</td>
                            <td
                                class="py-4 px-6 text-gray-900 dark:text-gray-100 hover:text-green-700 dark:hover:text-green-300 transition-colors duration-200">
                                {{
                                    apprenant.telephone }}</td>
                            <td
                                class="py-4 px-6 text-gray-900 dark:text-gray-100 hover:text-purple-700 dark:hover:text-purple-300 transition-colors duration-200">
                                <span
                                    class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 px-2 py-1 rounded-full text-sm cursor-pointer hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors duration-200"
                                    @click="changeFormation(apprenant.formation_id)">
                                    {{ getFormationTitre(apprenant.formation_id) }}
                                </span>
                            </td>
                            <td
                                class="py-4 px-6 text-gray-900 dark:text-gray-100 hover:text-yellow-700 dark:hover:text-yellow-300 transition-colors duration-200">
                                {{
                                    apprenant.user.email }}</td>
                        </tr>
                        <tr v-if="filteredApprenants.length === 0">
                            <td colspan="5" class="p-6 text-center text-gray-500 dark:text-gray-400 animate-pulse">
                                <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i> Aucun apprenant
                                trouvé
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile : Cartes -->
            <div class="sm:hidden grid grid-cols-1 gap-4 bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-4 animate-fade-in"
                :style="{ animationDelay: '0.4s' }">
                <div v-for="(apprenant, index) in filteredApprenants" :key="apprenant.id"
                    class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md border border-gray-200 dark:border-gray-600 p-4 hover:shadow-xl transition-all duration-300 animate-card-in"
                    :style="{ animationDelay: `${0.7 + index * 0.1}s` }">
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate"
                                :title="apprenant.nom + ' ' + apprenant.prenom">
                                <i :class="['fas fa-user mr-2', iconColors.base]"></i>
                                {{ apprenant.nom }} {{ apprenant.prenom }}
                            </h2>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300" :title="apprenant.telephone">
                            <i :class="['fas fa-phone mr-2', iconColors.phone, 'hover:' + iconColors.phoneHover]"></i>{{
                                apprenant.telephone }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <i
                                :class="['fas fa-graduation-cap mr-2', iconColors.formation, 'hover:' + iconColors.formationHover]"></i>
                            <span
                                class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 px-2 py-1 rounded-full text-xs cursor-pointer hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors duration-200"
                                @click="changeFormation(apprenant.formation_id)">
                                {{ getFormationTitre(apprenant.formation_id) }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 truncate" :title="apprenant.user.email">
                            <i
                                :class="['fas fa-envelope mr-2', iconColors.email, 'hover:' + iconColors.emailHover]"></i>{{
                                    apprenant.user.email }}
                        </p>
                    </div>
                </div>
                <div v-if="filteredApprenants.length === 0"
                    class="col-span-full p-4 text-center text-gray-500 dark:text-gray-400 animate-pulse bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md border border-gray-200 dark:border-gray-600">
                    <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i> Aucun apprenant trouvé
                </div>
            </div>
        </div>
    </FormateurLayout>
</template>

<style scoped>
select {
    background-image: none;
}

/* Animations personnalisées */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes rowIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes cardIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }

    50% {
        opacity: 1;
        transform: scale(1.05);
    }

    70% {
        transform: scale(0.9);
    }

    100% {
        transform: scale(1);
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

@keyframes number {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes text {
    from {
        opacity: 0;
        transform: scale(0.9);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

.animate-fade-up {
    animation: fadeUp 0.6s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

.animate-card-in {
    animation: cardIn 0.5s ease-out forwards;
}

.animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
}

.animate-spin-slow {
    animation: spinSlow 4s linear infinite;
}

.animate-number {
    animation: number 0.5s ease-out;
}

.animate-text {
    animation: text 0.5s ease-out;
}

/* Styles optimisés */
.min-h-screen {
    min-height: 100vh;
}

/* Réactivité améliorée */
@media (max-width: 640px) {
    .p-4 {
        padding: 1rem;
    }

    .text-2xl {
        font-size: 1.5rem;
    }

    .text-lg {
        font-size: 1.125rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .gap-4 {
        gap: 1rem;
    }

    .shadow-md {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .text-3xl {
        font-size: 1.875rem;
    }
}
</style>