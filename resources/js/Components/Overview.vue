<template>
    <div class="p-4 mx-auto max-w-4xl">
        <div v-if="isLoading" class="text-center text-gray-500">Loading profile data...</div>

        <div v-else>
             <div v-if="showMandatoryPrompt" class="p-4 mb-6 text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500" role="alert">
                <p class="font-bold">Mandatory Information Required</p>
                <p>Before proceeding, please complete the mandatory 'University Details' section.</p>
            </div>

            <div v-if="currentSection === 'dashboard'">
                <ProfileDashboard
                    :sections="sectionStructure"
                    :statuses="sectionStatuses"
                    @open-section="openSection"
                />
            </div>

            <div v-else>
                <component
                    :is="currentSectionComponent"
                    :section-fields="getCurrentSectionFields()"
                    :initial-answers="answers"
                    @saved="handleSectionSaved"
                    @cancel="cancelSectionEdit"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, shallowRef, onMounted, computed } from 'vue';
import axios from 'axios';
import ProfileDashboard from './student/StudentDashboard.vue';
import UniversityDetailsForm from '@/Components/student/forms/UniversityDetailsForm.vue';
import IgSubjectsForm from '@/Components/student/forms/IgSubjectsForm.vue';
import AlSubjectsForm from '@/Components/student/forms/AlSubjectsForm.vue';
import TestScoresForm from '@/Components/student/forms/TestScoresForm.vue';
import OfferOutcomeForm from '@/Components/student/forms/OfferOutcomeForm.vue';
import ActivitiesPlansForm from '@/Components/student/forms/ActivitiesPlansForm.vue';


const isLoading = ref(true);
const fields = ref([]);
const answers = ref({});
const currentSection = ref('dashboard');
const showMandatoryPrompt = ref(false);

const sectionComponents = {
    university: UniversityDetailsForm,
    activities_plans: ActivitiesPlansForm,
    test_scores: TestScoresForm,
    ig_subjects: IgSubjectsForm,
    al_subjects: AlSubjectsForm,
    offer_outcome: OfferOutcomeForm,

};

const currentSectionComponent = computed(() => {
  return sectionComponents[currentSection.value] || null;
});


const sectionStructure = computed(() => [
    {
        key: 'university',
        label: 'University Details',
        fieldLabels: ['Country Applying To', 'Major Applying For', 'Target Universities'],
        isMandatory: true,
    },
    {
        key: 'test_scores',
        label: 'Standardized Test Scores',
        fieldLabels: ['Language Test Details (e.g., IELTS/TOEFL Score, Date)', 'SAT/ACT Details (Score, Sections)'],
        isMandatory: false,
    },
    {
        key: 'activities_plans',
        label: 'Activities & Plans',
        fieldLabels: ['Academic Achievements / Activities', 'Non-Academic Activities / Interests', 'Holiday / Break Plans'],
        isMandatory: false,
    },
     {
        key: 'ig_subjects',
        label: 'IG Subjects & Scores',
        fieldLabels: [
            'IG Subject 1', 'IG Subject 2', 'IG Subject 3', 'IG Subject 4', 'IG Subject 5',
            'Official CIE Score 1', 'Official CIE Score 2', 'Official CIE Score 3', 'Official CIE Score 4', 'Official CIE Score 5',
        ],
        isMandatory: false,
    },
     {
        key: 'al_subjects',
        label: 'A Level Subjects & Scores',
        fieldLabels: [
            'A Level Subject 1', 'A Level Subject 2', 'A Level Subject 3',
             'Official CIE Score 6', 'Official CIE Score 7', 'Official CIE Score 8',
        ],
        isMandatory: false,
    },

    {
        key: 'offer_outcome',
        label: 'University Offer & Outcome',
        fieldLabels: ['University Offer Conditions', 'College Went'],
        isMandatory: false,
    },

]);


const sectionStatuses = computed(() => {
    const statuses = {};
    sectionStructure.value.forEach(section => {
        const sectionFields = fields.value.filter(field => section.fieldLabels.includes(field.label));
        statuses[section.key] = getStatusForSection(section, sectionFields);
    });
    return statuses;
});


