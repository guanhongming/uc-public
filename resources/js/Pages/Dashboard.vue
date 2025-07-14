<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import StudentProfile from '@/Components/ProfileForm.vue';
import TeacherView from '@/Components/TeacherDashboard.vue';
import AdminView from '@/Components/AdminDashboard.vue';
import Overview from '@/Components/Overview.vue';
const page = usePage();
const userRole = page.props.auth.user.role;
const isLoading = ref(false);
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="frosted-glass-container">
                    <div class="p-6 text-gray-900">
                    <Overview
                        v-if="userRole === 'student'"
                    />
                    <TeacherView
                        v-if="userRole === 'teacher'"
                    />
                    <AdminView
                        v-if="userRole === 'admin'"
                    />
        </div></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.frosted-glass-container {
    background-color: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}
</style>
