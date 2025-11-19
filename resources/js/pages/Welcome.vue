<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-6 py-12">
    <!-- Background Grid Pattern -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_70%,transparent_110%)]"></div>
    
    <!-- Content Card -->
    <div class="relative max-w-2xl w-full">
      <div class="border border-gray-800 rounded-2xl bg-gray-900/50 backdrop-blur-sm p-8 md:p-12 shadow-2xl animate-fade-in">
        <!-- Glow Effect -->
        <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur opacity-20 animate-pulse-slow"></div>
        
        <div class="relative text-center">
          <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 animate-slide-up">
            Drama Cina API
          </h1>
          <p class="text-gray-400 text-base md:text-lg mb-8 animate-slide-up animation-delay-100">
            Akses dramabx, netshrt, dramawve, dan lain-nya dalam satu request API
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up animation-delay-200">
            <a
              href="#apikey"
              class="group relative bg-indigo-600 text-white px-8 py-4 rounded-xl font-semibold overflow-hidden transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/50 hover:scale-105"
            >
              <span class="relative z-10">Get Apikey</span>
              <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
            <a
              href="#docs"
              class="group relative border border-gray-700 text-gray-300 px-8 py-4 rounded-xl font-semibold overflow-hidden transition-all duration-300 hover:border-gray-600 hover:text-white hover:shadow-lg hover:shadow-gray-700/50 hover:scale-105"
            >
              <span class="relative z-10">Documentation</span>
              <div class="absolute inset-0 bg-gray-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  <style scoped>
  @keyframes fade-in {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  @keyframes slide-up {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes pulse-slow {
    0%, 100% {
      opacity: 0.2;
    }
    50% {
      opacity: 0.3;
    }
  }

  .animate-fade-in {
    animation: fade-in 0.8s ease-out;
  }

  .animate-slide-up {
    animation: slide-up 0.6s ease-out;
    animation-fill-mode: both;
  }

  .animation-delay-100 {
    animation-delay: 0.1s;
  }

  .animation-delay-200 {
    animation-delay: 0.2s;
  }

  .animate-pulse-slow {
    animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  </style>
