<template>
    <div>
        <div v-if="participated">
            Danke für deine Teilnahme!
        </div>
        <div v-else>
            <h1>
                {{ survey.title }}
            </h1>
            <div v-for="(question, index) in survey.questions" :key="index">
                <h3>
                    {{ getQuestion(question) }}
                </h3>
                <component
                    :is="getInputType(question)"
                    :question="question"
                    @input="handleInput"
                    :getTranslation="getTranslation"
                    :errors="getErrors(question)"
                />
            </div>

            <button @click="submit()">
                Submit
            </button>
        </div>
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
            errors: null,
            participated: false,
        };
    },
    beforeMount() {
        if (localStorage.getItem(this.makeSurveyId())) {
            this.participated = true;
        }
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
                    data.questions[this.makeId(element.id)] = null;
                }
            }

            return data;
        },
        getInputType(question) {
            return ['checkbox', 'radio', 'select'].includes(
                question.question_type
            )
                ? 'MultipleAnswers'
                : 'SingleAnswer';
        },
        handleInput(val) {
            Vue.set(this.formData.questions, this.makeId(val.id), val.answer);
        },
        getTranslation(obj, key) {
            let locale = 'en';
            return obj.translation[locale][key];
        },
        getQuestion(question) {
            return this.getTranslation(question, 'question');
        },
        getErrors(question) {
            if (this.errors?.hasOwnProperty(this.makeId(question.id))) {
                return this.errors[this.makeId(question.id)];
            }
        },
        makeId(id) {
            return `id-${id}`;
        },
        makeSurveyId() {
            return `cwl-survey-${this.survey.id}`;
        },
        async submit() {
            this.errors = [];
            try {
                await axios.post(
                    `/api/survey/${this.survey.id}`,
                    this.formData
                );
                this.participated = true;
                localStorage.setItem(this.makeSurveyId(), true);
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        },
    },
};
</script>
