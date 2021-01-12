<template>
    <div>
        {{ question }}
        <slot />
    </div>
</template>

<script>
export default {
    name: 'Question',
    props: {
        question: {
            type: String,
            required: true
        }
    }
};
</script>
