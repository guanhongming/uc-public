<!-- resources/js/Components/AnswerDisplay.vue -->
<template>
    <!-- The component only renders if it has actual content to display -->
    <div v-if="hasContent" class="answer-item">
        <!-- Renders the label for the current piece of data (e.g., "Academic Achievements", "Reading", "Entry 1") -->
        <strong class="block text-base font-semibold text-gray-800">{{ formattedLabel }}:</strong>

        <!-- This is the container for the value, which will be indented -->
        <div class="pl-4 mt-1">
            <!-- Case 1: The data is a simple string. Display it. -->
            <p v-if="isString" class="text-gray-700 whitespace-pre-wrap">{{ data }}</p>

            <!-- Case 2: The data is an array. Loop through it and call this component for each item. -->
            <div v-else-if="isArray" class="space-y-4">
                <AnswerDisplay
                    v-for="(item, index) in data"
                    :key="index"
                    :data="item"
                    :data-label="`Entry ${index + 1}`"
                />
            </div>

            <!-- Case 3: The data is an object. Loop through its keys and call this component for each key-value pair. -->
            <div v-else-if="isObject" class="space-y-2">
                <AnswerDisplay
                    v-for="(value, key) in data"
                    :key="key"
                    :data="value"
                    :data-label="key"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

// The component now accepts 'data' and 'dataLabel' instead of a whole 'answer' object.
// This makes it more generic and suitable for recursion.
const props = defineProps({
    data: {
        required: true,
    },
    dataLabel: {
        type: String,
        required: true,
    }
});

// --- TYPE CHECKING ---
const isString = computed(() => typeof props.data === 'string' || typeof props.data === 'number' || typeof props.data === 'boolean');
const isArray = computed(() => Array.isArray(props.data));
const isObject = computed(() => typeof props.data === 'object' && props.data !== null && !isArray.value);

// --- FORMATTING ---
// Makes keys like 'inSchool' or 'isAward' look like 'In School' or 'Is Award'
const formattedLabel = computed(() => {
    const result = props.dataLabel.replace(/([A-Z])/g, ' $1');
    return result.charAt(0).toUpperCase() + result.slice(1);
});

// --- CONTENT VALIDATION ---
// This is the core logic to decide if the component should render at all.
// It prevents empty fields, empty objects, and empty arrays from being shown.
const hasContent = computed(() => {
    const d = props.data;
    if (d === null || d === undefined) return false;
    if (isString.value && String(d).trim() === '') return false;
    if (isArray.value && d.length === 0) return false;
    if (isObject.value && Object.keys(d).length === 0) return false;
    // Special check for an object whose children are all empty.
    if (isObject.value) {
        // This is a deep check. If an object contains keys but all their values are empty
        // (e.g. { reading: [], research: [] }), we should not render it.
        return Object.values(d).some(value => {
            if (Array.isArray(value)) return value.length > 0;
            if (typeof value === 'object' && value !== null) return Object.keys(value).length > 0;
            return value !== null && value !== '';
        });
    }
    return true;
});
</script>

<style scoped>
.answer-item {
    margin-bottom: 1rem;
    line-height: 1.6;
}
</style>