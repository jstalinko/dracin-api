<script setup>
import { ref } from 'vue'

const props = defineProps({
  details: {
    type: Object,
    required: true
  }
})

const copiedIndex = ref(null)
const isExpanded = ref(false)

const copyToClipboard = (text, index) => {
  navigator.clipboard.writeText(text)
  copiedIndex.value = index
  setTimeout(() => {
    copiedIndex.value = null
  }, 2000)
}

const toggleExpand = () => {
  isExpanded.value = !isExpanded.value
}
</script>

<template>
  <div class="border border-gray-800 rounded-2xl bg-gray-900/50 backdrop-blur-sm p-6 mb-6 hover:border-gray-700 transition-all">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center gap-3 mb-3">
        <span 
          :class="[
            'px-3 py-1 rounded-lg text-xs font-semibold',
            details.method === 'GET' ? 'bg-green-500/20 text-green-400' :
            details.method === 'POST' ? 'bg-blue-500/20 text-blue-400' :
            'bg-purple-500/20 text-purple-400'
          ]"
        >
          {{ details.method }}
        </span>
        <h3 class="text-xl font-bold text-white">{{ details.title }}</h3>
      </div>
      <code class="text-indigo-400 text-sm bg-gray-950 px-3 py-2 rounded-lg inline-block">
        {{ details.endpoint }}
      </code>
      <p v-if="details.description" class="text-gray-400 mt-3">{{ details.description }}</p>
    </div>

    <!-- Authorization -->
    <div v-if="details.authorization" class="mb-6">
      <h4 class="text-sm font-semibold text-gray-300 mb-3 flex items-center gap-2">
        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
        Authorization
      </h4>
      <div class="bg-gray-950 border border-gray-800 rounded-xl p-4">
        <p class="text-sm text-gray-400 mb-2">Header:</p>
        <code class="text-indigo-400 text-sm">
          Authorization: Bearer {{ details.authorization.token || 'YOUR_API_KEY' }}
        </code>
      </div>
    </div>

    <!-- Query Parameters -->
    <div v-if="details.queryParams && details.queryParams.length > 0" class="mb-6">
      <h4 class="text-sm font-semibold text-gray-300 mb-3 flex items-center gap-2">
        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
        Query Parameters
      </h4>
      <div class="space-y-3">
        <div 
          v-for="(param, idx) in details.queryParams" 
          :key="idx" 
          class="bg-gray-950 border border-gray-800 rounded-xl p-4"
        >
          <div class="flex items-center gap-2 mb-2">
            <code class="text-indigo-400 font-semibold">{{ param.name }}</code>
            <span v-if="param.required" class="text-xs text-red-400 bg-red-500/20 px-2 py-0.5 rounded">
              required
            </span>
            <span v-else class="text-xs text-gray-500 bg-gray-700/30 px-2 py-0.5 rounded">
              optional
            </span>
            <span class="text-xs text-gray-500 ml-auto">{{ param.type }}</span>
          </div>
          <p class="text-sm text-gray-400">{{ param.description }}</p>
          <p v-if="param.default" class="text-xs text-gray-500 mt-2">
            Default: <code class="text-indigo-400">{{ param.default }}</code>
          </p>
        </div>
      </div>
    </div>

    <!-- Example Request -->
    <div v-if="details.exampleRequest" class="mb-6">
      <h4 class="text-sm font-semibold text-gray-300 mb-3 flex items-center gap-2">
        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
        Example Request
      </h4>
      
      <!-- cURL Example -->
      <div v-if="details.exampleRequest.curl" class="mb-4">
        <div class="relative group">
          <button
            @click="copyToClipboard(details.exampleRequest.curl, 'curl')"
            class="absolute top-3 right-3 z-10 p-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
          >
            <svg v-if="copiedIndex === 'curl'" class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg v-else class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
          </button>
          <div class="bg-gray-950 border border-gray-800 rounded-xl p-4 overflow-x-auto">
            <div class="text-xs text-gray-500 mb-2 uppercase">cURL</div>
            <pre class="text-sm text-gray-300"><code>{{ details.exampleRequest.curl }}</code></pre>
          </div>
        </div>
      </div>

      <!-- PHP Example -->
      <div v-if="details.exampleRequest.php" class="mb-4">
        <div class="relative group">
          <button
            @click="copyToClipboard(details.exampleRequest.php, 'php')"
            class="absolute top-3 right-3 z-10 p-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
          >
            <svg v-if="copiedIndex === 'php'" class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg v-else class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
          </button>
          <div class="bg-gray-950 border border-gray-800 rounded-xl p-4 overflow-x-auto">
            <div class="text-xs text-gray-500 mb-2 uppercase">PHP</div>
            <pre class="text-sm text-gray-300"><code>{{ details.exampleRequest.php }}</code></pre>
          </div>
        </div>
      </div>
    </div>

    <!-- Example Response -->
    <div v-if="details.exampleResponse" class="mb-6">
      <h4 class="text-sm font-semibold text-gray-300 mb-3 flex items-center gap-2">
        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
        Example Response
      </h4>

      <!-- Success Response -->
      <div v-if="details.exampleResponse.success" class="mb-4">
        <div class="relative group">
          <button
            @click="copyToClipboard(JSON.stringify(details.exampleResponse.success, null, 2), 'success')"
            class="absolute top-3 right-3 z-10 p-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
          >
            <svg v-if="copiedIndex === 'success'" class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg v-else class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
          </button>
          <div class="bg-gray-950 border border-green-800/30 rounded-xl p-4 overflow-x-auto">
            <div class="text-xs text-green-400 mb-2 uppercase flex items-center gap-2">
              <span class="w-2 h-2 bg-green-400 rounded-full"></span>
              Success (200)
            </div>
            <pre class="text-sm text-gray-300"><code>{{ JSON.stringify(details.exampleResponse.success, null, 2) }}</code></pre>
          </div>
        </div>
      </div>

      <!-- Error Response -->
      <div v-if="details.exampleResponse.error" class="mb-4">
        <div class="relative group">
          <button
            @click="copyToClipboard(JSON.stringify(details.exampleResponse.error, null, 2), 'error')"
            class="absolute top-3 right-3 z-10 p-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
          >
            <svg v-if="copiedIndex === 'error'" class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg v-else class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
          </button>
          <div class="bg-gray-950 border border-red-800/30 rounded-xl p-4 overflow-x-auto">
            <div class="text-xs text-red-400 mb-2 uppercase flex items-center gap-2">
              <span class="w-2 h-2 bg-red-400 rounded-full"></span>
              Error ({{ details.exampleResponse.error.status || 400 }})
            </div>
            <pre class="text-sm text-gray-300"><code>{{ JSON.stringify(details.exampleResponse.error, null, 2) }}</code></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>