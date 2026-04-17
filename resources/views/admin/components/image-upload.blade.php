@php
    $name = $name ?? 'image';
    $id = $id ?? 'file_upload_' . uniqid();
    $previewUrl = !empty($preview) ? Storage::url($preview) : null;
    $defaultHeight = $height ?? '250px';
@endphp

<style>
    .image-upload-container {
        max-width: 100%;
        position: relative;
    }

    .drag-drop-zone {
        height: {{ $defaultHeight }};
        border: 2px dashed #dee2e6 !important;
        border-radius: 12px;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .drag-drop-zone:hover {
        border-color: #0d6efd !important;
        background-color: #f1f4f9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .preview-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-wrapper img {
        max-height: 100%;
        width: 100%;
        object-fit: cover;
        /* Đổi sang cover để nhìn ảnh đầy đặn hơn */
        transition: transform 0.5s ease;
    }

    .drag-drop-zone:hover img {
        transform: scale(1.05);
    }

    /* Lớp phủ khi hover vào ảnh */
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 3;
    }

    .preview-wrapper:hover .image-overlay {
        opacity: 1;
    }

    .btn-remove-image {
        background: white;
        color: #dc3545;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.2s;
    }

    .btn-remove-image:hover {
        background: #dc3545;
        color: white;
        transform: scale(1.1);
    }
</style>

<div class="image-upload-container">
    <div class="drag-drop-zone" id="zone_{{ $id }}"
        onclick="document.getElementById('{{ $id }}').click()"
        ondragover="event.preventDefault(); this.style.borderColor = '#0d6efd';"
        ondragleave="this.style.borderColor = '#dee2e6';" ondrop="handleDrop_{{ $id }}(event)">

        <div id="wrapper_{{ $id }}" class="preview-wrapper {{ $previewUrl ? '' : 'd-none' }}">
            <img id="preview_img_{{ $id }}" src="{{ $previewUrl ?? '' }}" alt="Preview">
            <div class="image-overlay">
                <button type="button" class="btn-remove-image" title="Xóa ảnh"
                    onclick="clearImage_{{ $id }}(event)">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        </div>

        <div id="placeholder_{{ $id }}" class="text-center {{ $previewUrl ? 'd-none' : '' }}">
            <div class="mb-2">
                <i class="bi bi-image-fill text-secondary opacity-25" style="font-size: 3rem;"></i>
            </div>
            <p class="text-dark fw-semibold mb-1">Tải ảnh lên</p>
            <p class="text-muted small mb-0 px-3">Kéo thả hoặc nhấn để chọn file</p>
        </div>
    </div>

    <input type="file" class="d-none" name="{{ $name }}" id="{{ $id }}" accept="image/*"
        onchange="previewImage_{{ $id }}(this)">

    @error($name)
        <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
    @enderror
</div>

<script>
    function previewImage_{{ $id }}(input) {
        const wrapper = document.getElementById('wrapper_{{ $id }}');
        const img = document.getElementById('preview_img_{{ $id }}');
        const placeholder = document.getElementById('placeholder_{{ $id }}');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                wrapper.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleDrop_{{ $id }}(event) {
        event.preventDefault();
        const zone = document.getElementById('zone_{{ $id }}');
        zone.style.borderColor = '#dee2e6';

        const files = event.dataTransfer.files;
        if (files.length > 0) {
            const input = document.getElementById('{{ $id }}');
            input.files = files;
            previewImage_{{ $id }}(input);
        }
    }

    function clearImage_{{ $id }}(event) {
        event.stopPropagation(); // Quan trọng: không kích hoạt click vào zone

        const input = document.getElementById('{{ $id }}');
        const wrapper = document.getElementById('wrapper_{{ $id }}');
        const img = document.getElementById('preview_img_{{ $id }}');
        const placeholder = document.getElementById('placeholder_{{ $id }}');

        input.value = '';
        img.src = '';
        wrapper.classList.add('d-none');
        placeholder.classList.remove('d-none');
    }
</script>
