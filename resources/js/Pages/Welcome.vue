<template>
    <div class="flex overflow-hidden relative justify-center items-center min-h-screen bg-slate-50">
      <div class="absolute -left-48 top-1/4 w-96 h-96 rounded-full blur-3xl bg-sky-500/20"></div>
      <div class="absolute -right-48 bottom-1/4 w-96 h-96 rounded-full blur-3xl bg-indigo-500/20"></div>
      <div class="relative px-6 py-16 w-full max-w-2xl text-center">
        <div class="flex justify-center mb-8">
          <svg class="w-auto h-16 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 10v6M2 10l10-5 10 5-10 5L2 10z"></path>
            <path d="M6 12v5c0 1.66 4 3 6 3s6-1.34 6-3v-5"></path>
          </svg>
        </div>
        <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-6xl">
           Uni Application
        </h1>
        <h1 class="text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-500 drop-shadow-lg transition-transform duration-300 ease-in-out sm:text-6xl">
   One-Stop Shop
</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">
            University applications made easy.
        </p>
        <div class="flex flex-col gap-4 justify-center items-center mt-10 sm:flex-row sm:gap-x-6">
          <a
            href="/register"
            class="px-8 py-3 w-full text-base font-semibold text-white bg-gray-900 rounded-md shadow-lg transition-colors duration-200 hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 sm:w-auto"
          >
            Sign Up
          </a>
          <a
            href="/login"
            class="px-8 py-3 w-full text-base font-semibold leading-6 text-gray-600 rounded-md transition-colors duration-200 group hover:bg-gray-100/60 sm:w-auto"
          >
            Log In <span class="ml-1 transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
          </a>

        </div>
      </div>
    </div>
  </template>
  <script setup>
  </script>
  <style scoped>
  </style>
