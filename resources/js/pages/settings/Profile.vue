<script setup lang="ts">
import { TransitionRoot } from '@headlessui/vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MonLayout from '@/components/MonLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

// Vérifier si l'utilisateur est admin
const isAdmin = user.role === 'admin';

// Initialiser le formulaire
const form = useForm({
    name: user.name,
    email: user.email,
});

// Soumettre uniquement les champs modifiables
const submit = () => {
    // Si l'utilisateur n'est pas admin, ne pas envoyer le champ "name"
    if (!isAdmin) {
        form.patch(route('profile.update'), {
            preserveScroll: true,
            data: {
                email: form.email, // Seule l'email est envoyée
            },
        });
    } else {
        form.patch(route('profile.update'), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <MonLayout>

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <!-- Champ "name" en lecture seule pour les non-admins -->
                        <Input v-if="isAdmin" id="name" class="mt-1 block w-full" v-model="form.name" required
                            autocomplete="name" placeholder="Full name" />
                        <div v-else
                            class="mt-1 block w-full px-3 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-md">
                            <div v-if="user.role!='admin'">

                                {{ user.apprenant?.nom }} {{ user.apprenant?.prenom }}
                                {{ user.formateur?.nom }} {{ user.formateur?.prenom }}
                            </div>
                            <div v-else>
                                {{ user.email }} 
                            </div>
                            <!-- {{ user.admin?.nom }} {{ user.admin?.prenom }} -->
                        </div>
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autocomplete="username" placeholder="Email address" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link :href="route('verification.send')" method="post" as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:!decoration-current dark:decoration-neutral-500">
                            Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <TransitionRoot :show="form.recentlySuccessful" enter="transition ease-in-out"
                            enter-from="opacity-0" leave="transition ease-in-out" leave-to="opacity-0">
                            <p class="text-sm text-neutral-600">Saved.</p>
                        </TransitionRoot>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </MonLayout>
</template>