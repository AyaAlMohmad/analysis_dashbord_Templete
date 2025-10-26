@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div style="padding: 3rem 0;">
  <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

      <!-- Modal Content -->
      <div style="position: relative; z-index: 50; width: 100%; max-width: 32rem; margin: 2rem auto;
                background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">
          {{ __('users.edit_user') }}
        </h3>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" style="display: grid; gap: 1.25rem;">
          @csrf
          @method('PUT')

          <div style="display: grid; gap: 0.5rem;">
            <label style="font-size: 0.875rem; color: #374151; font-weight: 500;">{{ __('users.name') }}</label>
            <input type="text" name="name" value="{{ $user->name }}" required
                   style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
                          border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
          </div>

          <div style="display: grid; gap: 0.5rem;">
            <label style="font-size: 0.875rem; color: #374151; font-weight: 500;">{{ __('users.email') }}</label>
            <input type="email" name="email" value="{{ $user->email }}" required
                   style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
                          border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
          </div>

          <div style="display: grid; gap: 0.5rem;">
            <label style="font-size: 0.875rem; color: #374151; font-weight: 500;">{{ __('users.new_password') }}</label>
            <input type="password" name="password" placeholder="{{ __('users.leave_blank') }}"
                   style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
                          border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
          </div>

          <!-- الأدوار -->
          <div style="border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 1rem;">
            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; color: #374151;">{{ __('users.roles') }}</h4>
            <div style="display: grid; gap: 0.5rem;">
              @foreach($roles as $role)
              <div style="display: flex; align-items: center; gap: 0.75rem;">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                       {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                       style="width: 1rem; height: 1rem; border: 1px solid #d1d5db;
                              border-radius: 0.25rem; accent-color: #2563eb;">
                <label for="role_{{ $role->id }}" style="font-size: 0.875rem; color: #374151;">
                  {{ $role->name }} - <span style="color: #6b7280;">{{ $role->description }}</span>
                </label>
              </div>
              @endforeach
            </div>
          </div>

          <!-- الصلاحيات المباشرة -->
          <div style="border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 1rem;">
            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; color: #374151;">{{ __('users.direct_permissions') }}</h4>
            <div style="display: grid; gap: 0.5rem;">
              @foreach($permissions as $permission)
              <div style="display: flex; align-items: center; gap: 0.75rem;">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}"
                       {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}
                       style="width: 1rem; height: 1rem; border: 1px solid #d1d5db;
                              border-radius: 0.25rem; accent-color: #2563eb;">
                <label for="permission_{{ $permission->id }}" style="font-size: 0.875rem; color: #374151;">
                  {{ $permission->name }} - <span style="color: #6b7280;">{{ $permission->description }}</span>
                </label>
              </div>
              @endforeach
            </div>
          </div>

          <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
            <a href="{{ route('admin.users.index') }}"
               style="padding: 0.625rem 1.25rem; background-color: #f3f4f6;
                      color: #374151; border-radius: 0.375rem; text-decoration: none;
                      font-size: 0.875rem; transition: background-color 0.2s;">
              {{ __('users.cancel') }}
            </a>
            <button type="submit"
                    style="padding: 0.625rem 1.25rem; background-color: #2563eb;
                           color: white; border-radius: 0.375rem; border: none;
                           font-size: 0.875rem; cursor: pointer; transition: background-color 0.2s;">
              {{ __('users.update') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
