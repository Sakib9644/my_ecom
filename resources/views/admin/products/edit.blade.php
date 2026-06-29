@extends('layouts.admin')

@section('page_title', 'Edit Product: ' . $product->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-slate-500 hover:text-primary-600 flex items-center gap-1.5 w-fit">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to list
    </a>
</div>

<div class="max-w-2xl bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Category</label>
                <select name="category_id" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Price -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Price ($)</label>
                <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Stock (shown when no variants) -->
            <div id="default-stock-field">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Stock Quantity</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Image File Input -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Product Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                <span class="block text-[10px] text-slate-400 mt-1">Leave blank to keep existing image.</span>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Current image preview -->
        @if($product->image)
            <div class="p-4 border border-slate-200/60 bg-slate-50/50 rounded-2xl flex items-center gap-4">
                <div class="h-16 w-16 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 shrink-0">
                    <img src="{{ $product->image_url }}" alt="Preview" class="h-full w-full object-cover">
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase">Current Image</span>
                    <p class="text-xs text-slate-600 truncate max-w-[300px]">{{ basename($product->image) }}</p>
                </div>
            </div>
        @endif

        <!-- ═══════════════════════════════════════ -->
        <!-- SIZE MODULE  (from DB)                 -->
        <!-- ═══════════════════════════════════════ -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-ruler text-slate-400 text-sm"></i>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Available Sizes</span>
                </div>
                <span class="text-[10px] text-slate-400 font-medium">Select all sizes that apply</span>
            </div>
            <div class="p-4">
                @if($sizes->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-sm text-slate-400">No sizes defined yet.</p>
                        <a href="{{ route('admin.sizes.create') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 mt-1 inline-block">Add sizes first →</a>
                    </div>
                @else
                    @php
                        $selectedSizes = old('sizes', $product->sizes ? \App\Models\Size::whereIn('name', $product->sizes)->pluck('id')->toArray() : []);
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($sizes as $size)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-primary-400 hover:bg-primary-50/30 transition-all has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                                <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                                    {{ in_array($size->id, $selectedSizes) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
                                <span class="text-sm font-semibold text-slate-700">{{ $size->name }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- COLOR MODULE (from DB)                 -->
        <!-- ═══════════════════════════════════════ -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-palette text-slate-400 text-sm"></i>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Available Colors</span>
                </div>
                <span class="text-[10px] text-slate-400 font-medium">Select colors and upload images for each</span>
            </div>
            <div class="p-4 space-y-4">
                @if($colors->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-sm text-slate-400">No colors defined yet.</p>
                        <a href="{{ route('admin.colors.create') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 mt-1 inline-block">Add colors first →</a>
                    </div>
                @else
                    @php
                        $selectedColors = old('colors', $product->colors ? \App\Models\Color::whereIn('name', $product->colors)->pluck('id')->toArray() : []);
                        $colorImages = $product->color_images ?? [];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($colors as $color)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-primary-400 hover:bg-primary-50/30 transition-all has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                                <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                                    {{ in_array($color->id, $selectedColors) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 h-4 w-4 color-checkbox"
                                    data-color-id="{{ $color->id }}"
                                    data-color-name="{{ $color->name }}"
                                    data-has-image="{{ isset($colorImages[$color->name]) ? '1' : '0' }}">
                                @if($color->hex_code)
                                    <span class="inline-block h-3.5 w-3.5 rounded-full border border-slate-200 shrink-0" style="background-color: {{ $color->hex_code }}"></span>
                                @endif
                                <span class="text-sm font-semibold text-slate-700">{{ $color->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- Color Image Upload Section -->
                    <div id="color-images-section" class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                            <i class="fa-solid fa-image text-slate-400 mr-1"></i> Color Images <span class="text-slate-400 font-normal normal-case">(optional — shown when customer selects that color)</span>
                        </p>
                        <div id="color-image-uploads" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Description</label>
            <textarea name="description" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Featured Checkbox -->
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
            <label for="is_featured" class="text-sm font-semibold text-slate-600 cursor-pointer">Feature this product on homepage</label>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- VARIANT STOCK MODULE                 -->
        <!-- ═══════════════════════════════════════ -->
        @php $existingVariantStock = $product->variant_stock ?? []; @endphp
        <div id="variant-stock-section" class="border border-slate-200 rounded-2xl overflow-hidden hidden">
            <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-slate-400 text-sm"></i>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Variant Stock</span>
                </div>
                <span id="variant-hint" class="text-[10px] text-slate-400 font-medium"></span>
            </div>
            <div class="p-4">
                <div id="variant-stock-grid" class="space-y-3"></div>
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex gap-4 justify-end">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs transition-colors shadow-md shadow-primary-500/10">Update Product</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.color-checkbox');
        const section = document.getElementById('color-images-section');
        const container = document.getElementById('color-image-uploads');

        function updateColorImages() {
            container.innerHTML = '';
            let anyChecked = false;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    anyChecked = true;
                    const colorId = cb.dataset.colorId;
                    const colorName = cb.dataset.colorName;
                    const hasImage = cb.dataset.hasImage === '1';
                    const hex = cb.closest('label').querySelector('span.rounded-full');
                    const hexCode = hex ? hex.style.backgroundColor : '#ccc';

                    const wrapper = document.createElement('div');
                    wrapper.className = 'border border-slate-200 rounded-xl p-3 space-y-2 bg-slate-50/50';
                    wrapper.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="inline-block h-3.5 w-3.5 rounded-full border border-slate-200 shrink-0" style="background-color: ${hexCode}"></span>
                            <span class="text-xs font-bold text-slate-700">${colorName}</span>
                            ${hasImage ? '<span class="ml-auto text-[10px] text-green-600 font-semibold"><i class="fa-solid fa-image"></i> Has image</span>' : ''}
                        </div>
                        <input type="file" name="color_images[${colorId}]" accept="image/*"
                            class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    `;
                    container.appendChild(wrapper);
                }
            });

            section.classList.toggle('hidden', !anyChecked);
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateColorImages));
        updateColorImages();

        // ── Variant Stock Dynamic Fields ──
        const sizeCheckboxes = document.querySelectorAll('input[name="sizes[]"]');
        const colorCheckboxesJS = document.querySelectorAll('.color-checkbox');
        const variantSection = document.getElementById('variant-stock-section');
        const variantGrid = document.getElementById('variant-stock-grid');
        const variantHint = document.getElementById('variant-hint');
        const defaultStockField = document.getElementById('default-stock-field');
        const existingStock = @json($existingVariantStock);

        function getSelectedSizes() {
            return Array.from(sizeCheckboxes).filter(cb => cb.checked).map(cb => cb.closest('label').querySelector('span').textContent.trim());
        }

        function getSelectedColors() {
            return Array.from(colorCheckboxesJS).filter(cb => cb.checked).map(cb => ({ id: cb.dataset.colorId, name: cb.dataset.colorName }));
        }

        function buildVariantStock() {
            const sizes = getSelectedSizes();
            const colors = getSelectedColors();
            const hasSizes = sizes.length > 0;
            const hasColors = colors.length > 0;

            variantGrid.innerHTML = '';

            if (!hasSizes && !hasColors) {
                variantSection.classList.add('hidden');
                defaultStockField.classList.remove('hidden');
                defaultStockField.querySelector('input[name="stock"]').setAttribute('required', 'required');
                return;
            }

            variantSection.classList.remove('hidden');
            defaultStockField.classList.add('hidden');
            defaultStockField.querySelector('input[name="stock"]').removeAttribute('required');

            if (hasColors && hasSizes) {
                variantHint.textContent = 'Stock per color + size combination';
                colors.forEach(color => {
                    const card = document.createElement('div');
                    card.className = 'border border-slate-200 rounded-xl p-3 bg-slate-50/50';
                    let sizeInputs = sizes.map(size => {
                        const val = (existingStock[color.name] && existingStock[color.name][size] !== undefined) ? existingStock[color.name][size] : 0;
                        return `
                            <div class="flex items-center gap-2">
                                <label class="text-[10px] font-bold text-slate-500 w-16 shrink-0">${size}</label>
                                <input type="number" name="variant_stock[${color.name}|${size}]" min="0" value="${val}" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-primary-500 bg-white" placeholder="0">
                            </div>
                        `;
                    }).join('');
                    card.innerHTML = `
                        <div class="flex items-center gap-2 mb-2 pb-2 border-b border-slate-200">
                            <span class="text-xs font-bold text-slate-700">${color.name}</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">${sizeInputs}</div>
                    `;
                    variantGrid.appendChild(card);
                });
            } else if (hasColors) {
                variantHint.textContent = 'Stock per color';
                colors.forEach(color => {
                    const val = existingStock[color.name] !== undefined ? existingStock[color.name] : 0;
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50/50';
                    row.innerHTML = `
                        <span class="text-xs font-bold text-slate-700 w-32 shrink-0">${color.name}</span>
                        <input type="number" name="variant_stock[${color.name}]" min="0" value="${val}" class="flex-1 rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-primary-500 bg-white" placeholder="Stock quantity">
                    `;
                    variantGrid.appendChild(row);
                });
            } else if (hasSizes) {
                variantHint.textContent = 'Stock per size';
                sizes.forEach(size => {
                    const val = existingStock[size] !== undefined ? existingStock[size] : 0;
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50/50';
                    row.innerHTML = `
                        <span class="text-xs font-bold text-slate-700 w-32 shrink-0">${size}</span>
                        <input type="number" name="variant_stock[${size}]" min="0" value="${val}" class="flex-1 rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-primary-500 bg-white" placeholder="Stock quantity">
                    `;
                    variantGrid.appendChild(row);
                });
            }
        }

        sizeCheckboxes.forEach(cb => cb.addEventListener('change', buildVariantStock));
        colorCheckboxesJS.forEach(cb => cb.addEventListener('change', buildVariantStock));
        buildVariantStock();
    });
</script>
@endsection
