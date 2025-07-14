<template>
    <div>
      <h2>Class Group Management</h2>
      <div v-for="group in classGroups" :key="group.id" class="group-card">
        <h3>{{ group.name }}</h3>
        <p>Assign users to this group:</p>


        <select multiple v-model="group.selectedUserIds" @change="updateGroupMembers(group)">
          <option v-for="user in allUsers" :key="user.id" :value="user.id">
            {{ user.name }} ({{ user.role }})
          </option>
        </select>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';

  const classGroups = ref([]);
  const allUsers = ref([]);

  async function fetchData() {
    // Fetch both groups and users in parallel
    const [groupsRes, usersRes] = await Promise.all([
      axios.get('/api/admin/class-groups'),
      axios.get('/api/admin/users')
    ]);


    classGroups.value = groupsRes.data.map(group => ({
      ...group,
      selectedUserIds: group.users.map(user => user.id)
    }));

    allUsers.value = usersRes.data;
  }

  async function updateGroupMembers(group) {
    await axios.put(`/api/admin/class-groups/${group.id}`, {
      userIds: group.selectedUserIds
    });
    alert(`Group ${group.name} updated!`);
  }

  onMounted(fetchData);
  </script>

  <style scoped>
  .group-card { border: 1px solid #ccc; padding: 1rem; margin-bottom: 1rem; border-radius: 8px; }
  select[multiple] { width: 100%; height: 200px; }
  </style>