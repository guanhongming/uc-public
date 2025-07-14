<!-- resources/js/Components/ProfileSections/ActivitiesPlansForm.vue -->
<template>
    <div class="activities-plans-form">
        <h2 class="text-2xl font-bold text-gray-800">Activities & Plans</h2>
        <p class="mt-1 mb-8 text-sm text-gray-600">
            Provide detailed information about your activities and future plans.
        </p>

        <form @submit.prevent="saveForm" class="space-y-10">

            <!-- 1. Academic Achievements / Activities -->
            <section>
                <h3 class="section-title">Academic Achievements / Activities</h3>

                <!-- Reading List Sub-section -->
                <div class="mt-4">
                    <h4 class="subsection-title">Reading List</h4>
                    <div class="mt-2 space-y-4">
                        <div v-for="(book, index) in academicData.reading" :key="book.id" class="entry-card">
                            <div class="entry-header">
                                <h5 class="entry-title">Book Entry {{ index + 1 }}</h5>
                                <button type="button" @click="removeEntry('reading', index)" v-if="academicData.reading.length > 1" class="remove-button" aria-label="Remove Book"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </div>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div><label class="form-label">Book Title</label><input type="text" v-model="book.title" class="form-input"></div>
                                    <div><label class="form-label">Start Date</label><input type="date" v-model="book.date" class="form-input"></div>
                                </div>
                                <div><label class="form-label">Why you chose this book</label><textarea rows="2" v-model="book.reason" class="form-input"></textarea></div>
                                <div><label class="form-label">Thoughts after reading <span class="text-red-500">*</span></label><textarea rows="3" v-model="book.thoughts" class="form-input"></textarea></div>
                            </div>
                        </div>
                        <button type="button" @click="addEntry('reading')" class="add-button">Add Book</button>
                    </div>
                </div>

                <!-- Research Sub-section -->
                <div class="mt-6">
                    <h4 class="subsection-title">Research Experience</h4>
                    <div class="mt-2 space-y-4">
                        <div v-for="(item, index) in academicData.research" :key="item.id" class="entry-card">
                           <div class="entry-header">
                                <h5 class="entry-title">Research Entry {{ index + 1 }}</h5>
                                <button type="button" @click="removeEntry('research', index)" v-if="academicData.research.length > 1" class="remove-button" aria-label="Remove Research"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </div>
                            <div class="space-y-4">
                                <div><label class="form-label">Research Name/Topic</label><input type="text" v-model="item.name" class="form-input"></div>
                                <div><label class="form-label">Reason for Choosing</label><textarea rows="2" v-model="item.reason" class="form-input"></textarea></div>
                                <div><label class="form-label">Process</label><textarea rows="3" v-model="item.process" class="form-input"></textarea></div>
                                <div><label class="form-label">Outcome/Findings</label><textarea rows="2" v-model="item.outcome" class="form-input"></textarea></div>
                            </div>
                        </div>
                        <button type="button" @click="addEntry('research')" class="add-button">Add Research</button>
                    </div>
                </div>

                 <!-- Competitions Sub-section -->
                <div class="mt-6">
                    <h4 class="subsection-title">Competitions</h4>
                    <div class="mt-2 space-y-4">
                        <div v-for="(item, index) in academicData.competitions" :key="item.id" class="entry-card">
                            <div class="entry-header">
                                <h5 class="entry-title">Competition Entry {{ index + 1 }}</h5>
                                <button type="button" @click="removeEntry('competitions', index)" v-if="academicData.competitions.length > 1" class="remove-button" aria-label="Remove Competition"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </div>
                            <div class="space-y-4">
                                <div><label class="form-label">Competition Name</label><input type="text" v-model="item.name" class="form-input"></div>
                                <div><label class="form-label">How you prepared</label><textarea rows="3" v-model="item.preparation" class="form-input"></textarea></div>
                                <div><label class="form-label">Awards Won</label><input type="text" v-model="item.awards" class="form-input"></div>
                            </div>
                        </div>
                        <button type="button" @click="addEntry('competitions')" class="add-button">Add Competition</button>
                    </div>
                </div>
            </section>

             <!-- 2. Non-Academic Activities -->
            <section>
                <h3 class="section-title">Non-Academic Activities / Interests</h3>
                 <!-- In-School Sub-section -->
                <div class="mt-4">
                    <h4 class="subsection-title">In-School</h4>
                    <div class="mt-2 space-y-4">
                        <div v-for="(item, index) in nonAcademicData.inSchool" :key="item.id" class="entry-card">
                            <div class="entry-header">
                                <h5 class="entry-title">In-School Entry {{ index + 1 }}</h5>
                                <button type="button" @click="removeEntry('inSchool', index, 'nonAcademic')" v-if="nonAcademicData.inSchool.length > 1" class="remove-button" aria-label="Remove Entry"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </div>
                            <div class="space-y-4">
                                <div><label class="form-label">Activity Name</label><input type="text" v-model="item.name" class="form-input"></div>
                                <div><label class="form-label">Description / My Role</label><textarea rows="3" v-model="item.description" class="form-input"></textarea></div>
                                <div class="flex items-center"><input type="checkbox" v-model="item.isAward" :id="'in-school-award-'+index" class="mr-2 form-checkbox"><label :for="'in-school-award-'+index" class="form-label !mb-0">This was an award</label></div>
                            </div>
                        </div>
                        <button type="button" @click="addEntry('inSchool', 'nonAcademic')" class="add-button">Add In-School Activity</button>
                    </div>
                </div>

                 <!-- Out-of-School Sub-section -->
                <div class="mt-6">
                    <h4 class="subsection-title">Out-of-School</h4>
                    <div class="mt-2 space-y-4">
                         <div v-for="(item, index) in nonAcademicData.outOfSchool" :key="item.id" class="entry-card">
                            <div class="entry-header">
                                <h5 class="entry-title">Out-of-School Entry {{ index + 1 }}</h5>
                                <button type="button" @click="removeEntry('outOfSchool', index, 'nonAcademic')" v-if="nonAcademicData.outOfSchool.length > 1" class="remove-button" aria-label="Remove Entry"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </div>
                            <div class="space-y-4">
                                <div><label class="form-label">Activity Name</label><input type="text" v-model="item.name" class="form-input"></div>
                                <div><label class="form-label">Description / My Role</label><textarea rows="3" v-model="item.description" class="form-input"></textarea></div>
                                <div class="flex items-center"><input type="checkbox" v-model="item.isAward" :id="'out-of-school-award-'+index" class="mr-2 form-checkbox"><label :for="'out-of-school-award-'+index" class="form-label !mb-0">This was an award</label></div>
                            </div>
                        </div>
                        <button type="button" @click="addEntry('outOfSchool', 'nonAcademic')" class="add-button">Add Out-of-School Activity</button>
                    </div>
                </div>
            </section>

            <!-- 3. Holiday Plans -->
            <section>
                 <h3 class="section-title">Holiday / Break Plans</h3>
                 <div class="p-4 mt-4 space-y-4 rounded-lg ring-1 ring-gray-200 bg-gray-50/70">
                    <div><label class="form-label">Classes / Courses</label><textarea rows="2" v-model="holidayPlans.classes" class="form-input"></textarea></div>
                    <div><label class="form-label">Research</label><textarea rows="2" v-model="holidayPlans.research" class="form-input"></textarea></div>
                    <div><label class="form-label">Reading</label><textarea rows="2" v-model="holidayPlans.reading" class="form-input"></textarea></div>
                    <div><label class="form-label">English Tests (IELTS/TOEFL Prep)</label><textarea rows="2" v-model="holidayPlans.englishTests" class="form-input"></textarea></div>
                    <div><label class="form-label">Timeframe</label><input type="text" v-model="holidayPlans.timeframe" placeholder="e.g., July 2024 - August 2024" class="form-input"></div>
                    <div><label class="form-label">Expected Outcome</label><textarea rows="3" v-model="holidayPlans.outcome" class="form-input"></textarea></div>
                 </div>
            </section>

            <!-- Form Actions -->
            <div class="flex justify-end pt-4 space-x-4">
                 <button type="button" @click="$emit('cancel')" class="cancel-button">Cancel</button>
                 <button type="submit" :disabled="isSaving" class="save-button">{{ isSaving ? 'Saving...' : 'Save' }}</button>
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
const isSaving = ref(false);
const academicData = ref({ reading: [], research: [], competitions: [] });
const nonAcademicData = ref({ inSchool: [], outOfSchool: [] });
const holidayPlans = ref({});

