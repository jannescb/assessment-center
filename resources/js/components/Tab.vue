<template>
    <div v-show="isActive">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'Tab',
    props: {
        title: {
            type: String | Number,
            default: 'Tab',
        },
        hasError: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            isActive: false,
        };
    },
};
</script>
