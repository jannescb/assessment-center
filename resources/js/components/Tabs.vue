<template>
    <div>
        <ul class="flex justify-around my-6">
            <li
                class="inline-flex items-center justify-center w-8 h-8 rounded-full"
                :class="{
                    'bg-gray-200': !tab.hasError,
                    'border border-red-300': tab.hasError,
                    'bg-gray-500 text-white': selectedIndex == index,
                }"
                v-for="(tab, index) in tabs"
                :key="tab.title"
                @click="selectTab(index)"
            >
                {{ tab.title }}
            </li>
        </ul>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'Tabs',
    data() {
        return {
            selectedIndex: 0, // the index of the selected tab,
            tabs: [], // all of the tabs
        };
    },
    watch: {
        tabs(val) {
            if (val.length == 1) {
                this.selectTab(0);
            }
        },
    },
    created() {
        this.tabs = this.$children;
    },
    mounted() {
        this.selectTab(0);
    },
    methods: {
        selectTab(i) {
            if (i >= this.tabs.length) {
                return;
            }
            if (i < 0) {
                return;
            }

            this.selectedIndex = i;

            // loop over all the tabs
            this.tabs.forEach((tab, index) => {
                tab.isActive = index === i;
            });
        },
        prev() {
            this.selectTab(this.selectedIndex - 1);
        },
        next() {
            this.selectTab(this.selectedIndex + 1);
        },
    },
};
</script>

<style></style>
