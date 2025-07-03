<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Nossos componentes
import AppLayout from '@/layouts/AppLayout.vue';
import Modal from '@/components/Modal.vue';
import InputError from '@/components/InputError.vue';

// Componentes da UI
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch'; // Importar o novo Switch
import { Plus } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

// Props
defineProps<{
  accounts: Array<{
    id: number;
    name: string;
    color: string;
    description: string | null;
    initial_balance: string;
  }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: route('accounts.index'),
    },
];

// Controlo do Modal
const isNewAccountModalOpen = ref(false);
const openModal = () => isNewAccountModalOpen.value = true;
const closeModal = () => {
    isNewAccountModalOpen.value = false;
    form.reset();
};

// Definições do Formulário
const accountTypes = {
    checking: 'Checking account',
    savings: 'Savings account',
    credit_card: 'Credit Card',
    cash: 'Cash',
};

const accountColors = ['#3b82f6', '#8b5cf6', '#22c55e', '#f97316'];

const form = useForm({
    name: '',
    type: 'checking',
    initial_balance: 0.00,
    description: '',
    color: accountColors[0],
    include_in_dashboard: true,
});

const submit = () => {
    form.post(route('accounts.store'), {
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:gap-8 md:p-6">
            <div class="flex items-center">
                <h1 class="text-lg font-semibold md:text-2xl">Accounts</h1>
                <div class="ml-auto flex items-center gap-2">
                    <Button @click="openModal" size="sm">
                        <Plus class="h-4 w-4" />
                        <span class="sr-only md:not-sr-only md:ml-2">New Account</span>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-4">
                <div class="grid auto-rows-max items-start gap-6 md:col-span-2 lg:col-span-3">
                    <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <Card @click="openModal" class="flex h-[180px] flex-col items-center justify-center border-dashed cursor-pointer hover:border-primary/50 transition-colors">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <Plus class="h-6 w-6" />
                                </div>
                                <span>New account</span>
                            </div>
                        </Card>
                        <Card v-for="account in accounts" :key="account.id" class="h-[180px]">
                            <CardHeader>
                                <CardTitle class="text-base font-medium">{{ account.name }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div>
                                    <span class="text-sm text-muted-foreground">Current balance</span>
                                    <p class="text-2xl font-bold">
                                        € {{ parseFloat(account.initial_balance).toFixed(2) }}
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
                <div class="grid auto-rows-max items-start gap-6 lg:col-span-1">
                     <Card>
                        <CardHeader><CardTitle class="text-base font-medium">Current balance</CardTitle></CardHeader>
                        <CardContent><p class="text-2xl font-bold">€ 0.00</p></CardContent>
                    </Card>
                     <Card>
                        <CardHeader><CardTitle class="text-base font-medium">Total balance</CardTitle></CardHeader>
                        <CardContent><p class="text-2xl font-bold">€ 0.00</p></CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <Modal :show="isNewAccountModalOpen" @close="closeModal" max-width="lg" class-more="dark:bg-gray-800 rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">New Account</h2>

                <form @submit.prevent="submit" class="mt-6 space-y-6">
                    <div>
                        <Label for="initial_balance">Initial Balance</Label>
                        <Input id="initial_balance" type="number" step="0.01" v-model="form.initial_balance" class="mt-1 block w-full text-2xl h-auto p-0 border-0 focus:ring-0" />
                        <InputError :message="form.errors.initial_balance" class="mt-2" />
                    </div>

                    <div>
                        <Label for="name">Account Name</Label>
                        <Input id="name" v-model="form.name" class="mt-1 block w-full" placeholder="E.g. Carteira" />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <div>
                        <Label for="type">Account Type</Label>
                        <select id="type" v-model="form.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                           <option v-for="(label, key) in accountTypes" :key="key" :value="key">{{ label }}</option>
                        </select>
                        <InputError :message="form.errors.type" class="mt-2" />
                    </div>

                    <div>
                        <Label for="description">Description</Label>
                        <Input id="description" v-model="form.description" class="mt-1 block w-full" placeholder="Optional" />
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <div>
                        <Label>Account Color</Label>
                        <div class="mt-2 flex items-center space-x-3">
                            <label v-for="color in accountColors" :key="color" class="cursor-pointer">
                                <input type="radio" v-model="form.color" :value="color" class="sr-only" />
                                <span class="h-8 w-8 rounded-full block border-2"
                                      :style="{ backgroundColor: color, borderColor: form.color === color ? color : 'transparent' }">
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <Label for="include_in_dashboard">Include sum on dashboard</Label>
                        <Switch id="include_in_dashboard" v-model:checked="form.include_in_dashboard" />
                    </div>
                     <InputError :message="form.errors.include_in_dashboard" />

                    <div class="flex justify-end gap-4 pt-4">
                        <Button variant="secondary" @click="closeModal">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">Save</Button>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>
