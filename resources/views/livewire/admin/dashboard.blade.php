<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>

        <h2 class="text-2xl font-bold text-gray-800">
            Dashboard
        </h2>
    </x-slot:pageHeader>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Total Students Card - Chart Style -->
        <div class="relative flex items-center justify-between p-6 bg-white border shadow-md overflow-hidden">
            <!-- Background Chart Lines -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 200 100">
                    <!-- Grid Lines -->
                    <defs>
                        <pattern id="grid-students" width="20" height="20" patternUnits="userSpaceOnUse">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="#3B82F6" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-students)"/>
                    <!-- Trending Line -->
                    <path d="M20,80 Q60,40 100,30 T180,20" stroke="#3B82F6" stroke-width="2" fill="none"/>
                </svg>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Students</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
                <!-- Mini Bar Chart -->
                <div class="flex items-end space-x-1 mt-2">
                    <div class="w-2 h-3 bg-blue-300 rounded-sm"></div>
                    <div class="w-2 h-5 bg-blue-400 rounded-sm"></div>
                    <div class="w-2 h-4 bg-blue-500 rounded-sm"></div>
                    <div class="w-2 h-6 bg-blue-600 rounded-sm"></div>
                    <div class="w-2 h-2 bg-blue-300 rounded-sm"></div>
                </div>
            </div>
            
            <!-- Circular Progress -->
            <div class="relative">
                <svg class="w-16 h-16 transform -rotate-90">
                    <circle cx="32" cy="32" r="28" stroke="#E5E7EB" stroke-width="4" fill="transparent"/>
                    <circle cx="32" cy="32" r="28" stroke="#3B82F6" stroke-width="4" fill="transparent"
                            stroke-dasharray="175" stroke-dashoffset="43.75" stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Teachers Card - Pie Chart Style -->
        <div class="relative flex items-center justify-between p-6 bg-white border shadow-md overflow-hidden">
            <!-- Background Pie Chart -->
            <div class="absolute top-4 right-4 opacity-20">
                <svg class="w-20 h-20">
                    <circle cx="40" cy="40" r="35" fill="#10B981" stroke="#ffffff" stroke-width="2"/>
                    <path d="M 40,5 A 35,35 0 0,1 68,25 L 40,40 Z" fill="#059669"/>
                    <path d="M 68,25 A 35,35 0 0,1 68,55 L 40,40 Z" fill="#065F46"/>
                    <path d="M 68,55 A 35,35 0 0,1 12,55 L 40,40 Z" fill="#047857"/>
                    <path d="M 12,55 A 35,35 0 0,1 40,5 L 40,40 Z" fill="#10B981"/>
                </svg>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Teachers</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalTeachers }}</p>
                <!-- Performance Indicators -->
                <div class="flex space-x-2 mt-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="w-3 h-3 bg-green-400 rounded-full"></span>
                    <span class="w-3 h-3 bg-green-300 rounded-full"></span>
                </div>
            </div>
            
            <!-- Semi-circle gauge -->
            <div class="relative">
                <svg class="w-16 h-16">
                    <path d="M 8,48 A 24,24 0 0,1 56,48" stroke="#E5E7EB" stroke-width="4" fill="none"/>
                    <path d="M 8,48 A 24,24 0 0,1 44,20" stroke="#10B981" stroke-width="4" fill="none" stroke-linecap="round"/>
                </svg>
                <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2">
                    <i class="fas fa-chalkboard-teacher text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Classes Card - Area Chart Style -->
        <div class="relative flex items-center justify-between p-6 bg-white border shadow-md overflow-hidden">
            <!-- Background Area Chart -->
            <div class="absolute inset-0 opacity-15">
                <svg class="w-full h-full" viewBox="0 0 200 100">
                    <!-- Area Chart -->
                    <defs>
                        <linearGradient id="areaGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#F59E0B;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#F59E0B;stop-opacity:0.1" />
                        </linearGradient>
                    </defs>
                    <path d="M0,80 Q50,60 100,50 T200,40 L200,100 L0,100 Z" fill="url(#areaGradient)"/>
                    <path d="M0,80 Q50,60 100,50 T200,40" stroke="#F59E0B" stroke-width="2" fill="none"/>
                </svg>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Classes</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalClasses }}</p>
                <!-- Data Points -->
                <div class="flex items-center space-x-1 mt-2">
                    <div class="w-1 h-8 bg-yellow-200 rounded"></div>
                    <div class="w-1 h-6 bg-yellow-300 rounded"></div>
                    <div class="w-1 h-10 bg-yellow-400 rounded"></div>
                    <div class="w-1 h-7 bg-yellow-500 rounded"></div>
                    <div class="w-1 h-9 bg-yellow-600 rounded"></div>
                    <div class="w-1 h-5 bg-yellow-400 rounded"></div>
                </div>
            </div>
            
            <!-- Hexagon Shape -->
            <div class="relative">
                <svg class="w-16 h-16">
                    <polygon points="32,6 48,18 48,42 32,54 16,42 16,18" 
                             fill="#F59E0B" fill-opacity="0.2" stroke="#F59E0B" stroke-width="2"/>
                    <polygon points="32,12 42,20 42,36 32,44 22,36 22,20" 
                             fill="#F59E0B" fill-opacity="0.4"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-school text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Subjects Card - Radar Chart Style -->
        <div class="relative flex items-center justify-between p-6 bg-white border shadow-md overflow-hidden">
            <!-- Background Radar Chart -->
            <div class="absolute inset-0 opacity-15">
                <svg class="w-full h-full" viewBox="0 0 200 100">
                    <!-- Radar Grid -->
                    <g transform="translate(100, 50)">
                        <!-- Outer rings -->
                        <circle cx="0" cy="0" r="40" fill="none" stroke="#8B5CF6" stroke-width="1"/>
                        <circle cx="0" cy="0" r="30" fill="none" stroke="#8B5CF6" stroke-width="1"/>
                        <circle cx="0" cy="0" r="20" fill="none" stroke="#8B5CF6" stroke-width="1"/>
                        <!-- Radar lines -->
                        <line x1="0" y1="-40" x2="0" y2="40" stroke="#8B5CF6" stroke-width="1"/>
                        <line x1="-40" y1="0" x2="40" y2="0" stroke="#8B5CF6" stroke-width="1"/>
                        <line x1="-28" y1="-28" x2="28" y2="28" stroke="#8B5CF6" stroke-width="1"/>
                        <line x1="28" y1="-28" x2="-28" y2="28" stroke="#8B5CF6" stroke-width="1"/>
                        <!-- Data area -->
                        <polygon points="0,-35 25,-25 30,20 -10,30 -35,-10" fill="#8B5CF6" fill-opacity="0.3"/>
                    </g>
                </svg>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Subjects</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalSubjects }}</p>
                <!-- Subject Categories -->
                <div class="flex flex-wrap gap-1 mt-2">
                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                    <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                    <span class="w-2 h-2 bg-purple-600 rounded-full"></span>
                    <span class="w-2 h-2 bg-purple-300 rounded-full"></span>
                </div>
            </div>
            
            <!-- Diamond Shape -->
            <div class="relative">
                <svg class="w-16 h-16">
                    <path d="M32,4 L56,32 L32,60 L8,32 Z" 
                          fill="#8B5CF6" fill-opacity="0.2" stroke="#8B5CF6" stroke-width="2"/>
                    <path d="M32,12 L44,32 L32,52 L20,32 Z" 
                          fill="#8B5CF6" fill-opacity="0.4"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-book text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>
</div>