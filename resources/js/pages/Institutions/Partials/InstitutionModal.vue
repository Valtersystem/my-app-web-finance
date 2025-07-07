<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch, ref } from 'vue';
import Modal from '@/components/Modal.vue';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
  show: boolean;
  institution?: { id: number; name: string; logo_path: string | null; };
}>();

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    logo_path: '',
});

const isEditMode = ref(false);

watch(() => props.show, (show) => {
    if (show) {
        form.reset();
        isEditMode.value = !!props.institution;
        if (isEditMode.value && props.institution) {
            form.name = props.institution.name;
            form.logo_path = props.institution.logo_path ?? '';
        }
    }
});

const submit = () => {
    if (isEditMode.value && props.institution) {
        form.put(route('institutions.update', props.institution.id), {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('institutions.store'), {
            onSuccess: () => emit('close'),
        });
    }
};
</script>

<template>
    <Modal :show="props.show" @close="emit('close')" class-more="bg-gray-800 rounded-lg text-white">
        <div class="p-6">
            <h2 class="text-lg font-medium">{{ isEditMode ? 'Edit Institution' : 'New Institution' }}</h2>
            <form @submit.prevent="submit" class="mt-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                    <input id="name" type="text" v-model="form.name" class="w-full bg-gray-700 p-2 rounded mt-1" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div>
                    <label for="logo_path" class="block text-sm font-medium text-gray-300">Logo URL (Optional)</label>
                    <input id="logo_path" type="text" v-model="form.logo_path" class="w-full bg-gray-700 p-2 rounded mt-1" />
                    <InputError class="mt-2" :message="form.errors.logo_path" />
                </div>
                <div class="flex justify-end gap-4 pt-4">
                    <Button type="button" variant="secondary" @click="emit('close')">Cancel</Button>
                    <Button type="submit" :disabled="form.processing">Save</Button>
                </div>
            </form>
        </div>
    </Modal>
</template>
