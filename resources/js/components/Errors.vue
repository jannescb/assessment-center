<template>
    <div class="mt-1">
        <div v-for="(error, i) in errors" :key="i" class="text-xs text-red-500">
            {{ error }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'Errors',
    props: {
        errors: {
            type: Array,
            default: () => [],
        },
    },
};
</script>
