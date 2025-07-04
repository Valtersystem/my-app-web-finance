<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch, ref } from 'vue';
import Modal from '@/components/Modal.vue';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { Check } from 'lucide-vue-next';

const props = defineProps<{
  show: boolean;
  category?: { id: number; name: string; type: string; icon: string; color: string; };
  type: 'income' | 'expense';
}>();

const emit = defineEmits(['close']);

const colors = ['#ef4444', '#f97316', '#eab308', '#84cc16', '#22c55e', '#14b8a6', '#06b6d4', '#3b82f6', '#8b5cf6', '#d946ef'];
const icons = ['Home', 'Car', 'Utensils', 'ShoppingBag', 'Heart', 'Gift', 'Book', 'Film', 'Briefcase', 'Landmark'];

const form = useForm({
    name: '',
    type: props.type,
    icon: icons[0],
    color: colors[0],
});

const isEditMode = ref(false);

watch(() => props.show, (show) => {
    if (show) {
        form.reset();
        form.type = props.type;
        isEditMode.value = !!props.category;
        if (isEditMode.value && props.category) {
            form.name = props.category.name;
            form.icon = props.category.icon;
            form.color = props.category.color;
            form.type = props.category.type as 'income' | 'expense';
        }
    }
});

const submit = () => {
    if (isEditMode.value && props.category) {
        form.put(route('categories.update', props.category.id), {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('categories.store'), {
            onSuccess: () => emit('close'),
        });
    }
};
</script>

<template>
    <Modal :show="props.show" @close="emit('close')" class-more="bg-[#2C2C2E] rounded-xl text-white">
        <div class="p-6">
            <h2 class="text-lg font-medium text-white">{{ isEditMode ? 'Edit Category' : 'Register new category' }}</h2>
            <form @submit.prevent="submit" class="mt-6 space-y-4">
                <div>
                    <input type="text" v-model="form.name" class="w-full bg-[#3A3A3C] p-2 rounded outline-none" placeholder="Name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div>
                    <p class="text-sm text-gray-400 mb-2">Category color</p>
                    <div class="flex gap-2">
                         <label v-for="c in colors" :key="c" class="cursor-pointer">
                            <input type="radio" :value="c" v-model="form.color" class="sr-only" />
                            <span class="w-8 h-8 rounded-full flex items-center justify-center" :style="{backgroundColor: c}">
                               <Check v-if="form.color === c" class="h-5 w-5 text-white"/>
                            </span>
                         </label>
                    </div>
                </div>
                 <div>
                    <p class="text-sm text-gray-400 mb-2">Icon</p>
                    <div class="flex gap-2 flex-wrap">
                        <label v-for="i in icons" :key="i" class="cursor-pointer">
                           <input type="radio" :value="i" v-model="form.icon" class="sr-only" />
                           <span class="w-10 h-10 rounded-full flex items-center justify-center" :class="form.icon === i ? 'bg-purple-600' : 'bg-[#3A3A3C]'">
                              <component :is="i" class="h-5 w-5 text-white"/>
                           </span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-4 pt-4">
                    <Button type="button" variant="secondary" @click="emit('close')">Cancel</Button>
                    <Button type="submit" :disabled="form.processing">Save</Button>
                </div>
            </form>
        </div>
    </Modal>
</template>
