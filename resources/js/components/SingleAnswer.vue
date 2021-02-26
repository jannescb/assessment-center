<template>
    <div>
        <input type="text" v-model="model" class="border border-gray-700 " />

        {{ errors }}
    </div>
</template>

<script>
export default {
    name: 'SingleAnswer',
    props: {
        question: {
            type: Object,
            required: true,
        },
        errors: {
            type: Array,
        },
    },
    data() {
        return {
            model: null,
        };
    },
    watch: {
        model(val) {
            this.$emit('input', {
                id: this.question.id,
                answer: val,
            });
        },
    },
};
</script>
