@if (session('status'))
    <div class="alert alert-success shadow-sm mb-4" role="alert">
        <div>
            {{ session('status') }}
        </div>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success shadow-sm mb-4" role="alert">
        <div>
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger shadow-sm mb-4" role="alert">
        <div>
            {{ session('error') }}
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning shadow-sm mb-4" role="alert">
        <div>
            {{ session('warning') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger shadow-sm mb-4" role="alert">
        <div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
