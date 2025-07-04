<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import Modal from '@/components/Modal.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
import { X, File, Landmark, Palette, Check, Building, ChevronRight } from 'lucide-vue-next';

// 1. DEFINIR AS NOVAS PROPS
const props = defineProps<{
  show: boolean;
  accountTypes: Array<{ id: number; name: string; icon: string }>;
  institutions: Array<{ id: number; name: string; logo_path: string | null }>;
}>();

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};

const accountColors = ['#3b82f6', '#8b5cf6', '#22c55e', '#f97316'];

// 2. ATUALIZAR O FORMULÁRIO PARA USAR OS IDs
const form = useForm<{
    name: string;
    account_type_id: number | null;
    institution_id: number | null;
    initial_balance: number;
    description: string;
    color: string;
    include_in_dashboard: boolean;
}>({
    name: '',
    account_type_id: null,
    institution_id: null,
    initial_balance: 0.00,
    description: '',
    color: accountColors[0],
    include_in_dashboard: true,
});

watch(() => props.show, (newVal) => {
    if (newVal && props.accountTypes.length > 0 && !form.account_type_id) {
        form.account_type_id = props.accountTypes[0].id;
    }
});


const displayValue = computed({
    get() {
        if (form.initial_balance === 0) return '0.00';
        return form.initial_balance.toFixed(2);
    },
    set(value) {
        const num = parseFloat(value.replace(/[^\d.]/g, ''));
        form.initial_balance = isNaN(num) ? 0 : num;
    }
});

const handleCurrencyInput = (e: Event) => {
    let raw = (e.target as HTMLInputElement).value;
    raw = raw.replace(/[^\d]/g, '');
    const num = raw ? (parseFloat(raw) / 100) : 0;
    form.initial_balance = num;
};

const submit = () => {
    form.post(route('accounts.store'), {
        onSuccess: () => emit('close'),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Modal :show="props.show" @close="closeModal" max-width="lg" class-more="bg-[#2C2C2E] rounded-xl text-white">
        <div class="flex flex-col">
            <div class="flex justify-between items-center p-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">New account</h2>
                <button @click="closeModal" class="text-gray-400 hover:text-white">
                    <X class="h-6 w-6" />
                </button>
            </div>
            <form @submit.prevent="submit">
                <div class="p-6 space-y-4">
                    <div class="border-b-2 border-gray-500 pb-2">
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl text-purple-400">€</span>
                            <input
                                type="text"
                                :value="displayValue"
                                @input="handleCurrencyInput"
                                placeholder="0.00"
                                class="bg-transparent outline-none w-full text-4xl placeholder-gray-500 font-bold"
                            />
                        </div>
                        <InputError :message="form.errors.initial_balance" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 border-b border-gray-700 py-3">
                         <File class="h-5 w-5 text-gray-400" />
                         <input type="text" v-model="form.name" class="w-full bg-transparent outline-none placeholder-gray-400" placeholder="Account Name" />
                    </div>
                    <InputError :message="form.errors.name" />

                    <div class="flex items-center gap-4 border-b border-gray-700 py-3">
                        <Landmark class="h-5 w-5 text-gray-400" />
                        <select v-model="form.account_type_id" class="w-full bg-transparent outline-none appearance-none">
                            <option :value="null" disabled class="text-black">Select an account type</option>
                            <option v-for="type in props.accountTypes" :key="type.id" :value="type.id" class="text-black">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>
                    <InputError :message="form.errors.account_type_id" />

                    <div class="flex items-center justify-between border-b border-gray-700 py-3 cursor-pointer">
                        <div class="flex items-center gap-4">
                            <Building class="h-5 w-5 text-gray-400" />
                            <span>Other</span>
                        </div>
                        <ChevronRight class="h-5 w-5 text-gray-400" />
                    </div>

                    <div class="flex items-center gap-4 py-3">
                        <Palette class="h-5 w-5 text-gray-400" />
                        <div class="flex items-center space-x-3">
                            <label v-for="color in accountColors" :key="color" class="cursor-pointer">
                                <input type="radio" v-model="form.color" :value="color" class="sr-only" />
                                <span class="h-8 w-8 rounded-full block flex items-center justify-center" :style="{ backgroundColor: color }">
                                    <Check v-if="form.color === color" class="h-5 w-5 text-white" />
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <label for="include_in_dashboard" class="text-sm cursor-pointer">Include sum on dashboard</label>
                        <Switch id="include_in_dashboard" :checked="form.include_in_dashboard" @update:checked="form.include_in_dashboard = $event" />
                    </div>
                    <InputError :message="form.errors.include_in_dashboard" />
                </div>
                <div class="flex justify-end gap-4 p-4 border-t border-gray-700 bg-[#3A3A3C] rounded-b-xl">
                    <Button type="button" class="bg-gray-600 hover:bg-gray-500" @click="closeModal">Cancel</Button>
                    <Button type="submit" class="bg-purple-600 hover:bg-purple-500" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save' }}
                    </Button>
                </div>
            </form>
        </div>
    </Modal>
</template>
