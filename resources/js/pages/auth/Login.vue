<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Connectez vouz a votre compte !!"
        description="Connectez-vous pour booster vos compétences avec le Centre de Formation Professionnel La Canadienne">

        <Head title="Connexion" />

        <!-- Message de statut -->
        <div v-if="status"
            class="mb-6 text-center text-sm font-medium text-green-400 dark:text-green-300 animate-fade-in-up">
            {{ status }}
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit"
            class="flex flex-col gap-6 w-full bg-gray-100 dark:bg-gray-800 p-6 sm:p-8 rounded-xl shadow-xl animate-slide-in-up">
            <!-- Email -->
            <div class="grid gap-3">
                <Label for="email" class="text-gray-900 dark:text-gray-200 text-sm sm:text-base font-semibold">Votre
                    email</Label>
                <Input id="email" type="email" required autofocus :tabindex="1" autocomplete="email"
                    v-model="form.email" placeholder="email@exemple.com"
                    class="bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4 rounded-lg transition-all duration-300" />
                <InputError :message="form.errors.email" class="text-red-400 dark:text-red-300 text-sm" />
            </div>

            <!-- Mot de passe -->
            <div class="grid gap-3">
                <div class="flex items-center justify-between">
                    <Label for="password"
                        class="text-gray-900 dark:text-gray-200 text-sm sm:text-base font-semibold">Votre mot de
                        passe</Label>
                    <TextLink v-if="canResetPassword" :href="route('password.request')"
                        class="text-sm text-indigo-400 dark:text-indigo-300 hover:text-indigo-500 dark:hover:text-indigo-200 transition-colors duration-200"
                        :tabindex="5">
                        Oublié ?
                    </TextLink>
                </div>
                <Input id="password" type="password" required :tabindex="2" autocomplete="current-password"
                    v-model="form.password" placeholder="••••••••"
                    class="bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4 rounded-lg transition-all duration-300" />
                <InputError :message="form.errors.password" class="text-red-400 dark:text-red-300 text-sm" />
            </div>

            <!-- Se souvenir de moi -->
            <div class="flex items-center">
                <Label for="remember" class="flex items-center space-x-2 text-gray-900 dark:text-gray-200">
                    <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4"
                        class="text-indigo-500 rounded border-gray-300 dark:border-gray-700" />
                    <span class="text-sm">Rester connecté</span>
                </Label>
            </div>

            <!-- Bouton Connexion -->
            <Button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white py-3 rounded-lg font-semibold text-base sm:text-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2 animate-fade-in-up"
                :disabled="form.processing" :tabindex="4">
                <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                <span>Connexion</span>
            </Button>
        </form>
    </AuthBase>
</template>

<style scoped>
/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

.animate-slide-in-up {
    animation: slideInUp 0.8s ease-out forwards;
}

/* Réactivité */
@media (max-width: 640px) {
    .text-xl {
        font-size: 1.25rem;
    }

    /* Titre dans AuthBase */
    .text-base {
        font-size: 1rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .gap-6 {
        gap: 1.5rem;
    }

    .gap-3 {
        gap: 0.75rem;
    }

    .p-6 {
        padding: 1.5rem;
    }

    .rounded-xl {
        border-radius: 0.75rem;
    }
}
</style>