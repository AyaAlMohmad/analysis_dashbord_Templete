@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div style="padding: 3rem 0;">
  <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

      <!-- Page Header -->
      <div style="padding: 1.5rem; text-align: center;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1f2937;">{{ __('users.title') }}</h1>
      </div>

      <div style="padding: 1.5rem;">
        @if (session('success'))
          <div style="background-color: #d1fae5; color: #065f46; padding: 1rem 1.25rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
            {{ session('success') }}
          </div>
        @endif

        <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
          <a href="{{ route('admin.users.create') }}" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none;">
            + {{ __('users.new_user') }}
          </a>
        </div>

        <!-- Users Table -->
        <div style="overflow-x: auto; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
              <tr>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">{{ __('users.name') }}</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">{{ __('users.email') }}</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">{{ __('users.roles') }}</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">{{ __('users.direct_permissions') }}</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">{{ __('users.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr style="border-top: 1px solid #e5e7eb;">
                <td style="padding: 1rem; text-align: center; color: #1f2937;">{{ $user->name }}</td>
                <td style="padding: 1rem; text-align: center; color: #4b5563;">{{ $user->email }}</td>
                <td style="padding: 1rem; text-align: center;">
                  @foreach($user->roles as $role)
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #dcfce7; color: #166534; border-radius: 9999px; font-size: 0.75rem; margin: 0.1rem;">
                      {{ $role->name }}
                    </span>
                  @endforeach
                  @if($user->roles->count() == 0)
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #f3f4f6; color: #374151; border-radius: 9999px; font-size: 0.75rem;">
                      {{ __('users.no_roles') }}
                    </span>
                  @endif
                </td>
                <td style="padding: 1rem; text-align: center;">
                  @foreach($user->permissions as $permission)
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #dbeafe; color: #1e40af; border-radius: 9999px; font-size: 0.7rem; margin: 0.1rem;">
                      {{ $permission->name }}
                    </span>
                  @endforeach
                  @if($user->permissions->count() == 0)
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #f3f4f6; color: #374151; border-radius: 9999px; font-size: 0.75rem;">
                      {{ __('users.no_permissions') }}
                    </span>
                  @endif
                </td>
                <td style="padding: 1rem; text-align: center;">
                  <a href="{{ route('admin.users.edit', $user) }}" style="color: #3b82f6; text-decoration: none; margin: 0 0.5rem;">
                    <i class="fas fa-edit"></i>
                  </a>

                  <a href="{{ route('admin.users.manage-permissions', $user) }}" style="color: #8b5cf6; text-decoration: none; margin: 0 0.5rem;">
                    <i class="fas fa-key"></i>
                  </a>

                  <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ __('users.are_you_sure') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; margin: 0 0.5rem;">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
