<template>
    <question :question="getQuestion()">
        <pre>{{ question.id }}</pre>
        <fieldset>
            <div v-for="(answer, index) in question.answers" :key="index">
                <input
                    :type="type"
                    :id="getId(answer)"
                    :value="answer.translation.en.answer"
                    v-model="model"
                />
                <label :for="getId(answer)">
                    {{ getAnswer(answer) }}
                </label>
            </div>
            {{ model }}
        </fieldset>
    </question>
</template>

<script>
import Question from './Question';
export default {
    name: 'MultipleAnswers',
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
            model: [],
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
        slugify(text) {
            if (!text) return '';
            return text
                .toString()
                .toLowerCase()
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/[^\w\-]+/g, '') // Remove all non-word chars
                .replace(/\-\-+/g, '-') // Replace multiple - with single -
                .replace(/^-+/, '') // Trim - from start of text
                .replace(/-+$/, ''); // Trim - from end of text
        },
        getTranslation(obj, key) {
            let locale = 'en';
            return obj.translation[locale][key];
        },
        getQuestion() {
            return this.getTranslation(this.question, 'question');
        },
        getAnswer(answer) {
            return this.getTranslation(answer, 'answer');
        },
        getId(answer) {
            return this.slugify(this.getAnswer(answer));
        },
    },
    computed: {
        type() {
            return this.question.question_type;
        },
    },
};
</script>
