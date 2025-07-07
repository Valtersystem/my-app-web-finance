<script setup lang="ts">
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Link } from '@inertiajs/vue3';
import { Landmark } from 'lucide-vue-next';

</script>

<template>
    <DropdownMenuItem :as-child="true">
        <Link class="block w-full" method="post" :href="route('institutions.index')">
        <Landmark class="h-5 w-5" />
        <span>Institutions</span>
        </Link>
    </DropdownMenuItem>
</template>
