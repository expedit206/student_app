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
const selectedFormation = ref<number | null>(props.selectedFormation); // État initial depuis l'URL

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

// Changer la formation localement
const changeFormation = (formationId: number | null) => {
    selectedFormation.value = formationId; // Mise à jour locale uniquement
};
</script>

<template>
    <FormateurLayout>
        <div class="p-6 bg-gray-900 text-gray-100 min-h-screen overflow-hidden">
            <h1 class="text-3xl font-extrabold mb-8 flex items-center animate-slide-in">
                <i class="fas fa-users mr-3 text-blue-400 text-4xl animate-spin-slow"></i>
                Gestion des Apprenants
            </h1>
            <TextLink :href="route('formateur.notes')" class="text-blue-400 hover:text-blue-300 mb-4 inline-block">
                <i class="fas fa-star mr-2"></i> Gérer les notes
            </TextLink>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div
                    class="bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                    <div class="flex items-center">
                        <i class="fas fa-user-graduate text-2xl text-blue-400 mr-3 animate-pulse"></i>
                        <div>
                            <p class="text-xs text-gray-400">Apprenants</p>
                            <h2 class="text-lg font-bold text-white animate-number">{{ totalApprenants }}</h2>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                    <div class="flex items-center">
                        <i class="fas fa-book-open text-2xl text-purple-400 mr-3 animate-pulse"></i>
                        <div>
                            <p class="text-xs text-gray-400">Formations</p>
                            <h2 class="text-lg font-bold text-white animate-number">{{ totalFormations }}</h2>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                    <div class="flex items-center">
                        <i class="fas fa-star text-2xl text-yellow-400 mr-3 animate-pulse"></i>
                        <div>
                            <p class="text-xs text-gray-400">Top Formation</p>
                            <h2 class="text-lg font-bold text-white truncate animate-text">{{ apprenantsParFormation }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <div class="relative flex-1 animate-fade-up">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 animate-pulse"></i>
                    <input v-model="searchQuery" type="text" placeholder="Rechercher par nom, prénom ou email"
                        class="w-full pl-12 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-700" />
                </div>
                <div class="relative animate-fade-up">
                    <i
                        class="fas fa-graduation-cap absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 animate-pulse"></i>
                    <select v-model="selectedFormation" @change="changeFormation(selectedFormation)"
                        class="pl-12 pr-10 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-700">
                        <option :value="null">Toutes les formations</option>
                        <option v-for="formation in props.formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 animate-bounce pointer-events-none"></i>
                </div>
            </div>
            <p class="mb-4 text-gray-400">{{ filteredApprenants.length }} Apprenants trouvés</p>

            <div
                class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-700 to-gray-800 text-gray-200 animate-slide-in">
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    class="fas fa-user mr-2 text-blue-400 animate-pulse"></i>Nom</th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    class="fas fa-user-circle mr-2 text-blue-400 animate-pulse"></i>Prénom</th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    class="fas fa-phone mr-2 text-green-400 animate-pulse"></i>Téléphone</th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    class="fas fa-graduation-cap mr-2 text-purple-400 animate-pulse"></i>Formation</th>
                            <th class="py-4 px-6 text-left font-semibold"><i
                                    class="fas fa-envelope mr-2 text-yellow-400 animate-pulse"></i>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(apprenant, index) in filteredApprenants" :key="apprenant.id"
                            class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300 animate-row-in"
                            :style="{ animationDelay: `${0.7 + index * 0.1}s` }">
                            <td class="py-4 px-6 text-gray-100 hover:text-blue-300 transition-colors duration-200">{{
                                apprenant.nom }}</td>
                            <td class="py-4 px-6 text-gray-100 hover:text-blue-300 transition-colors duration-200">{{
                                apprenant.prenom }}</td>
                            <td class="py-4 px-6 text-gray-100 hover:text-green-300 transition-colors duration-200">{{
                                apprenant.telephone }}</td>
                            <td class="py-4 px-6 text-gray-100 hover:text-purple-300 transition-colors duration-200">
                                <span
                                    class="bg-purple-900 text-purple-300 px-2 py-1 rounded-full text-sm cursor-pointer hover:bg-purple-800 transition-colors duration-200"
                                    @click="changeFormation(apprenant.formation_id)">
                                    {{ getFormationTitre(apprenant.formation_id) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-100 hover:text-yellow-300 transition-colors duration-200">{{
                                apprenant.user.email }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="filteredApprenants.length === 0" class="p-6 text-center text-gray-400 animate-pulse">
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

.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

.animate-fade-up {
    animation: fadeUp 0.6s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
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
</style>