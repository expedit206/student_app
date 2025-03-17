<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import MonLayout from '@/components/MonLayout.vue';
import { ref } from 'vue';

// Définir les props avec des types explicites
const props = defineProps<{
    apprenant: {
        id: number;
        nom: string;
        prenom: string;
        date_naissance: string;
        adresse: string;
        telephone: string;
        niveau_id: number | string;
        formation_id: number | string;
        user: { email: string };
    };
    formations: { id: number; titre: string }[];
    niveaux: { id: number; titre: string }[];
}>();

// Initialiser le formulaire avec les données de l'apprenant
const form = useForm({
    nom: props.apprenant.nom || '',
    prenom: props.apprenant.prenom || '',
    email: props.apprenant.user.email || '',
    date_naissance: props.apprenant.date_naissance || '',
    adresse: props.apprenant.adresse || '',
    telephone: props.apprenant.telephone || '',
    niveau_id: props.apprenant.niveau_id || '',
    formation_id: props.apprenant.formation_id || '',
    password: '', // Laisser vide par défaut pour ne pas modifier si non rempli
});

// Références pour les listes déroulantes
const niveaux = ref(props.niveaux);
const formations = ref(props.formations);

// Fonction de soumission
function submitForm() {
    form.put(route('apprenants.update', props.apprenant.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password'); // Réinitialiser uniquement le mot de passe après succès
        },
        onError: (errors) => {
            console.log('Validation errors:', errors); // Débogage pour vérifier les erreurs
        },
    });
}
</script>

<template>

    <Head title="Modifier l'Apprenant" />
    <MonLayout>
        <div class="container  py-6">
            <h1 class="text-xl sm:text-2xl font-bold text-center mb-6 text-gray-900 dark:text-gray-100">
                Modifier l'Apprenant
            </h1>

            <form @submit.prevent="submitForm"
                class="w-[90%] max-w-4xl mx-auto bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-md">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Champ Nom -->
                    <div class="relative">
                        <label for="nom"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Nom</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="nom" v-model="form.nom" placeholder="Entrez le nom"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required />
                        </div>
                        <InputError :message="form.errors.nom" />
                    </div>

                    <!-- Champ Prénom -->
                    <div class="relative">
                        <label for="prenom"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Prénom</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-user-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="prenom" v-model="form.prenom" placeholder="Entrez le prénom"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required />
                        </div>
                        <InputError :message="form.errors.prenom" />
                    </div>

                    <!-- Champ Email -->
                    <div class="relative">
                        <label for="email"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="email" id="email" v-model="form.email" placeholder="Entrez l'email"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="relative">
                        <label for="password"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Nouveau Mot de passe (optionnel)
                        </label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="password" id="password" v-model="form.password"
                                placeholder="Nouveau mot de passe"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base" />
                        </div>
                        <InputError :message="form.errors.password" />
                    </div>

                    <!-- Champ Date de Naissance -->
                    <div class="relative">
                        <label for="date_naissance"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Date de
                            Naissance</label>
                        <div class="mt-1 relative">
                            <input type="date" id="date_naissance" v-model="form.date_naissance"
                                class="pl-4 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required />
                        </div>
                        <InputError :message="form.errors.date_naissance" />
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="relative">
                        <label for="telephone"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Téléphone</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="telephone" v-model="form.telephone" placeholder="Entrez le numéro"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required />
                        </div>
                        <InputError :message="form.errors.telephone" />
                    </div>

                    <!-- Champ Adresse -->
                    <div class="relative col-span-1 sm:col-span-2">
                        <label for="adresse"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Adresse</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="adresse" v-model="form.adresse" placeholder="Entrez l'adresse"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required />
                        </div>
                        <InputError :message="form.errors.adresse" />
                    </div>

                    <!-- Champ Niveau -->
                    <div class="relative">
                        <label for="niveau_id"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Niveau</label>
                        <div class="mt-1 relative">
                            <select id="niveau_id" v-model="form.niveau_id"
                                class="pl-4 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required>
                                <option value="">Sélectionnez le niveau</option>
                                <option v-for="niveau in niveaux" :key="niveau.id" :value="niveau.id">
                                    {{ niveau.titre }}
                                </option>
                            </select>
                        </div>
                        <InputError :message="form.errors.niveau_id" />
                    </div>

                    <!-- Champ Formation -->
                    <div class="relative">
                        <label for="formation_id"
                            class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">Formation</label>
                        <div class="mt-1 relative">
                            <select id="formation_id" v-model="form.formation_id"
                                class="pl-4 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                required>
                                <option value="">Sélectionnez la formation</option>
                                <option v-for="formation in formations" :key="formation.id" :value="formation.id">
                                    {{ formation.titre }}
                                </option>
                            </select>
                        </div>
                        <InputError :message="form.errors.formation_id" />
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit"
                    class="mt-6 w-full bg-blue-500 text-white rounded-lg p-3 hover:bg-blue-600 transition duration-200 flex items-center justify-center text-sm sm:text-base"
                    :disabled="form.processing">
                    <span v-if="!form.processing">Mettre à Jour</span>
                    <span v-else class="flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Envoi en cours...
                    </span>
                </button>
            </form>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Styles pour InputError si non défini dans le composant */
.input-error {
    @apply text-red-500 dark:text-red-400 text-xs sm:text-sm mt-1;
}

/* Ajustements responsifs supplémentaires */
@media (max-width: 640px) {
    .container {
        padding-left: .5rem;
        padding-right: .5rem;
    }
}
</style>