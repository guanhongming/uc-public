<!-- resources/js/Components/ProfileSections/OfferOutcomeForm.vue -->
<template>
    <div class="offer-outcome-form">
        <h2 class="text-2xl font-bold text-gray-800">University Offer & Outcome</h2>
        <p class="mt-1 mb-8 text-sm text-gray-600">
             Log any university offers you receive and, eventually, your final choice.
        </p>

        <form @submit.prevent="saveForm" class="space-y-10">
            <!-- Section 1: University Offers Received -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800">University Offers Received</h3>
                <div class="mt-4 space-y-6">
                    <div v-for="(offer, index) in universityOffers" :key="offer.id" class="offer-entry-card">
                        <div class="flex justify-between items-start mb-4">
                             <h4 class="font-medium text-gray-700">Offer Entry {{ index + 1 }}</h4>
                             <button
                                v-if="universityOffers.length > 1"
                                type="button"
                                @click="removeUniversityOffer(index)"
                                class="-mt-1 -mr-1 remove-button"
                                aria-label="Remove offer entry">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                             </button>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">University Name</label>
                                <input type="text" v-model="offer.university" placeholder="e.g., University of Toronto" class="mt-1 form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Major / Course Offered</label>
                                <input type="text" v-model="offer.major" placeholder="e.g., Computer Science" class="mt-1 form-input" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Offer Status</label>
                                <select v-model="offer.status" class="mt-1 form-input">
                                    <option>Conditional</option>
                                    <option>Unconditional</option>

                                </select>
                            </div>
                             <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Conditions (if any)</label>
                                <textarea v-model="offer.conditions" rows="3" class="mt-1 form-input" placeholder="e.g., Achieve A*AA in A-Levels, with A* in Mathematics."></textarea>
                            </div>
                        </div>
                    </div>
                     <!-- Add Button -->
                    <button type="button" @click="addUniversityOffer" class="add-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        Add Offer
                    </button>
                </div>
            </div>

            <!-- Section 2: Final Decision -->
            <div>
                 <h3 class="text-lg font-semibold text-gray-800">Final University Choice</h3>
                 <p class="mt-1 text-sm text-gray-600">Once you've made your decision, fill this in. This is for the university you will be attending.</p>
                 <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2">
                     <div>
                        <label class="block text-sm font-medium text-gray-700">University Name</label>
                        <input type="text" v-model="finalDecision.university" class="mt-1 form-input" />
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700">Major Attended</label>
                        <input type="text" v-model="finalDecision.major" class="mt-1 form-input" />
                    </div>
                 </div>
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
const universityOffers = ref([]);
const finalDecision = ref({ university: '', major: '' });
const isSaving = ref(false);


const getFieldByLabel = (label) => props.sectionFields.find(f => f.label === label);

const offersFieldId = computed(() => getFieldByLabel('University Offer Conditions')?.id);
const finalDecisionFieldId = computed(() => getFieldByLabel('College Went')?.id);

// --- DYNAMIC FORM LOGIC ---
const createBlankOffer = () => ({
    id: Date.now(),
    university: '',
    major: '',
    status: 'Conditional',
    conditions: '',
});

const addUniversityOffer = () => {
    universityOffers.value.push(createBlankOffer());
};

const removeUniversityOffer = (index) => {
    universityOffers.value.splice(index, 1);
};

// --- INITIALIZATION ---
watch(() => props.initialAnswers, (newAnswers) => {
    // Populate University Offers
    const initialOffersData = newAnswers[offersFieldId.value];
    if (initialOffersData) {
        try {
            const parsedData = JSON.parse(initialOffersData);
            if (Array.isArray(parsedData) && parsedData.length > 0) {
                universityOffers.value = parsedData.map(item => ({ ...item, id: Date.now() + Math.random() }));
            } else {
                universityOffers.value = [createBlankOffer()];
            }
        } catch (e) {
            universityOffers.value = [createBlankOffer()];
        }
    } else {
        universityOffers.value = [createBlankOffer()];
    }

    // Populate Final Decision
    const initialFinalDecision = newAnswers[finalDecisionFieldId.value];
     if (initialFinalDecision) {
        try {
            const parsedData = JSON.parse(initialFinalDecision);
            finalDecision.value = { ...{ university: '', major: '' }, ...parsedData };
        } catch(e) {
            finalDecision.value = { university: '', major: '' };
        }
    } else {
         finalDecision.value = { university: '', major: '' };
    }

}, { immediate: true });


// --- FORM SUBMISSION ---
async function saveForm() {
    isSaving.value = true;
    try {
        const answersSubset = {};

        // 1. Process University Offers
        const nonEmptyOffers = universityOffers.value.filter(o => o.university || o.major || o.conditions);
        const offersToSave = nonEmptyOffers.map(({ id, ...rest }) => rest);
        if (offersFieldId.value) {
            answersSubset[offersFieldId.value] = offersToSave.length > 0 ? JSON.stringify(offersToSave) : null;
        }

        // 2. Process Final Decision
        const hasFinalDecision = finalDecision.value.university || finalDecision.value.major;
        if (finalDecisionFieldId.value) {
            answersSubset[finalDecisionFieldId.value] = hasFinalDecision ? JSON.stringify(finalDecision.value) : null;
        }

        await axios.post('/api/profile', { answers: answersSubset });
        emit('saved', answersSubset);

    } catch (error) {
        console.error("Error saving Offer & Outcome section:", error);
    } finally {
        isSaving.value = false;
    }
}
</script>

<style scoped>
.form-input {
    @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 sm:text-sm;
}
.offer-entry-card {
    @apply p-4 rounded-lg bg-gray-50/70 ring-1 ring-gray-200;
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
.focus\:border-navy-500:focus { --tw-border-opacity: 1; border-color: #4A5568; }
.focus\:ring-navy-500:focus { --tw-ring-color: #4A5568; }
.bg-navy-100 { background-color: #E2E8F0; }
.hover\:bg-navy-200:hover { background-color: #CBD5E0; }
.bg-navy-600 { background-color: #2D3748; }
.hover\:bg-navy-700:hover { background-color: #1A202C; }
.text-navy-700 { color: #1A202C; }
</style>