// --- BLANK ENTRY CREATORS ---
const createBlank = {
    reading: () => ({ id: Date.now(), title: '', date: '', reason: '', thoughts: '' }),
    research: () => ({ id: Date.now(), name: '', reason: '', process: '', outcome: '' }),
    competitions: () => ({ id: Date.now(), name: '', preparation: '', awards: '' }),
    inSchool: () => ({ id: Date.now(), name: '', description: '', isAward: false }),
    outOfSchool: () => ({ id: Date.now(), name: '', description: '', isAward: false }),
    holiday: () => ({ classes: '', research: '', reading: '', englishTests: '', timeframe: '', outcome: '' }),
};

// --- DYNAMIC FORM LOGIC ---
const dataMap = { academic: academicData, nonAcademic: nonAcademicData };
const addEntry = (type, parent = 'academic') => {
    dataMap[parent].value[type].push(createBlank[type]());
};
const removeEntry = (type, index, parent = 'academic') => {
    dataMap[parent].value[type].splice(index, 1);
};

// --- COMPUTED FIELD IDs ---
const getFieldId = (label) => computed(() => props.sectionFields.find(f => f.label === label)?.id);
const academicFieldId = getFieldId('Academic Achievements / Activities');
const nonAcademicFieldId = getFieldId('Non-Academic Activities / Interests');
const holidayFieldId = getFieldId('Holiday / Break Plans');

