<script setup>
import { ref } from 'vue'

const features = ref([
  { title: '🎭 Koleksi Drama Cina', desc: 'Akses ribuan drama Cina terbaru dan terpopuler secara gratis.' },
  { title: '⚡ API Cepat & Stabil', desc: 'Didesain untuk performa tinggi dengan response JSON ringan.' },
  { title: '🔍 Filter & Cari', desc: 'Temukan drama favorit berdasarkan genre, tahun, atau rating.' },
])
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-indigo-50 to-white flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-indigo-600">Drama Cina API</h1>
        <nav class="space-x-4">
          <a href="#features" class="text-gray-700 hover:text-indigo-600">Fitur</a>
          <a href="#docs" class="text-gray-700 hover:text-indigo-600">Dokumentasi</a>
          <a href="https://github.com/" class="text-gray-700 hover:text-indigo-600">GitHub</a>
        </nav>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col items-center justify-center text-center px-6">
      <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
        Selamat Datang di <span class="text-indigo-600">Drama Cina API</span>
      </h2>
      <p class="text-gray-600 max-w-2xl mb-6">
        Platform sederhana untuk mengakses data drama Cina terbaru dalam format JSON.  
        Ideal untuk developer yang ingin membuat aplikasi streaming, katalog, atau info drama.
      </p>
      <a
        href="#docs"
        class="bg-indigo-600 text-white px-6 py-3 rounded-xl shadow hover:bg-indigo-700 transition-all"
      >
        Mulai Sekarang
      </a>
    </section>

    <!-- Features -->
    <section id="features" class="py-16 bg-white">
      <div class="max-w-5xl mx-auto px-6">
        <h3 class="text-2xl font-bold text-center text-gray-800 mb-8">Fitur Utama</h3>
        <div class="grid md:grid-cols-3 gap-6">
          <div
            v-for="(f, i) in features"
            :key="i"
            class="p-6 border rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h4 class="text-lg font-semibold text-indigo-600 mb-2">{{ f.title }}</h4>
            <p class="text-gray-600 text-sm">{{ f.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="text-center text-gray-500 py-6 border-t mt-auto text-sm">
      © {{ new Date().getFullYear() }} Drama Cina API | JavaraDigital
    </footer>
  </div>
</template>
