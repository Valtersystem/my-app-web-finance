<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { TableOfContents } from 'lucide-vue-next';

</script>

<template>
    <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
        <AvatarFallback class="rounded-lg text-black dark:text-white">
            <TableOfContents class="size-4" />
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">More options</span>
    </div>
</template>