// --- INITIALIZATION ---
watch(() => props.initialAnswers, (newAnswers) => {
    // Helper to parse and populate a dynamic section
    const populateSection = (fieldId, dataRef, blankCreators) => {
        try {
            const parsed = JSON.parse(newAnswers[fieldId] || '{}');
            Object.keys(blankCreators).forEach(key => {
                const items = parsed[key] || [];
                dataRef.value[key] = items.length > 0 ? items.map(item => ({ ...item, id: Date.now() + Math.random() })) : [blankCreators[key]()];
            });
        } catch (e) {
            Object.keys(blankCreators).forEach(key => { dataRef.value[key] = [blankCreators[key]()] });
        }
    };
    populateSection(academicFieldId.value, academicData, { reading: createBlank.reading, research: createBlank.research, competitions: createBlank.competitions });
    populateSection(nonAcademicFieldId.value, nonAcademicData, { inSchool: createBlank.inSchool, outOfSchool: createBlank.outOfSchool });

    // Populate holiday plans
    try {
        holidayPlans.value = JSON.parse(newAnswers[holidayFieldId.value]) || createBlank.holiday();
    } catch (e) {
        holidayPlans.value = createBlank.holiday();
    }
}, { immediate: true });


// --- FORM SUBMISSION ---
const isEntryEmpty = (entry) => Object.values(entry).every(v => v === '' || v === false || v === null);
const cleanForSave = (data) => JSON.stringify(data.map(({ id, ...rest }) => rest));

async function saveForm() {
    isSaving.value = true;
    try {
        const answersSubset = {};

        // Process Academic Data
        const academicPayload = {
            reading: academicData.value.reading.filter(e => !isEntryEmpty(e)),
            research: academicData.value.research.filter(e => !isEntryEmpty(e)),
            competitions: academicData.value.competitions.filter(e => !isEntryEmpty(e)),
        };
        answersSubset[academicFieldId.value] = Object.values(academicPayload).some(arr => arr.length > 0) ? JSON.stringify({
             reading: academicPayload.reading.map(({ id, ...rest }) => rest),
             research: academicPayload.research.map(({ id, ...rest }) => rest),
             competitions: academicPayload.competitions.map(({ id, ...rest }) => rest),
        }) : null;

        // Process Non-Academic Data
        const nonAcademicPayload = {
            inSchool: nonAcademicData.value.inSchool.filter(e => !isEntryEmpty(e)),
            outOfSchool: nonAcademicData.value.outOfSchool.filter(e => !isEntryEmpty(e)),
        };
         answersSubset[nonAcademicFieldId.value] = Object.values(nonAcademicPayload).some(arr => arr.length > 0) ? JSON.stringify({
             inSchool: nonAcademicPayload.inSchool.map(({ id, ...rest }) => rest),
             outOfSchool: nonAcademicPayload.outOfSchool.map(({ id, ...rest }) => rest),
        }) : null;

        // Process Holiday Plans
        answersSubset[holidayFieldId.value] = !isEntryEmpty(holidayPlans.value) ? JSON.stringify(holidayPlans.value) : null;

        await axios.post('/api/profile', { answers: answersSubset });
        emit('saved', answersSubset);
    } catch (error) {
        console.error("Error saving Activities & Plans section:", error);
    } finally {
        isSaving.value = false;
    }
}
</script>

<style scoped>
/* General styles used across the form for consistency */
.form-label { @apply block text-sm font-medium text-gray-700 mb-1; }
.form-input { @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 sm:text-sm; }
.form-checkbox { @apply h-4 w-4 rounded border-gray-300 text-navy-600 focus:ring-navy-500; }

.section-title { @apply text-xl font-semibold text-gray-900 pb-2 border-b border-gray-200; }
.subsection-title { @apply text-base font-semibold text-gray-800; }

.entry-card { @apply p-4 rounded-lg bg-gray-50/70 ring-1 ring-gray-200; }
.entry-header { @apply flex justify-between items-center mb-2; }
.entry-title { @apply font-medium text-gray-700; }

.remove-button { @apply inline-flex items-center p-1 text-gray-400 bg-transparent rounded-full hover:bg-red-100 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500; }
.add-button { @apply inline-flex items-center px-3 py-1.5 text-xs font-medium text-navy-700 bg-navy-100 rounded-md border border-transparent hover:bg-navy-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500; }
.cancel-button { @apply px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500; }
.save-button { @apply inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-navy-600 rounded-md border border-transparent shadow-sm hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 disabled:opacity-50 disabled:cursor-not-allowed; }

/* Customizing Tailwind colors for the navy theme */
.focus\:border-navy-500:focus { --tw-border-opacity: 1; border-color: #4A5568; }
.focus\:ring-navy-500:focus { --tw-ring-color: #4A5568; }
.text-navy-600 { color: #2D3748; }
.bg-navy-100 { background-color: #E2E8F0; }
.hover\:bg-navy-200:hover { background-color: #CBD5E0; }
.bg-navy-600 { background-color: #2D3748; }
.hover\:bg-navy-700:hover { background-color: #1A202C; }
.text-navy-700 { color: #1A202C; }
</style>