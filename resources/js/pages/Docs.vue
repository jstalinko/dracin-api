<script setup>
import { ref } from 'vue'
import DocCard from '@/components/DocCard.vue';

const apiDocs = ref([
  {
    method: 'GET',
    title: 'Get Drama List',
    endpoint: 'https://api.example.com/v1/dramas',
    description: 'Mendapatkan daftar drama dari berbagai sumber seperti dramabx, netshrt, dan dramawve.',
    authorization: {
      token: 'YOUR_API_KEY'
    },
    queryParams: [
      {
        name: 'source',
        type: 'string',
        required: false,
        description: 'Filter berdasarkan sumber (dramabx, netshrt, dramawve)',
        default: 'all'
      },
      {
        name: 'page',
        type: 'integer',
        required: false,
        description: 'Nomor halaman untuk pagination',
        default: '1'
      },
      {
        name: 'limit',
        type: 'integer',
        required: false,
        description: 'Jumlah data per halaman',
        default: '20'
      }
    ],
    exampleRequest: {
      curl: `curl -X GET "https://api.example.com/v1/dramas?source=dramabx&page=1" \\
  -H "Authorization: Bearer YOUR_API_KEY"`,
      php: `<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.example.com/v1/dramas?source=dramabx");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer YOUR_API_KEY'
]);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`
    },
    exampleResponse: {
      success: {
        status: 'success',
        data: [
          {
            id: 1,
            title: 'Love Between Fairy and Devil',
            source: 'dramabx',
            year: 2022,
            episodes: 36
          }
        ],
        pagination: {
          page: 1,
          limit: 20,
          total: 150
        }
      },
      error: {
        status: 401,
        message: 'Unauthorized. Invalid API key.'
      }
    }
  },
  {
    method: 'GET',
    title: 'Get Drama Details',
    endpoint: 'https://api.example.com/v1/dramas/{id}',
    description: 'Mendapatkan detail lengkap dari sebuah drama berdasarkan ID.',
    authorization: {
      token: 'YOUR_API_KEY'
    },
    queryParams: [],
    exampleRequest: {
      curl: `curl -X GET "https://api.example.com/v1/dramas/123" \\
  -H "Authorization: Bearer YOUR_API_KEY"`,
      php: `<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.example.com/v1/dramas/123");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer YOUR_API_KEY'
]);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`
    },
    exampleResponse: {
      success: {
        status: 'success',
        data: {
          id: 123,
          title: 'Love Between Fairy and Devil',
          source: 'dramabx',
          year: 2022,
          episodes: 36,
          genre: ['Fantasy', 'Romance'],
          rating: 8.9,
          synopsis: 'A story about...'
        }
      },
      error: {
        status: 404,
        message: 'Drama not found.'
      }
    }
  }
])
</script>

<template>
  <div class="min-h-screen bg-gray-950">
    <!-- Background Grid Pattern -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)]"></div>

    <div class="relative max-w-6xl mx-auto px-6 py-12">
      <!-- Header -->
      <div class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">API Documentation</h1>
        <p class="text-gray-400 text-lg">Panduan lengkap untuk menggunakan Drama Cina API</p>
      </div>

      <!-- Documentation Cards -->
      <div class="space-y-6">
        <DocCard 
          v-for="(doc, index) in apiDocs" 
          :key="index" 
          :details="doc" 
        />
      </div>

      <!-- Footer Info -->
      <div class="mt-12 border border-gray-800 rounded-2xl bg-gray-900/50 backdrop-blur-sm p-6">
        <h3 class="text-lg font-semibold text-white mb-3">Need Help?</h3>
        <p class="text-gray-400 mb-4">
          Jika Anda mengalami masalah atau memiliki pertanyaan, jangan ragu untuk menghubungi kami.
        </p>
        <div class="flex gap-4">
          <a href="#" class="text-indigo-400 hover:text-indigo-300 transition-colors">
            Email Support
          </a>
          <a href="#" class="text-indigo-400 hover:text-indigo-300 transition-colors">
            GitHub Issues
          </a>
        </div>
      </div>
    </div>
  </div>
</template>