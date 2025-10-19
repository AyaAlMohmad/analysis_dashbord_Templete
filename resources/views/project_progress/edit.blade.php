@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <div style="padding: 3rem 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

                <!-- Modal Content -->
                <div
                    style="position: relative; z-index: 50; width: 100%; max-width: 28rem; margin: 2rem auto;
                background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">
                        {{ __('project_progress.edit') }}
                    </h3>

                    <form method="POST" action="{{ route('admin.project-progress.update', $progress->id) }}"
                        style="display: grid; gap: 1.25rem;">
                        @csrf
                        @method('PUT')
                        <div style="display: grid; gap: 0.5rem;">
                            <label
                                style="font-size: 0.875rem; color: #374151; font-weight: 500;">{{ __('project_progress.site') }}</label>
                            <select name="site" id="" required
                                style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
               border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
                                <option value="dhahran" {{ $progress->site == 'dhahran' ? 'selected' : '' }}>
                                    {{ __('project_progress.dhahran') }}
                                </option>
                                <option value="bashaer" {{ $progress->site == 'bashaer' ? 'selected' : '' }}>
                                    {{ __('project_progress.bashaer') }}
                                </option>
                                <option value="jaddah" {{ $progress->site == 'jaddah' ? 'selected' : '' }}>
                                    {{ __('project_progress.jaddah') }}
                                </option>
                                <option value="alfursan" {{ $progress->site == 'alfursan' ? 'selected' : '' }}>
                                    {{ __('project_progress.alfursan') }}
                                </option>
                            </select>

                        </div>

                        <div style="display: grid; gap: 0.5rem;">
                            <label
                                style="font-size: 0.875rem; color: #374151; font-weight: 500;">{{ __('project_progress.progress_percentage') }}</label>
                            <input type="number" name="progress_percentage" required
                                style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
                          border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);"
                                value="{{ $progress->progress_percentage }}">
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
                                {{ __('project_progress.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
