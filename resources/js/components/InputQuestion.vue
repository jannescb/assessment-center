<template>
    <question :question="question">
        <input type="text" />
    </question>
</template>

<script>
import Question from './Question';
export default {
    name: 'InputQuestion',
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
