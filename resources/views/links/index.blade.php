<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Links') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Search & Filter Form -->
                    <form action="{{ route('links.index') }}" method="GET" class="mb-6 flex gap-4 flex-wrap">
                        <input type="text" name="search" placeholder="Search by title..." value="{{ request('search') }}" class="shadow border rounded py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                        
                        <select name="category_id" class="shadow border rounded py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <select name="tag_id" class="shadow border rounded py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                            <option value="">All Tags</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                        <a href="{{ route('links.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded flex items-center">Reset</a>
                    </form>

                    <a href="{{ route('links.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add Link</a>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($links as $link)
                        <div class="border rounded p-4 dark:border-gray-700">
                            <h3 class="font-bold text-lg mb-2"><a href="{{ $link->url }}" target="_blank" class="text-blue-500 hover:underline">{{ $link->title }}</a></h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Category: {{ $link->category->name }}</p>
                            <div class="mb-2">
                                @foreach($link->tags as $tag)
                                    <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2 mb-2">#{{ $tag->name }}</span>
                                @endforeach
                            </div>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('links.edit', $link) }}" class="text-blue-500 hover:text-blue-700 text-sm">Edit</a>
                                <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
