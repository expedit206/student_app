<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import MonLayout from '@/components/MonLayout.vue';
import { ref } from 'vue';

// Définir les props passées au composant
const props = defineProps<{
    apprenant?: {
        id?: number;
        nom?: string;
        prenom?: string;
        date_naissance?: string;
        adresse?: string;
        telephone?: string;
        niveau_id?: string | number;
        formation_id?: string | number;
        email?: string; // Ajouter l'email pour l'édition
    };
    formations: { id: number; titre: string }[];
    niveaux: { id: number; titre: string }[];
}>();

// Initialiser le formulaire avec useForm
const form = useForm({
    nom: props.apprenant?.nom || '',
    prenom: props.apprenant?.prenom || '',
    date_naissance: props.apprenant?.date_naissance || '',
    adresse: props.apprenant?.adresse || '',
    telephone: props.apprenant?.telephone || '',
    niveau_id: props.apprenant?.niveau_id || '',
    formation_id: props.apprenant?.formation_id || '',
    email: props.apprenant?.email || '', // Champ email
    password: '', // Champ mot de passe, vide par défaut
});

// Références pour les listes déroulantes
const niveaux = ref(props.niveaux);
const formations = ref(props.formations);

// Fonction de soumission du formulaire
function submitForm() {
    const routeName = props.apprenant ? 'apprenants.update' : 'apprenants.store';
    const method = props.apprenant ? 'put' : 'post';

    form[method](route(routeName, props.apprenant?.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset(); // Réinitialiser le formulaire après succès
        },
        onError: (errors) => {
            console.log('Validation errors:', errors); // Débogage pour vérifier les erreurs
        },
    });
}
</script>

<template>

    <Head :title="props.apprenant ? 'Modifier l\'Étudiant' : 'Ajouter un Étudiant'" />

    <MonLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold text-center mb-6">
                {{ props.apprenant ? 'Modifier l\'Étudiant' : 'Ajouter un Étudiant' }}
            </h1>

            <form @submit.prevent="submitForm"
                class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Champ Nom -->
                    <div class="relative">
                        <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="nom" v-model="form.nom" placeholder="Entrez le nom"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                required />
                        </div>
                        <InputError :message="form.errors.nom" />
                    </div>

                    <!-- Champ Prénom -->
                    <div class="relative">
                        <label for="prenom"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">Prénom</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-user-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="prenom" v-model="form.prenom" placeholder="Entrez le prénom"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                required />
                        </div>
                        <InputError :message="form.errors.prenom" />
                    </div>

                    <!-- Champ Date de Naissance -->
                    <div class="relative">
                        <label for="date_naissance"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date de Naissance</label>
                        <div class="mt-1 relative">
                            <input type="date" id="date_naissance" v-model="form.date_naissance"
                                class="pl-4 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                required />
                        </div>
                        <InputError :message="form.errors.date_naissance" />
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="relative">
                        <label for="telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Numéro
                            de Téléphone</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="telephone" v-model="form.telephone" placeholder="Entrez le numéro"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                required />
                        </div>
                        <InputError :message="form.errors.telephone" />
                    </div>

                    <!-- Champ Adresse -->
                    <div class="relative col-span-1 sm:col-span-2">
                        <label for="adresse"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">Adresse</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="text" id="adresse" v-model="form.adresse" placeholder="Entrez l'adresse"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                required />
                        </div>
                        <InputError :message="form.errors.adresse" />
                    </div>

                    <!-- Champ Niveau -->
                    <div class="relative">
                        <label for="niveau_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">Niveau</label>
                        <div class="mt-1 relative">
                            <select id="niveau_id" v-model="form.niveau_id"
                                class="pl-4 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
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
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">Formation</label>
                        <div class="mt-1 relative">
                            <select id="formation_id" v-model="form.formation_id"
                                class="pl-4 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                required>
                                <option value="">Sélectionnez la formation</option>
                                <option v-for="formation in formations" :key="formation.id" :value="formation.id">
                                    {{ formation.titre }}
                                </option>
                            </select>
                        </div>
                        <InputError :message="form.errors.formation_id" />
                    </div>

                    <!-- Champ Email -->
                    <div class="relative col-span-1 sm:col-span-2">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                        <div class="mt-1 relative">
                            <i
                                class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                            <input type="email" id="email" v-model="form.email" placeholder="Entrez l'email"
                                class="pl-10 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                required />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Champ Mot de Passe -->
                    <div class="relative col-span-1 sm:col-span-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Mot de
                            Passe</label>
                        <div class="mt-1 relative">
                            <input type="password" id="password" v-model="form.password"
                                placeholder="Entrez le mot de passe"
                                class="pl-4 pr-4 py-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100"
                                :required="!props.apprenant" />
                            <!-- Le mot de passe est requis uniquement lors de la création -->
                        </div>
                        <InputError :message="form.errors.password" />
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit"
                    class="mt-6 w-full bg-blue-500 text-white rounded-lg p-3 hover:bg-blue-600 transition duration-200 flex items-center justify-center"
                    :disabled="form.processing">
                    <span v-if="!form.processing">{{ props.apprenant ? 'Modifier' : 'Ajouter' }} l'Étudiant</span>
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
    @apply text-red-500 dark:text-red-400 text-sm mt-1;
}
</style>