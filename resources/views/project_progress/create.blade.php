@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div style="padding: 3rem 0;">
  <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

      <!-- Display Validation Errors -->
      @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem; margin: 1rem; border-radius: 0.375rem;">
          <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Modal Content -->
      <div style="position: relative; z-index: 50; width: 100%; max-width: 28rem; margin: 2rem auto;
                background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">
          {{ __('project_progress.create') }}
        </h3>

        <form method="POST" action="{{ route('admin.project-progress.store') }}" style="display: grid; gap: 1.25rem;">
          @csrf

          <div style="display: grid; gap: 0.5rem;">
            <label style="font-size: 0.875rem; color: #374151; font-weight: 500;">{{ __('project_progress.site') }}</label>
            <select name="site" id="site" required>
                <option value="dhahran" {{ old('site') == 'dhahran' ? 'selected' : '' }}>{{ __('project_progress.dhahran') }}</option>
                <option value="bashaer" {{ old('site') == 'bashaer' ? 'selected' : '' }}>{{ __('project_progress.bashaer') }}</option>
                <option value="jaddah" {{ old('site') == 'jaddah' ? 'selected' : '' }}>{{ __('project_progress.jaddah') }}</option>
            </select>
            @error('site')
              <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
          </div>

          <div style="display: grid; gap: 0.5rem;">
            <label style="font-size: 0.875rem; color: #374151; font-weight: 500;">{{ __('project_progress.progress_percentage') }}</label>
            <input type="number" name="progress_percentage" value="{{ old('progress_percentage') }}" required
                   style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
                          border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
            @error('progress_percentage')
              <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
          </div>

          <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
            <a href="{{ route('admin.project-progress.index') }}"
               style="padding: 0.625rem 1.25rem; background-color: #f3f4f6;
                      color: #374151; border-radius: 0.375rem; text-decoration: none;
                      font-size: 0.875rem; transition: background-color 0.2s;">
              {{ __('project_progress.cancel') }}
            </a>
            <button type="submit"
                    style="padding: 0.625rem 1.25rem; background-color: #2563eb;
                           color: white; border-radius: 0.375rem; border: none;
                           font-size: 0.875rem; cursor: pointer; transition: background-color 0.2s;">
              {{ __('project_progress.save') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
