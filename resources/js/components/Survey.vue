<template>
    <div>
        <h1>
            {{ survey.title }}
        </h1>
        <div v-if="participated">
            Danke für deine Teilnahme!
        </div>
        <div v-else>
            <tabs ref="tabs">
                <tab
                    :title="index + 1"
                    v-for="(question, index) in survey.questions"
                    :hasError="hasErrors(survey.questions)"
                    :key="index"
                >
                    <h3>{{ getQuestion(question) }}</h3>
                    <component
                        :is="getInputType(question)"
                        :question="question"
                        @input="handleInput"
                        :getTranslation="getTranslation"
                        :errors="getErrors(question)"
                    />
                    <button @click="submit()" v-if="isLastTab(index)">
                        Submit
                    </button>
                </tab>
            </tabs>

            <button @click="prev()">
                prev
            </button>
            <button @click="next()">
                next
            </button>
            <!-- <pre>{{ formData }}</pre> -->
        </div>
    </div>
</template>

<script>
import MultipleAnswers from './MultipleAnswers';
import SingleAnswer from './SingleAnswer';
import Tabs from './Tabs';
import Tab from './Tab';
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
        Tabs,
        Tab,
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
        hasErrors(questions) {
            if (this.errors) {
                const hasErrors = _.map(questions, question =>
                    Object.keys(this.errors).includes(this.makeId(question.id))
                ).includes(true);
                return hasErrors;
            }
            return false;
        },
        makeId(id) {
            return `id-${id}`;
        },
        makeSurveyId() {
            return `cwl-survey-${this.survey.id}`;
        },
        isLastTab(index) {
            return this.survey.questions.length - 1 == index;
        },
        prev() {
            this.$refs.tabs.prev();
        },
        next() {
            this.$refs.tabs.next();
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
