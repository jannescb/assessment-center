<template>
    <question :question="question.translation.en.question">
        <fieldset>
            <div v-for="(answer, index) in question.answers" :key="index">
                <input
                    type="radio"
                    :id="`${index}-${getQuestion}`"
                    :name="getQuestion"
                    :value="answer.translation.de.answer"
                />
                <label :for="`${index}-${getQuestion}`">{{
                    answer.translation.en.answer
                }}</label>
            </div>
        </fieldset>
    </question>
</template>

<script>
import Question from './Question';
export default {
    name: 'RadioQuestion',
    props: {
        question: {
            type: Object,
            required: true
        }
    },
    components: {
        Question
    },
    computed: {
        getQuestion() {
            return this.question.translation.en.question;
        }
    }
};
</script>