function getStatusForSection(section, sectionFields) {
    let hasAnsweredAny = false;
    let allMandatoryAnswered = true;

    if (section.key === 'ig_subjects' || section.key === 'al_subjects') {
        const isIg = section.key === 'ig_subjects';
        const numSubjects = isIg ? 5 : 3;
        const scoreStartIndex = isIg ? 1 : 6;

        let hasAnyPairStarted = false;
        let hasIncompletePair = false;

        for (let i = 0; i < numSubjects; i++) {
            const subjectField = sectionFields.find(f => f.label === `${isIg ? 'IG' : 'A Level'} Subject ${i + 1}`);
            const scoreField = sectionFields.find(f => f.label === `Official CIE Score ${scoreStartIndex + i}`);

            const subjectAnswer = answers.value[subjectField?.id]?.trim() || '';
            const scoreAnswer = answers.value[scoreField?.id]?.trim() || '';

            if (subjectAnswer || scoreAnswer) {
                hasAnyPairStarted = true;
                if (!subjectAnswer || !scoreAnswer) {
                    hasIncompletePair = true;
                }
                 hasAnsweredAny = true;
            }
        }

        if (hasIncompletePair) {
            return 'incomplete';
        } else if (hasAnyPairStarted) {
             return 'complete';
        } else {
             return 'untouched';
        }
    }

    for (const field of sectionFields) {
        const answerValue = answers.value[field.id]?.trim() || '';

        if (answerValue) {
            hasAnsweredAny = true;
        }

        if (section.isMandatory && field.is_required && !answerValue) {
            allMandatoryAnswered = false;
        }
    }

    if (section.isMandatory) {
        return allMandatoryAnswered ? 'complete' : 'incomplete';
    } else {
        return hasAnsweredAny ? 'complete' : 'untouched';
    }
}


function getCurrentSectionFields() {
    const sectionKey = currentSection.value;
    if (sectionKey === 'dashboard') {
        return [];
    }
    const sectionDef = sectionStructure.value.find(s => s.key === sectionKey);
    if (!sectionDef) {
        console.error("Section definition not found for key:", sectionKey);
        return [];
    }
     return fields.value.filter(field => sectionDef.fieldLabels.includes(field.label));
}

function openSection(sectionKey) {
     const universityStatus = sectionStatuses.value['university'];
    if (universityStatus === 'incomplete' && sectionKey !== 'university') {
        showMandatoryPrompt.value = true;
        currentSection.value = 'university';
        return;
    }

    showMandatoryPrompt.value = false;
    currentSection.value = sectionKey;
}

async function handleSectionSaved(updatedAnswersSubset) {
    answers.value = { ...answers.value, ...updatedAnswersSubset };

    const universitySection = sectionStructure.value.find(s => s.key === 'university');
    const universityFields = fields.value.filter(field => universitySection.fieldLabels.includes(field.label));
    const isMandatoryCompleteAfterSave = getStatusForSection(universitySection, universityFields) === 'complete';

    if (currentSection.value === 'university' && isMandatoryCompleteAfterSave) {
         showMandatoryPrompt.value = false;
    }

    currentSection.value = 'dashboard';
}

function cancelSectionEdit() {
    currentSection.value = 'dashboard';
     if (sectionStatuses.value['university'] === 'incomplete') {
         showMandatoryPrompt.value = true;
     } else {
         showMandatoryPrompt.value = false;
     }
}

onMounted(async () => {
    try {
        const response = await axios.get('/api/profile');
        fields.value = response.data.fields;
        answers.value = response.data.answers || {};

        const universitySection = sectionStructure.value.find(s => s.key === 'university');
         const universityFields = fields.value.filter(field => universitySection.fieldLabels.includes(field.label));
        const isMandatoryCompleteOnInit = getStatusForSection(universitySection, universityFields) === 'complete';

        if (!isMandatoryCompleteOnInit) {
            showMandatoryPrompt.value = true;
            openSection('university');
        } else {
             currentSection.value = 'dashboard';
        }

    } catch (error) {
        console.error("Error fetching profile:", error);
    } finally {
        isLoading.value = false;
    }
});
</script>