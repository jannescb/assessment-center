<template>
    <div>
        <select v-if="type == 'select'" v-model="model">
            <option
                :value="answer"
                v-for="(answer, index) in question.answers"
                :key="index"
                >{{ answer }}</option
            >
        </select>
        <div v-else v-for="(answer, index) in question.answers" :key="index">
            <input
                :type="type"
                :id="getId(answer.answer)"
                :value="answer.answer"
                v-model="model"
            />
            <label :for="getId(answer.answer)">
                {{ answer.answer }}
            </label>
        </div>
        {{ errors }}
    </div>
</template>

<script>
export default {
    name: 'MultipleAnswers',
    props: {
        question: {
            type: Object,
            required: true,
        },
        errors: {
            type: Array,
        },
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
        getId(answer) {
            return this.slugify(answer);
        },
    },
    computed: {
        type() {
            return this.question.type;
        },
        answers() {
            return this.question.fields;
        },
    },
};
</script>
