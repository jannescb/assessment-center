<template>
    <div>
        <input
            type="text"
            v-model="model"
            class="w-full p-2 border border-gray-300 rounded-lg"
        />

        <errors :errors="errors" />
    </div>
</template>

<script>
import Errors from './Errors';
export default {
    name: 'SingleAnswer',
    components: {
        Errors,
    },
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
