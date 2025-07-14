<template>
    <div class="field-management-container">
      <h2>Manage Application Form Fields</h2>
      <p class="description">Add, remove, and review the questions that will appear on the student profile form.</p>

      <div class="card add-field-form">
        <h3>Add New Field</h3>
        <form @submit.prevent="addField">
          <div class="form-group">
            <label for="label">Field Label</label>
            <input
              id="label"
              type="text"
              v-model="newField.label"
              placeholder="e.g., Extracurricular Activities"
              required
            />
          </div>
          <div class="form-group">
            <label for="field_type">Field Type</label>
            <select id="field_type" v-model="newField.field_type" required>
              <option value="text">Single Line Text</option>
              <option value="textarea">Multi-line Text (Textarea)</option>
            </select>
          </div>
          <button type="submit" class="btn-primary" :disabled="isSubmitting">
            {{ isSubmitting ? 'Adding...' : 'Add Field' }}
          </button>
        </form>
      </div>

      <div class="card existing-fields-list">
        <h3>Existing Fields</h3>
        <div v-if="isLoading" class="loading">Loading fields...</div>
        <div v-else-if="fields.length === 0" class="no-fields">No fields have been created yet.</div>
        <ul v-else>
          <li v-for="field in fields" :key="field.id">
            <div class="field-info">
              <span class="field-label">{{ field.label }}</span>
              <span class="field-type">{{ field.field_type }}</span>
            </div>
            <button @click="deleteField(field.id)" class="btn-danger">Delete</button>
          </li>
        </ul>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, reactive, onMounted } from 'vue';
  import axios from 'axios';

  const fields = ref([]);
  const isLoading = ref(true);
  const isSubmitting = ref(false);

  const newField = reactive({
    label: '',
    field_type: 'text',
  });

  async function fetchFields() {
    isLoading.value = true;
    try {
      const response = await axios.get('/api/admin/profile-fields');
      fields.value = response.data;
    } catch (error) {
      console.error("Error fetching profile fields:", error);
      alert('Could not load form fields. Please check the console.');
    } finally {
      isLoading.value = false;
    }
  }

  async function addField() {
    isSubmitting.value = true;
    try {
      await axios.post('/api/admin/profile-fields', newField);

      newField.label = '';
      newField.field_type = 'text';

      await fetchFields();
    } catch (error) {
      if (error.response && error.response.status === 422) {
        alert("Validation Error: " + Object.values(error.response.data.errors).join('\n'));
      } else {
        console.error("Error adding field:", error);
        alert('An error occurred while adding the field.');
      }
    } finally {
      isSubmitting.value = false;
    }
  }

  async function deleteField(fieldId) {
    if (!confirm('Are you sure you want to delete this field? All student answers for it will be permanently lost.')) {
      return;
    }

    try {
      await axios.delete(`/api/admin/profile-fields/${fieldId}`);
      await fetchFields();
    } catch (error) {
      console.error("Error deleting field:", error);
      alert('An error occurred while deleting the field.');
    }
  }

  onMounted(fetchFields);
  </script>

  <style scoped>
  .field-management-container { max-width: 800px; margin: auto; }
  .description { color: #666; margin-bottom: 2rem; }
  .card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 2rem; }
  .form-group { margin-bottom: 1rem; }
  label { display: block; font-weight: bold; margin-bottom: 0.5rem; }
  input, select { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; }
  ul { list-style: none; padding: 0; }
  li { display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #eee; }
  li:last-child { border-bottom: none; }
  .field-info { display: flex; flex-direction: column; }
  .field-label { font-weight: 500; }
  .field-type { font-size: 0.8rem; color: #777; background: #f0f0f0; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; }
  .loading, .no-fields { text-align: center; color: #777; padding: 2rem; }

  /* Button Styles */
  button { padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
  .btn-primary { background-color: #333; color: white; }
  .btn-primary:disabled { background-color: #aaa; cursor: not-allowed; }
  .btn-danger { background-color: #ef4444; color: white; }
  </style>