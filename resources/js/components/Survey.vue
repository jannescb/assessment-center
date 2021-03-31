<template>
    <div class="grid w-full h-screen grid-cols-2">
        <div
            class="flex items-center justify-center max-h-screen overflow-scroll"
        >
            <div class="p-6 my-6 mb-16 bg-white shadow-lg rounded-xl w-96">
                <h1 class="text-2xl text-center">
                    {{ survey.title }}
                </h1>
                <div
                    v-if="participated"
                    class="flex flex-col items-center mt-8"
                >
                    <div
                        class="flex items-center justify-center mb-8 text-white bg-green-500 rounded-full w-52 h-52"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="-5 -7 24 24"
                            width="24"
                            height="24"
                            preserveAspectRatio="xMinYMin"
                            class="w-40 h-40 fill-current"
                        >
                            <path
                                d="M5.486 9.73a.997.997 0 0 1-.707-.292L.537 5.195A1 1 0 1 1 1.95 3.78l3.535 3.535L11.85.952a1 1 0 0 1 1.415 1.414L6.193 9.438a.997.997 0 0 1-.707.292z"
                            ></path>
                        </svg>
                    </div>
                    Danke für deine Teilnahme!
                </div>
                <div v-else>
                    <tabs ref="tabs" @selected="handleSelected">
                        <tab
                            :title="index + 1"
                            v-for="(step, index) in survey.steps"
                            :hasError="hasErrors(step)"
                            :key="index"
                        >
                            <div v-for="(question, index) in step" :key="index">
                                <h3 class="my-2 ">
                                    {{ question.question }}
                                    <span
                                        v-if="isRequired(question)"
                                        class="text-red-600"
                                    >
                                        *
                                    </span>
                                </h3>

                                <div class="pb-6">
                                    <component
                                        :is="getInputType(question)"
                                        :question="question"
                                        @input="handleInput"
                                        :errors="getErrors(question)"
                                    />
                                </div>
                            </div>
                        </tab>
                    </tabs>
                    <div class="flex justify-end">
                        <button
                            @click="prev()"
                            class="p-2 ml-2 text-white bg-gray-800 hover:bg-gray-900 rounded-xl focus:outline-none focus:shadow-lg"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="-8 -5 24 24"
                                width="24"
                                height="24"
                                preserveAspectRatio="xMinYMin"
                                class="w-6 h-6 fill-current"
                            >
                                <path
                                    d="M2.757 7l4.95 4.95a1 1 0 1 1-1.414 1.414L.636 7.707a1 1 0 0 1 0-1.414L6.293.636A1 1 0 0 1 7.707 2.05L2.757 7z"
                                ></path>
                            </svg>
                        </button>
                        <button
                            @click="next()"
                            class="p-2 ml-2 text-white bg-gray-800 hover:bg-gray-900 rounded-xl focus:outline-none focus:shadow-lg"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="-8 -5 24 24"
                                width="24"
                                height="24"
                                preserveAspectRatio="xMinYMin"
                                class="w-6 h-6 fill-current"
                            >
                                <path
                                    d="M5.314 7.071l-4.95-4.95A1 1 0 0 1 1.778.707l5.657 5.657a1 1 0 0 1 0 1.414l-5.657 5.657a1 1 0 0 1-1.414-1.414l4.95-4.95z"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <button
                        v-if="lastTabActive"
                        @click="submit()"
                        class="w-full py-3 mt-2 text-white rounded-xl focus:outline-none focus:shadow-lg"
                        :class="{
                            'bg-red-500': errors,
                            'bg-blue-500': !errors,
                        }"
                    >
                        Absenden
                    </button>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end">
            <illustration />
        </div>
    </div>
</template>

<script>
import MultipleAnswers from './MultipleAnswers';
import SingleAnswer from './SingleAnswer';
import Tabs from './Tabs';
import Tab from './Tab';
import Illustration from './Illustration';
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
        Illustration,
    },
    data() {
        return {
            formData: this.init(),
            errors: null,
            participated: false,
            activeIndex: 0,
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

            this.survey.steps.forEach(step => {
                step.forEach(question => {
                    data.questions[this.makeId(question.id)] = null;
                });
            });

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
        hasErrors(step) {
            if (this.errors) {
                return (
                    step.filter(question => {
                        return Object.keys(this.errors).includes(
                            this.makeId(question.id)
                        );
                    }).length > 0
                );
            }
            return false;
        },
        isRequired(question) {
            return question.validate?.includes('required');
        },
        makeId(id) {
            return `id-${id}`;
        },
        makeSurveyId() {
            return `cwl-survey-${this.survey.id}`;
        },
        handleSelected(index) {
            return (this.activeIndex = index);
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
    computed: {
        lastTabActive() {
            return this.activeIndex == this.survey.steps.length - 1;
        },
    },
};
</script>
