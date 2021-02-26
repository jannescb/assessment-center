<template>
    <div>
        <pre>{{ survey }}</pre>
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
                    v-for="(step, index) in survey.steps"
                    :hasError="hasErrors(step)"
                    :key="index"
                >
                    <div v-for="(question, index) in step" :key="index">
                        <h3>{{ question.question }}</h3>

                        <div class="pb-6">
                            <component
                                :is="getInputType(question)"
                                :question="question"
                                @input="handleInput"
                                :errors="getErrors(question)"
                            />
                        </div>
                    </div>
                    <button
                        @click="submit()"
                        v-if="isLastTab(index)"
                        class="btn"
                    >
                        Submit
                    </button>
                </tab>
            </tabs>

            <button @click="prev()" class="btn">
                prev
            </button>
            <button @click="next()" class="btn">
                next
            </button>
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

            let questions = _.flatten(this.survey.steps);

            for (const key in questions) {
                if (Object.hasOwnProperty.call(questions, key)) {
                    const element = questions[key];
                    data.questions[this.makeId(element.id)] = null;
                }
            }

            return data;
        },
        getInputType(question) {
            return ['checkbox', 'radio', 'select'].includes(question.type)
                ? 'MultipleAnswers'
                : 'SingleAnswer';
        },
        handleInput(val) {
            Vue.set(this.formData.questions, this.makeId(val.id), val.answer);
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
            return this.survey.steps.length - 1 == index;
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

<style lang="scss" scoped>
.btn {
    @apply px-4 py-2 bg-indigo-300 rounded-full;
}
</style>
