<template>
    <div class="teacher-dashboard">
      <h1 class="mb-6 text-4xl font-bold tracking-tight text-gray-900">My Students</h1>

      <div class="p-4 mb-6 bg-gray-100 rounded-md shadow-sm">
          <h2 class="mb-4 text-xl font-semibold text-gray-800">Filter Students</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-3">
              <div>
                  <label for="studentNameFilter" class="block text-sm font-medium text-gray-700">Student Name</label>
                  <input type="text" id="studentNameFilter" v-model="filterName" placeholder="e.g. Ali" class="block px-3 py-2 mt-1 w-full rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"/>
              </div>
              <div>
                  <label for="yearGroupFilter" class="block text-sm font-medium text-gray-700">Year (e.g. 2024)</label>
                   <input type="text" id="yearGroupFilter" v-model="filterYearInput" placeholder="e.g. 2024" class="block px-3 py-2 mt-1 w-full rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"/>
              </div>
              <div class="relative">
                  <label for="academicClassFilter" class="block text-sm font-medium text-gray-700">Academic Class</label>
                   <input type="text" id="academicClassFilter" v-model="filterAcademicClass" @input="updateAcademicClassSuggestions" @focus="showAcademicClassSuggestions = true" @blur="hideAcademicClassSuggestions" placeholder="e.g. A2 Physics" class="block px-3 py-2 mt-1 w-full rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"/>
                   <ul v-if="showAcademicClassSuggestions && filteredAcademicClassSuggestions.length > 0" class="overflow-y-auto absolute z-10 mt-1 w-full max-h-60 bg-white rounded-md border border-gray-300 shadow-lg">
                       <li v-for="suggestion in filteredAcademicClassSuggestions" :key="suggestion" @mousedown.prevent="selectAcademicClassSuggestion(suggestion)" class="px-3 py-2 text-sm text-gray-800 cursor-pointer hover:bg-gray-100">{{ suggestion }}</li>
                   </ul>
              </div>
          </div>
          <div class="text-right">
              <button @click="applyFilters" :disabled="isLoading" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-black rounded-md border border-transparent shadow-sm hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed">{{ isLoading ? 'Filtering...' : 'Apply Filters' }}</button>
          </div>
      </div>

      <div v-if="isLoading" class="mt-8 text-center text-gray-500">Loading students...</div>
      <div v-else>
          <div v-if="students.length === 0" class="mt-8 text-center text-gray-500">No students found.</div>
          <ul v-else class="mt-4 student-list">
              <li v-for="student in students" :key="student.id" class="student-item">
                  <h3 @click="toggleStudent(student.id)" class="collapsible-header student-header">
                      <span>{{ student.name }}</span>
                      <span :class="{'rotate-180': isStudentExpanded(student.id)}" class="transition-transform">▼</span>
                  </h3>
                  <div v-if="isStudentExpanded(student.id)" class="answer-list">
                      <div v-if="getVisibleAnswers(student).length > 0">
                          <AnswerDisplay
                              v-for="answer in getVisibleAnswers(student)"
                              :key="answer.id"
                              :data-label="answer.profile_field.label"
                              :data="parseAnswerValue(answer.value)"
                          />
                      </div>
                      <div v-else class="no-answers">This student has not provided any profile information.</div>
                  </div>
              </li>
          </ul>
      </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AnswerDisplay from './AnswerDisplay.vue';

const isLoading = ref(true);
const students = ref([]);

const parseAnswerValue = (value) => {
    try {
        return JSON.parse(value);
    } catch (e) {
        return value;
    }
};

const hasVisibleContent = (answer) => {
    const val = answer.value;
    if (!val || val.trim() === '') return false;

    if (val.trim() === '[]' || val.trim() === '{}') return false;

    try {
        const parsed = JSON.parse(val);
        if (typeof parsed === 'object' && parsed !== null) {
            return Object.values(parsed).some(v => (Array.isArray(v) ? v.length > 0 : v !== null));
        }
    } catch(e) {
    }
    return true;
};

const getVisibleAnswers = (student) => {
    if (!student.answers) return [];
    return student.answers.filter(hasVisibleContent);
};

