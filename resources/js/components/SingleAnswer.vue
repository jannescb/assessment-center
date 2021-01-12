<template>
    <question :question="question.translation.en.question">
        <input type="text" v-model="model" />
    </question>
</template>

<script>
import Question from './Question';
export default {
    name: 'SingleAnswer',
    props: {
        question: {
            type: Object,
            required: true,
        },
    },
    components: {
        Question,
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
    methods: {
        getTranslation(obj, key) {
            let locale = 'en';
            return obj.translation[locale][key];
        },
        getQuestion() {
            return this.getTranslation(this.question, 'question');
        },
    },
};
</script>
