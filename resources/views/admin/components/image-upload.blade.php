@php
    $name = $name ?? 'image';
    $id = $id ?? 'file_upload_' . uniqid();
    $preview = $preview ?? null;
    $defaultHeight = $height ?? '250px';
@endphp

<div class="image-upload-wrapper w-100">
    <div 
        class="drag-drop-zone border rounded d-flex flex-column align-items-center justify-content-center bg-light position-relative"
        style="height: {{ $defaultHeight }}; border: 2px dashed #ced4da !important; cursor: pointer; overflow: hidden; transition: all 0.3s ease;"
        id="zone_{{ $id }}"
        onclick="document.getElementById('{{ $id }}').click()"
        ondragover="event.preventDefault(); document.getElementById('zone_{{ $id }}').style.borderColor = '#0d6efd'; document.getElementById('zone_{{ $id }}').style.backgroundColor = '#e9ecef';"
        ondragleave="document.getElementById('zone_{{ $id }}').style.borderColor = '#ced4da'; document.getElementById('zone_{{ $id }}').style.backgroundColor = '#f8f9fa';"
        ondrop="event.preventDefault(); document.getElementById('zone_{{ $id }}').style.borderColor = '#ced4da'; document.getElementById('zone_{{ $id }}').style.backgroundColor = '#f8f9fa'; handleDrop_{{ $id }}(event);"
    >
        <!-- Preview Image -->
        <img id="preview_img_{{ $id }}" src="{{ $preview ? Storage::url($preview) : '#' }}" alt="Preview" class="img-fluid {{ $preview ? '' : 'd-none' }}" style="max-height: 100%; object-fit: contain; z-index: 2; position: relative;">
        
        <!-- Placeholder Text -->
        <div id="placeholder_{{ $id }}" class="text-center position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center {{ $preview ? 'd-none' : '' }}" style="z-index: 1;">
            <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 3.5rem;"></i>
            <p class="text-muted small mt-2 font-weight-bold mb-0">Kéo thả ảnh vào đây</p>
            <p class="text-muted small mb-0">hoặc click để chọn tệp</p>
        </div>
    </div>
    
    <!-- Hidden File Input -->
    <input 
        type="file" 
        class="form-control d-none @error($name) is-invalid @enderror" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        accept="image/*" 
        onchange="previewImage_{{ $id }}(this)"
    >
    
    <!-- Error Message -->
    @error($name)
        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
    @enderror

    <!-- Clear Button (optional) -->
    <button type="button" id="clear_btn_{{ $id }}" class="btn btn-sm btn-outline-danger mt-2 {{ $preview ? '' : 'd-none' }}" onclick="clearImage_{{ $id }}(event)">
        <i class="bi bi-trash"></i> Xóa ảnh
    </button>
</div>

<script>
    function previewImage_{{ $id }}(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview_img_{{ $id }}').src = e.target.result;
                document.getElementById('preview_img_{{ $id }}').classList.remove('d-none');
                document.getElementById('placeholder_{{ $id }}').classList.add('d-none');
                document.getElementById('clear_btn_{{ $id }}').classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleDrop_{{ $id }}(event) {
        if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
            let input = document.getElementById('{{ $id }}');
            input.files = event.dataTransfer.files;
            
            // Trigger change event manually
            const eventChange = new Event('change');
            input.dispatchEvent(eventChange);
        }
    }

    function clearImage_{{ $id }}(event) {
        event.stopPropagation(); // Prevent clicking the zone again
        document.getElementById('{{ $id }}').value = '';
        document.getElementById('preview_img_{{ $id }}').src = '#';
        document.getElementById('preview_img_{{ $id }}').classList.add('d-none');
        @if($preview)
            // If there's an existing image, clearing it will only visually hide it. 
            // In a real app, you might want a hidden input to mark deletion.
        @endif
        document.getElementById('placeholder_{{ $id }}').classList.remove('d-none');
        document.getElementById('clear_btn_{{ $id }}').classList.add('d-none');
    }
</script>
