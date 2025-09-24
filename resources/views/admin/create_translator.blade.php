@extends('layouts.app')

@section('title', __('translator.add_title'))

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-10"> <!-- ✅ Responsive column -->
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
          <h3 class="mb-4 text-center text-primary fw-bold">
            {{ __('translator.add_title') }}
          </h3>

          {{-- ✅ Success message --}}
          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          {{-- ✅ Errors --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- ✅ Form --}}
          <form action="{{ route('admin.translators.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label class="form-label">{{ __('translator.full_name') }}</label>
              <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">{{ __('translator.email') }}</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">{{ __('translator.phone') }}</label>
              <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">{{ __('translator.password') }}</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                {{ __('translator.create_account') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
