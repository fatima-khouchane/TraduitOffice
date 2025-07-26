@extends('layouts.app')

@section('title', __('translator.add_title'))

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow p-4">
        <h3 class="mb-4 text-center">{{ __('translator.add_title') }}</h3>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('admin.translators.store') }}" method="POST">
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

          <button type="submit" class="btn btn-primary">{{ __('translator.create_account') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
