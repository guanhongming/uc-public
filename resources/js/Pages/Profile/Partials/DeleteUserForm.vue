<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

// This will hold our generic failure message.
const customErrorMessage = ref('');

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    // Clear any previous custom error messages before making a new request.
    customErrorMessage.value = '';
try{
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: (errors) => {
            // First, check if the error is specifically for the password field.
            if (errors.password) {
                // If it is, handle it normally by focusing the input.
                passwordInput.value.focus();
                form.reset('password');
            } else {
                // If the error is ANYTHING else (e.g., server error, permission denied),
                // display our generic message.
                customErrorMessage.value = "Your account type isn't allowed to be deleted. Please contact admin.";
                // Also clear the password field.
                form.reset('password');
            }
        },
        onFinish: () => {
            // The form fields are reset inside the onError/onSuccess callbacks now,
            // so we can leave this empty or remove it.
        },
    });}catch(error){customErrorMessage.value = "Your account type isn't allowed to be deleted. Please contact admin.";
                // Also clear the password field.
                form.reset('password');}finally{}
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    customErrorMessage.value = ''; // Clear custom error on close
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                your account, please download any data or information that you wish to retain.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block mt-1 w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <!-- This handles the standard password validation error from the backend -->
                    <InputError :message="form.errors.password" class="mt-2" />

                    <!-- This displays our new generic error message for any other failure -->
                    <p v-if="customErrorMessage" class="mt-2 text-sm text-red-600">
                        {{ customErrorMessage }}
                    </p>
                </div>

                <div class="flex justify-end mt-6">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>