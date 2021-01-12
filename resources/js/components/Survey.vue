<template>
    <div>
        {{ survey.title }}

        <component
            :is="getType(question)"
            :question="question"
            v-for="(question, index) in survey.questions"
            :key="index"
        />
    </div>
</template>

<script>
import RadioQuestion from './RadioQuestion';
import InputQuestion from './InputQuestion';
import CheckboxQuestion from './CheckboxQuestion';
export default {
    name: 'Survey',
    props: {
        survey: {
            type: Object,
            required: true
        }
    },
    components: {
        RadioQuestion,
        InputQuestion,
        CheckboxQuestion
    },
    methods: {
        getType(question) {
            return (
                {
                    checkbox: 'CheckboxQuestion',
                    input: 'InputQuestion',
                    radio: 'RadioQuestion'
                }[question.question_type] || 'InputQuestion'
            );
        }
    }
};
</script>
