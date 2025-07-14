<!-- resources/js/Components/ProfileSections/IgSubjectsForm.vue -->
<template>
    <!-- The outer container has no border or shadow to blend into the parent card -->
    <div class="ig-subjects-form">
        <h2 class="text-2xl font-bold text-gray-800">IG Subjects & Scores</h2>
        <p class="mt-1 mb-8 text-sm text-gray-600">Enter up to 5 of your IG subjects and their official CIE scores.</p>

        <form @submit.prevent="saveForm" class="space-y-4">

            <!-- Dynamic Subject/Score Pairs -->
            <div v-for="(subject, index) in subjects" :key="subject.id"
                 class="subject-pair-row"
                 :class="{ 'is-incomplete': (subject.subjectValue && !subject.scoreValue) || (!subject.subjectValue && subject.scoreValue) }">

                <div class="grid grid-cols-1 gap-y-2 gap-x-4 sm:grid-cols-5">
                    <!-- Subject Input -->
                    <div class="sm:col-span-2">
                        <label :for="'ig-subject-' + index" class="block text-sm font-medium text-gray-700">
                            IG Subject {{ index + 1 }}
                        </label>
                        <input
                            type="text"
                            :id="'ig-subject-' + index"
                            v-model="subject.subjectValue"
                            placeholder="e.g. Mathematics"
                            class="mt-1 form-input"
                            @keydown.enter.prevent
                        >
                    </div>

                     <!-- Score Input -->
                    <div class="sm:col-span-2">
                        <label :for="'ig-score-' + index" class="block text-sm font-medium text-gray-700">
                             Official CIE Score
                        </label>
                        <input
                            type="text"
                            :id="'ig-score-' + index"
                            v-model="subject.scoreValue"
                            placeholder="e.g. A*, 8"
                            class="mt-1 form-input"
                            @keydown.enter.prevent
                        >
                    </div>

                    <!-- Remove Button -->
                    <div class="flex justify-end items-end sm:col-span-1">
                         <button
                            v-if="subjects.length > 1"
                            type="button"
                            @click="removeSubject(index)"
                            class="remove-button"
                            aria-label="Remove subject">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add Subject Button -->
            <div v-if="subjects.length < 5" class="pt-2">
                 <button
                    type="button"
                    @click="addSubject"
                    class="add-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Another Subject
                </button>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end pt-6 space-x-4">
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
  sectionFields: {
    type: Array,
    required: true,
  },
  initialAnswers: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['saved', 'cancel']);

// --- STATE ---
const subjects = ref([]); // Holds our dynamic list of subject objects
const isSaving = ref(false);
const MAX_SUBJECTS = 5;

// --- HELPERS ---
const getFieldIdByLabel = (label) => props.sectionFields.find(f => f.label === label)?.id || null;

// --- DYNAMIC FIELD LOGIC ---
const addSubject = () => {
    if (subjects.value.length < MAX_SUBJECTS) {
        subjects.value.push({
            id: Date.now(), // Unique key for v-for
            subjectValue: '',
            scoreValue: '',
        });
    }
};

const removeSubject = (index) => {
    subjects.value.splice(index, 1);
};


// --- LIFECYCLE & WATCHERS ---
// Populate the 'subjects' ref from initial props
watch(() => props.sectionFields, (newFields) => {
    if (newFields.length === 0) return;

    const populatedSubjects = [];
    for (let i = 1; i <= MAX_SUBJECTS; i++) {
        const subjectFieldId = getFieldIdByLabel(`IG Subject ${i}`);
        const scoreFieldId = getFieldIdByLabel(`Official CIE Score ${i}`);

        const subjectAnswer = props.initialAnswers[subjectFieldId] || '';
        const scoreAnswer = props.initialAnswers[scoreFieldId] || '';

        // Add the row only if it has data
        if (subjectAnswer || scoreAnswer) {
            populatedSubjects.push({
                id: Date.now() + i,
                subjectValue: subjectAnswer,
                scoreValue: scoreAnswer,
            });
        }
    }

    // Always ensure at least one row is visible
    if (populatedSubjects.length === 0) {
        populatedSubjects.push({
            id: Date.now(),
            subjectValue: '',
            scoreValue: '',
        });
    }
    subjects.value = populatedSubjects;
}, { immediate: true });


// --- FORM SUBMISSION ---
async function saveForm() {
    isSaving.value = true;
    try {
        const answersSubset = {};

        // Map our dynamic array back to the ID-based structure expected by the backend
        subjects.value.forEach((item, index) => {
            const subjectFieldId = getFieldIdByLabel(`IG Subject ${index + 1}`);
            const scoreFieldId = getFieldIdByLabel(`Official CIE Score ${index + 1}`);

            if (subjectFieldId) {
                answersSubset[subjectFieldId] = item.subjectValue?.trim() || null;
            }
            if (scoreFieldId) {
                answersSubset[scoreFieldId] = item.scoreValue?.trim() || null;
            }
        });

        await axios.post('/api/profile', { answers: answersSubset });
        emit('saved', answersSubset);

    } catch (error) {
        console.error("Error saving IG Subjects & Scores:", error);
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

.subject-pair-row {
    @apply p-4 rounded-lg border border-transparent transition-colors duration-300;
}

.subject-pair-row.is-incomplete {
    @apply bg-yellow-50 border-yellow-200;
}

.remove-button {
    @apply inline-flex items-center p-2 text-gray-400 bg-transparent rounded-full hover:bg-red-100 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500;
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
.focus\:border-navy-500:focus {
    --tw-border-opacity: 1;
    border-color: #4A5568; /* navy-500 */
}
.focus\:ring-navy-500:focus {
    --tw-ring-color: #4A5568; /* navy-500 */
}
.bg-navy-100 { background-color: #E2E8F0; } /* navy-100 */
.hover\:bg-navy-200:hover { background-color: #CBD5E0; } /* navy-200 */
.bg-navy-600 { background-color: #2D3748; } /* navy-600 */
.hover\:bg-navy-700:hover { background-color: #1A202C; } /* navy-700 */
.text-navy-700 { color: #1A202C; } /* navy-700 */
</style>