<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import CategoryModal from './Partials/CategoryModal.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Plus, Trash2, FileEdit } from 'lucide-vue-next';

const props = defineProps<{
  expenseCategories: Array<any>;
  incomeCategories: Array<any>;
}>();

const activeTab = ref<'expense' | 'income'>('expense');
const isModalOpen = ref(false);
const editingCategory = ref(null);

const openNewModal = () => {
    editingCategory.value = null;
    isModalOpen.value = true;
};

const openEditModal = (category: any) => {
    editingCategory.value = category;
    activeTab.value = category.type;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};
</script>

<template>
    <Head title="Categories" />

    <AppLayout>
        <div class="p-4 md:p-6">
            <div class="flex items-center justify-between mb-4">
                 <div class="flex items-center gap-2">
                    <Button @click="activeTab = 'expense'" :variant="activeTab === 'expense' ? 'destructive' : 'ghost'">Expenses Categories</Button>
                    <Button @click="activeTab = 'income'" :variant="activeTab === 'income' ? 'secondary' : 'ghost'">Incomes Categories</Button>
                 </div>
                 <Button @click="openNewModal" size="sm"><Plus class="h-4 w-4 mr-2" /> New Category</Button>
            </div>
           
            <Card>
                <CardContent class="p-0">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-700/20">
                            <tr>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Icon</th>
                                <th class="px-6 py-3">Color</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in (activeTab === 'expense' ? expenseCategories : incomeCategories)" :key="category.id" class="border-b border-gray-700">
                                <td class="px-6 py-4 font-medium text-white">{{ category.name }}</td>
                                <td class="px-6 py-4"><component :is="category.icon" class="h-5 w-5" :style="{ color: category.color }" /></td>
                                <td class="px-6 py-4"><div class="w-4 h-4 rounded-full" :style="{ backgroundColor: category.color }"></div></td>
                                <td class="px-6 py-4 flex justify-end gap-2">
                                    <Button @click="openEditModal(category)" variant="ghost" size="icon"><FileEdit class="h-4 w-4" /></Button>
                                    <Link :href="route('categories.destroy', category.id)" method="delete" as="button" class="p-2 hover:bg-red-500/20 rounded-md" :onBefore="() => confirm('Are you sure you want to delete this category?')">
                                        <Trash2 class="h-4 w-4 text-red-500" />
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardContent>
            </Card>
        </div>
        
        <CategoryModal :show="isModalOpen" :category="editingCategory" :type="activeTab" @close="closeModal" />
    </AppLayout>
</template>