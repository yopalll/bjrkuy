@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">

    <div>

        <h1 class="text-3xl font-bold text-brand-text-dark tracking-tight">
            Edit Category
        </h1>

        <p class="text-sm text-slate-500 mt-2">
            Update your category information.
        </p>

    </div>

    <a href="{{ route('admin.categories.index') }}"
       class="bg-brand-cream-card border border-slate-200 hover:bg-brand-bg-soft/60 text-brand-text-dark px-5 py-3 rounded-2xl text-sm font-medium shadow-sm transition flex items-center gap-2">

        <i data-lucide="arrow-left"
           class="w-4 h-4"></i>

        Back

    </a>

</div>

<!-- FORM -->
<div class="max-w-4xl">

    <div class="bg-brand-cream-card rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">

        <!-- HEADER -->
        <div class="px-8 py-6 border-b border-slate-100">

            <h1 class="text-lg font-semibold text-brand-text-dark">
                Edit Category
            </h1>

            <p class="text-sm text-slate-400 mt-1">
                Update category details below.
            </p>

        </div>

        <!-- FORM -->
        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-8">

            @csrf
            @method('PUT')

            <!-- NAME -->
            <div class="mb-6">

                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Category Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $category->name) }}"
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">

            </div>

            <!-- SLUG -->
            <div class="mb-6">

                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Category Slug
                </label>

                <input type="text"
                       name="slug"
                       value="{{ old('slug', $category->slug) }}"
                       class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all">

            </div>

            <!-- IMAGE UPLOAD -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Category Image <span class="text-xs text-slate-400 font-normal">(Optional)</span>
                </label>

                <div class="relative group">
                    <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewImage(event)">
                    
                    <label for="imageInput" id="uploadZone" class="flex flex-col items-center justify-center w-full h-48 bg-brand-bg-soft border-2 border-dashed border-slate-300 rounded-3xl cursor-pointer hover:bg-brand-bg-soft/60 hover:border-brand-accent-blue/60 transition-all overflow-hidden relative">
                        
                        <div id="uploadPlaceholder" class="flex flex-col items-center justify-center pt-5 pb-6 {{ $category->image_url ? 'hidden' : '' }}">
                            <div class="w-12 h-12 bg-brand-cream-card rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <i data-lucide="cloud-upload" class="w-6 h-6 text-blue-500"></i>
                            </div>
                            <p class="mb-1 text-sm text-slate-500 font-medium"><span class="text-brand-accent-blue font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-slate-400">PNG, JPG, WEBP (MAX. 2MB)</p>
                        </div>

                        <img id="imagePreview" src="{{ $category->image_url ?? '#' }}" alt="Preview" class="{{ $category->image_url ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover">
                        
                        <div id="imageOverlay" class="{{ $category->image_url ? '' : 'hidden' }} absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <span class="bg-brand-cream-card/90 text-slate-800 text-xs font-semibold px-4 py-2 rounded-full shadow-sm backdrop-blur-sm">
                                Change Image
                            </span>
                        </div>
                    </label>
                    @error('image')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-6">

                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Description
                </label>

                <textarea name="description"
                          rows="6"
                          class="w-full bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 text-sm outline-none focus:ring-4 focus:ring-brand-accent-blue/20 focus:border-brand-accent-blue/40 transition-all resize-none">{{ old('description', $category->description) }}</textarea>

            </div>

            <!-- STATUS -->
            <div class="mb-8">

                <label class="block text-sm font-semibold text-brand-text-dark mb-3">
                    Status
                </label>

                <label class="flex items-center gap-3 bg-brand-bg-soft border border-slate-200 rounded-2xl px-5 py-4 cursor-pointer">

                    <input type="checkbox"
                           name="status"
                           value="1"
                           {{ $category->status ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-200">

                    <div>

                        <h1 class="text-sm font-medium text-slate-800">
                            Active Category
                        </h1>

                        <p class="text-xs text-slate-400 mt-1">
                            This category will be visible publicly.
                        </p>

                    </div>

                </label>

            </div>

            <!-- BUTTON -->
            <div class="flex items-center gap-4">

                <button type="submit"
                        class="bg-brand-accent-blue hover:opacity-90 text-white px-6 py-4 rounded-2xl text-sm font-semibold shadow-lg shadow-blue-100 transition flex items-center gap-2">

                    <i data-lucide="save"
                       class="w-4 h-4"></i>

                    Update Category

                </button>

                <a href="{{ route('admin.categories.index') }}"
                   class="text-slate-500 hover:text-slate-800 text-sm font-medium transition">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@push('scripts')
<script>
    function previewImage(event) {
        const input = event.target;
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const imagePreview = document.getElementById('imagePreview');
        const imageOverlay = document.getElementById('imageOverlay');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                imageOverlay.classList.remove('hidden');
                if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            
        }
    }
</script>
@endpush

@endsection