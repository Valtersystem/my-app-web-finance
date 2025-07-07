<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import InstitutionModal from './Partials/InstitutionModal.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Plus, Trash2, FileEdit } from 'lucide-vue-next';

defineProps<{
  institutions: Array<{ id: number; name: string; logo_path: string | null; }>;
}>();

const isModalOpen = ref(false);
const editingInstitution = ref(null);

const openNewModal = () => {
    editingInstitution.value = null;
    isModalOpen.value = true;
};

const openEditModal = (institution: any) => {
    editingInstitution.value = institution;
    isModalOpen.value = true;
};
</script>

<template>
    <Head title="Banking Institutions" />
    <AppLayout>
        <div class="p-4 md:p-6">
            <div class="flex items-center justify-between mb-4">
                 <h1 class="text-xl font-semibold">Institutions</h1>
                 <Button @click="openNewModal" size="sm"><Plus class="h-4 w-4 mr-2" /> New Institution</Button>
            </div>
            <Card>
                <CardContent class="p-0">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-700/20">
                            <tr>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="institution in institutions" :key="institution.id" class="border-b border-gray-700">
                                <td class="px-6 py-4 font-medium text-white">{{ institution.name }}</td>
                                <td class="px-6 py-4 flex justify-end gap-2">
                                    <Button @click="openEditModal(institution)" variant="ghost" size="icon"><FileEdit class="h-4 w-4" /></Button>
                                    <Link :href="route('institutions.destroy', institution.id)" method="delete" as="button" class="p-2 hover:bg-red-500/20 rounded-md" :onBefore="() => confirm('Are you sure?')">
                                        <Trash2 class="h-4 w-4 text-red-500" />
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="institutions.length === 0">
                                <td colspan="2" class="px-6 py-10 text-center text-gray-400">No institutions found.</td>
                            </tr>
                        </tbody>
                    </table>
                </CardContent>
            </Card>
        </div>
        <InstitutionModal :show="isModalOpen" :institution="editingInstitution" @close="isModalOpen = false" />
    </AppLayout>
</template>
