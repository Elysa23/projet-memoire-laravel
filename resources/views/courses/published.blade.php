@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Cours disponibles</h1>

    <form method="GET" action="{{ route('courses.published') }}" class="mb-6 flex flex-col sm:flex-row items-center gap-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par titre..."
           class="w-full sm:w-64 border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
        Rechercher
    </button>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('input[name="search"]');
    const form = searchInput.closest('form');
    if (searchInput && form) {
        searchInput.addEventListener('input', function () {
            if (this.value === '') {
                form.submit();
            }
        });
    }
});
</script>
</form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col justify-between h-full">
                @if(Auth::user() && Auth::user()->role === 'apprenant')
                    @php
                        $progress = $course->getProgress(Auth::user());
                        $progressColor = $progress >= 100 ? 'bg-green-500' : 'bg-blue-500';
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Progression</span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $progress }}%</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-200 rounded-full dark:bg-gray-700">
                            <div class="h-2.5 rounded-full transition-all duration-500 ease-in-out {{ $progressColor }}"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                @endif
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Miniature du cours" class="w-full h-40 object-cover rounded-t mb-4">
                @endif
                <div>
                    <h2 class="text-xl font-bold mb-2 dark:text-white">{{ $course->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($course->description, 100) }}</p>
                </div>
                <div class="flex justify-end items-center mt-4">
                    <a href="{{ route('courses.show', $course->id) }}" class="text-blue-600 hover:underline font-semibold">Voir</a>
                </div>
            </div>

            <!--Pagination-->
 
            
        @empty
            <div class="col-span-3 text-center text-gray-500">Aucun cours disponible pour le moment.</div>
        @endforelse
    </div>
</div>
<div class="mt-8">
    {{ $courses->withQueryString()->links() }}
</div>

@endsection