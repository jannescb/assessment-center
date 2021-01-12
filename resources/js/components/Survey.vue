<template>
    <div>
        {{ survey.title }}

        <component
            :is="getType(question)"
            :question="question"
            v-for="(question, index) in survey.questions"
            :key="index"
            @input="handleInput"
        />

        <pre>{{ formData }}</pre>

        <button @click="submit()">
            Submit
        </button>
    </div>
</template>

<script>
import MultipleAnswers from './MultipleAnswers';
import SingleAnswer from './SingleAnswer';
export default {
    name: 'Survey',
    props: {
        survey: {
            type: Object,
            required: true,
        },
    },
    components: {
        MultipleAnswers,
        SingleAnswer,
    },
    data() {
        return {
            formData: this.init(),
        };
    },
    methods: {
        init() {
            let data = {
                id: this.survey.id,
                questions: {},
            };

            for (const key in this.survey.questions) {
                if (Object.hasOwnProperty.call(this.survey.questions, key)) {
                    const element = this.survey.questions[key];
                    data.questions[`id-${element.id}`] = null;
                }
            }

            return data;
        },
        getType(question) {
            return (
                {
                    checkbox: 'MultipleAnswers',
                    input: 'SingleAnswer',
                    radio: 'MultipleAnswers',
                }[question.question_type] || 'SingleAnswer'
            );
        },
        getComponent(question) {
            return (
                {
                    checkbox: 'MultipleAnswers',
                    radio: 'MultipleAnswers',
                }[question.question_type] || 'InputQuestion'
            );
        },
        handleInput(val) {
            Vue.set(this.formData.questions, `id-${val.id}`, val.answer);
        },
        async submit() {
            try {
                await axios.post(
                    `/api/survey/${this.survey.id}`,
                    this.formData
                );
            } catch (error) {}
        },
    },
};
</script>