const filterName = ref('');
const filterYearInput = ref('');
const filterAcademicClass = ref('');
const academicClassSuggestions = ref([]);
const filteredAcademicClassSuggestions = ref([]);
const showAcademicClassSuggestions = ref(false);
const expandedStudents = ref({});

const applyFilters = async () => {
    isLoading.value = true;
    try {
        const params = {};
        if (filterName.value.trim()) params.student_name = filterName.value.trim();
        if (filterYearInput.value.trim()) params.year_group = 'Year ' + filterYearInput.value.trim();
        if (filterAcademicClass.value.trim()) params.academic_class = filterAcademicClass.value.trim();

        const response = await axios.get('/api/teacher/filter', { params });
        students.value = response.data;
        expandedStudents.value = {};
    } catch (error) {
        console.error("Failed to fetch students:", error);
        students.value = [];
    } finally {
        isLoading.value = false;
    }
};

const fetchAcademicClassNames = async () => {
     try {
        const response = await axios.get('/api/teacher');
        academicClassSuggestions.value = response.data
            .map(group => group.name)
            .filter(name => !name.startsWith('Year '))
            .sort();
     } catch (error) {
        console.error("Failed to fetch class group names:", error);
     }
};

const updateAcademicClassSuggestions = () => {
    const query = filterAcademicClass.value.trim().toLowerCase();
    if (!query) filteredAcademicClassSuggestions.value = academicClassSuggestions.value;
    else {
        filteredAcademicClassSuggestions.value = academicClassSuggestions.value.filter(name =>
            name.toLowerCase().includes(query)
        );
    }
};

const selectAcademicClassSuggestion = (suggestion) => {
    filterAcademicClass.value = suggestion;
    showAcademicClassSuggestions.value = false;
};

const hideAcademicClassSuggestions = () => { setTimeout(() => { showAcademicClassSuggestions.value = false; }, 100); };
const toggleStudent = (studentId) => { expandedStudents.value[studentId] = !expandedStudents.value[studentId]; };
const isStudentExpanded = (studentId) => !!expandedStudents.value[studentId];

onMounted(() => {
    fetchAcademicClassNames();
    applyFilters();
});
</script>

<style scoped>
.teacher-dashboard { padding: 1rem; max-width: 900px; margin: 0 auto; }
.student-list { list-style: none; padding: 0; margin: 0; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 0.75rem; overflow: hidden; background-color: rgba(255, 255, 255, 0.7); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
.student-item + .student-item { border-top: 1px solid rgba(0, 0, 0, 0.08); }
.collapsible-header { padding: 1rem 1.5rem; cursor: pointer; user-select: none; font-weight: 500; display: flex; justify-content: space-between; align-items: center; transition: background-color 0.2s ease-in-out; }
.student-header { background-color: rgba(245, 245, 245, 0.8); font-weight: 500; }
.student-header:hover { background-color: rgba(230, 230, 230, 0.9); }
.answer-list { padding: 1.5rem; background-color: rgba(255, 255, 255, 0.9); border-top: 1px solid rgba(0, 0, 0, 0.08); }
.no-answers { color: #6b7280; font-style: italic; }
.bg-gray-100 { background-color: #f3f4f6; }
.border-gray-300 { border-color: #d1d5db; }
.focus\:ring-gray-500:focus { --tw-ring-color: #1a2533; box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow); }
.focus\:border-gray-500:focus { border-color: #1a2533; }
.disabled\:opacity-50:disabled { opacity: 0.5; }
.disabled\:cursor-not-allowed:disabled { cursor: not-allowed; }
.relative { position: relative; }
.absolute { position: absolute; }
.z-10 { z-index: 10; }
.w-full { width: 100%; }
.bg-white { background-color: #fff; }
.border { border-width: 1px; }
.rounded-md { border-radius: 0.375rem; }
.shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
.mt-1 { margin-top: 0.25rem; }
.max-h-60 { max-height: 15rem; }
.overflow-y-auto { overflow-y: auto; }
.cursor-pointer { cursor: pointer; }
.hover\:bg-gray-100:hover { background-color: #1a253359; }
.text-gray-800 { color: #1f2937; }
.text-sm { font-size: 0.875rem; }
.px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
</style>