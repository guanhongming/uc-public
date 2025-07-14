<template>
    <div class="admin-dashboard">
      <h1>Admin Panel</h1>
      <nav class="admin-nav">
        <button @click="activeTab = 'users'" :class="{ active: activeTab === 'users' }">Manage Users</button>
        <button @click="activeTab = 'groups'" :class="{ active: activeTab === 'groups' }">Manage Groups</button>
        <button @click="activeTab = 'fields'" :class="{ active: activeTab === 'fields' }">Manage Form Fields</button>
      </nav>

      <main class="admin-content">
        <UserManagement v-if="activeTab === 'users'" />
        <GroupManagement v-if="activeTab === 'groups'" />
        <FieldManagement v-if="activeTab === 'fields'" />
      </main>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import UserManagement from './admin/UserManagement.vue';
  import GroupManagement from './admin/GroupManagement.vue';
  import FieldManagement from './admin/FieldManagement.vue';

  const activeTab = ref('users'); 
  </script>

  <style scoped>
  .admin-nav { margin-bottom: 2rem; border-bottom: 1px solid #ccc; padding-bottom: 1rem; }
  .admin-nav button { margin-right: 1rem; padding: 0.5rem 1rem; background: #eee; border: 1px solid #ccc; cursor: pointer; }
  .admin-nav button.active { background: #1a2533; color: white; border-color: #1a2533; }
  </style>