<template>
    <div>
        {{ question.translation.en.question }}
        <slot />
    </div>
</template>

<script>
export default {
    name: 'Question',
    props: {
        question: {
            type: Object,
            required: true
        }
    }
};
</script>
