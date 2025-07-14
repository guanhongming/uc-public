<!-- resources/js/Components/ProfileSections/UniversityDetailsForm.vue -->
<template>
    <!-- The outer container has no styling to blend into its parent -->
    <div class="university-details-form">
        <h2 class="text-2xl font-bold text-gray-800">University Details</h2>
        <p class="mt-1 mb-8 text-sm text-gray-600">Please provide the mandatory details about your university applications.</p>

        <form @submit.prevent="saveForm" class="space-y-6">
            <div v-for="field in sectionFields" :key="field.id">
                <label :for="'field-' + field.id" class="block text-sm font-medium text-gray-700">
                    {{ field.label }}
                    <span v-if="field.is_required" class="text-red-500">*</span>
                </label>

                <textarea
                    v-if="field.field_type === 'textarea'"
                    :id="'field-' + field.id"
                    v-model="formData[field.id]"
                    rows="4"
                    class="mt-1 form-input"
                    :required="field.is_required"
                ></textarea>

                <input
                    v-else
                    type="text"
                    :id="'field-' + field.id"
                    v-model="formData[field.id]"
                    class="mt-1 form-input"
                    @keydown.enter.prevent
                    :required="field.is_required"
                >
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end pt-4 space-x-4">
                 <button type="button" @click="$emit('cancel')" class="cancel-button">
                    Cancel
                 </button>
                 <button type="submit" :disabled="isSaving" class="save-button">
                    {{ isSaving ? 'Saving...' : 'Save' }}
                 </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  sectionFields: { type: Array, required: true },
  initialAnswers: { type: Object, required: true },
});

const emit = defineEmits(['saved', 'cancel']);

const formData = ref({});
const isSaving = ref(false);

watch(() => props.initialAnswers, (newAnswers) => {
    const initialData = {};
    props.sectionFields.forEach(field => {
        initialData[field.id] = newAnswers[field.id] || '';
    });
    formData.value = initialData;
}, { immediate: true });


async function saveForm() {
    isSaving.value = true;
    try {
        const answersSubset = {};
        props.sectionFields.forEach(field => {
             // Send null if the field is empty after trimming, otherwise send the value
             answersSubset[field.id] = formData.value[field.id]?.trim() || null;
        });

        await axios.post('/api/profile', { answers: answersSubset });
        emit('saved', answersSubset);

    } catch (error) {
        console.error("Error saving University Details:", error);
    } finally {
        isSaving.value = false;
    }
}
</script>

<style scoped>
/* Using @apply to group utilities for cleaner template code */
.form-input {
    @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 sm:text-sm;
}
.cancel-button {
    @apply px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500;
}
.save-button {
    @apply inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-navy-600 rounded-md border border-transparent shadow-sm hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 disabled:opacity-50 disabled:cursor-not-allowed;
}

/* Customizing Tailwind colors for the navy theme */
.focus\:border-navy-500:focus {
    --tw-border-opacity: 1;
    border-color: #4A5568; /* navy-500 */
}
.focus\:ring-navy-500:focus {
    --tw-ring-color: #4A5568; /* navy-500 */
}
.bg-navy-600 { background-color: #2D3748; } /* navy-600 */
.hover\:bg-navy-700:hover { background-color: #1A202C; } /* navy-700 */
</style>