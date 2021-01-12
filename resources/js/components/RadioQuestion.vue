<template>
    <question :question="question">
        <fieldset>
            <div v-for="(answer, index) in question.answers" :key="index">
                <input type="radio" :id="index" name="answer" :value="answer" />
                <label :for="index">{{ answer.translation.en.answer }}</label>
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
    }
};
</script>
