<template>
    <div v-if="hasContent" class="answer-item">
        <strong class="block text-base font-semibold text-gray-800">{{ formattedLabel }}:</strong>

        <div class="pl-4 mt-1">
            <p v-if="isString" class="text-gray-700 whitespace-pre-wrap">{{ data }}</p>

            <div v-else-if="isArray" class="space-y-4">
                <AnswerDisplay
                    v-for="(item, index) in data"
                    :key="index"
                    :data="item"
                    :data-label="`Entry ${index + 1}`"
                />
            </div>

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

const props = defineProps({
    data: {
        required: true,
    },
    dataLabel: {
        type: String,
        required: true,
    }
});

const isString = computed(() => typeof props.data === 'string' || typeof props.data === 'number' || typeof props.data === 'boolean');
const isArray = computed(() => Array.isArray(props.data));
const isObject = computed(() => typeof props.data === 'object' && props.data !== null && !isArray.value);

const formattedLabel = computed(() => {
    const result = props.dataLabel.replace(/([A-Z])/g, ' $1');
    return result.charAt(0).toUpperCase() + result.slice(1);
});

const hasContent = computed(() => {
    const d = props.data;
    if (d === null || d === undefined) return false;
    if (isString.value && String(d).trim() === '') return false;
    if (isArray.value && d.length === 0) return false;
    if (isObject.value && Object.keys(d).length === 0) return false;
    if (isObject.value) {
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