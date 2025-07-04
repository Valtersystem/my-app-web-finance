<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// Nossos componentes
import AppLayout from '@/layouts/AppLayout.vue';
import CreateAccountModal from './Partials/CreateAccountModal.vue';

// Componentes da UI
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Plus } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

// Props recebidas do AccountController
defineProps<{
  accounts: Array<{
    id: number;
    name: string;
    color: string;
    description: string | null;
    initial_balance: string;
  }>;
  accountTypes: Array<any>; // Prop para os tipos de conta
  institutions: Array<any>; // Prop para as instituições
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: route('accounts.index'),
    },
];

// Controlo do Modal (nome da variável corrigido para consistência)
const isNewAccountModalOpen = ref(false);
const openModal = () => isNewAccountModalOpen.value = true;
const closeModal = () => isNewAccountModalOpen.value = false;
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

        <CreateAccountModal
            :show="isNewAccountModalOpen"
            :account-types="accountTypes"
            :institutions="institutions"
            @close="closeModal"
        />

    </AppLayout>
</template>
