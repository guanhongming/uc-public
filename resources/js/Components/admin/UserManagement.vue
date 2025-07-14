<template>
    <div>
      <h2>User Management</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <select v-model="user.role" @change="updateRole(user)">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
              </select>
            </td>
            <td><button @click="deleteUser(user.id)" class="delete-btn">Delete</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';

  const users = ref([]);

  async function fetchUsers() {
    const response = await axios.get('/api/admin/users');
    users.value = response.data;
  }

  async function updateRole(user) {
    await axios.put(`/api/admin/users/${user.id}`, { role: user.role });
  }

  async function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This cannot be undone.')) {
      await axios.delete(`/api/admin/users/${userId}`);
      await fetchUsers();
    }
  }
  onMounted(fetchUsers);
  </script>

  <style scoped>
   
  </style>