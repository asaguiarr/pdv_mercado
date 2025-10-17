@props(['color' => 'primary', 'icon', 'label', 'route'])

<div class="col-6 col-md-4 col-lg-3">
    <a href="{{ route($route) }}" class="btn btn-{{ $color }} w-100 d-flex align-items-center justify-content-center py-3 px-2 rounded-3 text-decoration-none text-white shadow-sm hover-shadow">
        <div class="text-center">
            <i class="{{ $icon }} fs-4 mb-2 d-block"></i>
            <span class="fw-semibold small">{{ $label }}</span>
        </div>
    </a>
</div>
