<!-- resources/js/Components/ProfileSections/TestScoresForm.vue -->
<template>
    <div class="test-scores-form">
        <h2 class="text-2xl font-bold text-gray-800">Standardized Test Scores</h2>
        <p class="mt-1 mb-8 text-sm text-gray-600">
            Provide details for any language or aptitude tests. For language tests, you can add multiple entries.
        </p>

        <form @submit.prevent="saveForm" class="space-y-8">
            <!-- Section 1: Language Tests (IELTS/TOEFL) -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Language Tests (IELTS, TOEFL, etc.)</h3>
                <div class="mt-4 space-y-6">
                    <div v-for="(test, index) in languageTests" :key="test.id" class="p-4 rounded-lg ring-1 ring-gray-200 bg-gray-50/70">
                        <div class="flex justify-between items-start">
                             <h4 class="mb-4 font-medium text-gray-700">Test Entry {{ index + 1 }}</h4>
                             <button
                                v-if="languageTests.length > 1"
                                type="button"
                                @click="removeLanguageTest(index)"
                                class="-mt-1 -mr-1 remove-button"
                                aria-label="Remove test entry">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                             </button>
                        </div>

                        <!-- Test Type and Date -->
                        <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700">Test Type</label>
                                <select v-model="test.type" class="mt-1 form-input">
                                    <option>IELTS</option>
                                    <option>TOEFL</option>
                                    <option>Duolingo</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Test Date</label>
                                <input type="date" v-model="test.date" class="mt-1 form-input" />
                            </div>
                        </div>

                        <!-- Scores Grid -->
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Overall</label>
                                <input type="text" v-model="test.overall" placeholder="e.g., 7.5" class="mt-1 form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Listening</label>
                                <input type="text" v-model="test.listening" placeholder="e.g., 8.0" class="mt-1 form-input" />
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700">Reading</label>
                                <input type="text" v-model="test.reading" placeholder="e.g., 7.0" class="mt-1 form-input" />
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700">Writing</label>
                                <input type="text" v-model="test.writing" placeholder="e.g., 7.0" class="mt-1 form-input" />
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700">Speaking</label>
                                <input type="text" v-model="test.speaking" placeholder="e.g., 7.5" class="mt-1 form-input" />
                            </div>
                        </div>
                    </div>
                     <!-- Add Button -->
                    <button type="button" @click="addLanguageTest" class="add-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        Add Another Entry
                    </button>
                </div>
            </div>

            <!-- Section 2: SAT/ACT -->
            <div>
                 <label :for="satActFieldId" class="text-lg font-semibold text-gray-800">
                    SAT/ACT Details
                 </label>
                 <textarea
                    :id="satActFieldId"
                    v-model="satActDetails"
                    rows="4"
                    class="mt-2 form-input"
                    placeholder="e.g., SAT: 1450 (Math: 780, EBRW: 670) - March 2024"
                  ></textarea>
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
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
  sectionFields: { type: Array, required: true },
  initialAnswers: { type: Object, required: true },
});

const emit = defineEmits(['saved', 'cancel']);

// --- STATE ---
const languageTests = ref([]);
const satActDetails = ref('');
const isSaving = ref(false);

// --- COMPUTED PROPERTIES to find field IDs ---
const getFieldByLabel = (label) => props.sectionFields.find(f => f.label === label);

const languageFieldId = computed(() => getFieldByLabel('Language Test Details (e.g., IELTS/TOEFL Score, Date)')?.id);
const satActFieldId = computed(() => getFieldByLabel('SAT/ACT Details (Score, Sections)')?.id);


// --- DYNAMIC FORM LOGIC ---
const createBlankTest = () => ({
    id: Date.now(),
    type: 'IELTS',
    date: '',
    overall: '',
    listening: '',
    reading: '',
    writing: '',
    speaking: '',
});

const addLanguageTest = () => {
    languageTests.value.push(createBlankTest());
};

const removeLanguageTest = (index) => {
    languageTests.value.splice(index, 1);
};


// --- INITIALIZATION ---
watch(() => props.initialAnswers, (newAnswers) => {
    // Populate Language Tests
    const initialLangData = newAnswers[languageFieldId.value];
    if (initialLangData) {
        try {
            const parsedData = JSON.parse(initialLangData);
            if (Array.isArray(parsedData) && parsedData.length > 0) {
                // Add a unique key for v-for
                languageTests.value = parsedData.map(item => ({ ...item, id: Date.now() + Math.random() }));
            } else {
                languageTests.value = [createBlankTest()];
            }
        } catch (e) {
            console.error("Could not parse language test data. Initializing with a blank entry.", e);
            languageTests.value = [createBlankTest()];
        }
    } else {
        // If no data, start with one blank entry
        languageTests.value = [createBlankTest()];
    }

    // Populate SAT/ACT Details
    satActDetails.value = newAnswers[satActFieldId.value] || '';

}, { immediate: true });


// --- FORM SUBMISSION ---
async function saveForm() {
    isSaving.value = true;
    try {
        const answersSubset = {};

        // 1. Process Language Tests: filter out empty entries, then stringify
        const nonEmptyTests = languageTests.value.filter(test =>
            test.type || test.date || test.overall || test.listening || test.reading || test.writing || test.speaking
        );

        // Remove the temporary 'id' key before saving
        const dataToSave = nonEmptyTests.map(({ id, ...rest }) => rest);

        if (languageFieldId.value) {
            answersSubset[languageFieldId.value] = dataToSave.length > 0 ? JSON.stringify(dataToSave) : null;
        }

        // 2. Process SAT/ACT Details
        if (satActFieldId.value) {
            answersSubset[satActFieldId.value] = satActDetails.value.trim() || null;
        }

        await axios.post('/api/profile', { answers: answersSubset });
        emit('saved', answersSubset);

    } catch (error) {
        console.error("Error saving test scores section:", error);
    } finally {
        isSaving.value = false;
    }
}
</script>

<style scoped>
.form-input {
    @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 sm:text-sm;
}
.remove-button {
    @apply inline-flex items-center p-1 text-gray-400 bg-transparent rounded-full hover:bg-red-100 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500;
}
.add-button {
    @apply inline-flex items-center px-4 py-2 text-sm font-medium text-navy-700 bg-navy-100 rounded-md border border-transparent hover:bg-navy-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500;
}
.cancel-button {
    @apply px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500;
}
.save-button {
    @apply inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-navy-600 rounded-md border border-transparent shadow-sm hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 disabled:opacity-50 disabled:cursor-not-allowed;
}

/* Customizing Tailwind colors for the navy theme */
.focus\:border-navy-500:focus { --tw-border-opacity: 1; border-color: #4A5568; }
.focus\:ring-navy-500:focus { --tw-ring-color: #4A5568; }
.bg-navy-100 { background-color: #E2E8F0; }
.hover\:bg-navy-200:hover { background-color: #CBD5E0; }
.bg-navy-600 { background-color: #2D3748; }
.hover\:bg-navy-700:hover { background-color: #1A202C; }
.text-navy-700 { color: #1A202C; }
</style>