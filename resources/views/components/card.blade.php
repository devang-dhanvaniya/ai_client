<div class="col">
    <div class="card card-custom p-3 border-{{ $borderColor }} shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-muted">{{ $title }}</h6>
                <h5 class="{{ $valueClass }}">{{ trim($prefix . ' ' . $value . ' ' . $suffix) }}</h5>
            </div>
            <div class="icon-box">
                <i class="{{ $icon }} text-{{ $borderColor }} fs-3"></i>
            </div>
        </div>
    </div>
</div>
