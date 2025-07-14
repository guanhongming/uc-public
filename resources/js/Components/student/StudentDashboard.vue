<template>
    <div class="profile-dashboard">
        <h1 class="mb-8 text-3xl font-bold tracking-tight text-center text-gray-900 sm:text-4xl">
            My University Application Profile
        </h1>
        <p class="mt-2 mb-10 text-lg text-center text-gray-600">
            Click on a section below to view or edit your information.
        </p>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <button
                v-for="section in sections"
                :key="section.key"
                @click="$emit('open-section', section.key)"
                class="relative p-6 text-left rounded-xl shadow-md transition-all duration-300 ease-in-out section-card hover:shadow-xl hover:-translate-y-1"
                 :class="{
                     'bg-green-50': statuses[section.key] === 'complete',
                     'bg-yellow-50': statuses[section.key] === 'incomplete',
                     'bg-gray-50': statuses[section.key] === 'untouched'
                 }"
            >
                <div class="absolute top-4 right-4 w-3 h-3 rounded-full"
                     :class="{
                         'bg-green-500': statuses[section.key] === 'complete',
                         'bg-yellow-400': statuses[section.key] === 'incomplete',
                         'bg-gray-400': statuses[section.key] === 'untouched'
                     }">
                </div>

                <div>
                    <h2 class="pr-6 text-xl font-bold text-gray-900">{{ section.label }}</h2>
                    <p v-if="section.isMandatory" class="mt-1 text-sm italic text-gray-500">(Mandatory)</p>
                </div>

            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    sections: {
        type: Array,
        required: true,
    },
    statuses: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['open-section']);

</script>

<style scoped>
.section-card {
    min-height: 150px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    position: relative;
    cursor: pointer;
}

.bg-green-50 { background-color: #f0fdf4; }
.bg-yellow-50 { background-color: #fefce8; }
.bg-gray-50 { background-color: #f9fafb; }

.border-green-600 { border-color: #059669; }
.border-yellow-600 { border-color: #d97706; }
.border-gray-400 { border-color: #9ca3af; }

.grid {
    display: grid;
}
.grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
@media (min-width: 768px) {
  .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (min-width: 1024px) {
  .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
.gap-6 { gap: 1.5rem; }

.rounded-xl { border-radius: 0.75rem; }
.shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
.p-6 { padding: 1.5rem; }
.text-left { text-align: left; }
.text-gray-900 { color: #111827; }
.text-gray-800 { color: #1f2937; }
.text-gray-600 { color: #4b5563; }
.text-gray-500 { color: #6b7280; }
.text-xl { font-size: 1.25rem; }
.text-lg { font-size: 1.125rem; }
.font-bold { font-weight: 700; }
.italic { font-style: italic; }
.mb-8 { margin-bottom: 2rem; }
.mb-10 { margin-bottom: 2.5rem; }
.pr-6 { padding-right: 1.5rem; }
.mt-1 { margin-top: 0.25rem; }


.transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 300ms; }
.duration-300 { transition-duration: 300ms; }
.ease-in-out { transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); }

.hover\:-translate-y-1:hover {
    --tw-translate-y: -0.25rem;
    transform: translateX(var(--tw-translate-x, 0)) translateY(var(--tw-translate-y, 0)) rotate(var(--tw-rotate, 0)) skewX(var(--tw-skew-x, 0)) skewY(var(--tw-skew-y, 0)) scaleX(var(--tw-scale-x, 1)) scaleY(var(--tw-scale-y, 1));
}
.hover\:shadow-xl:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>