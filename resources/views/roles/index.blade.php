@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div style="padding: 3rem 0;">
  <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

      <!-- Page Header -->
      <div style="padding: 1.5rem; text-align: center;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1f2937;">إدارة الأدوار والصلاحيات</h1>
      </div>

      <div style="padding: 1.5rem;">
        @if (session('success'))
          <div style="background-color: #d1fae5; color: #065f46; padding: 1rem 1.25rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
            {{ session('success') }}
          </div>
        @endif

        @if (session('error'))
          <div style="background-color: #fef2f2; color: #dc2626; padding: 1rem 1.25rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
            {{ session('error') }}
          </div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.users.index') }}" style="background: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none;">
              إدارة المستخدمين
            </a>
            <a href="{{ route('admin.roles.create') }}" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none;">
              + إضافة دور جديد
            </a>
          </div>
        </div>

        <!-- Roles Table -->
        <div style="overflow-x: auto; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
              <tr>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">اسم الدور</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">الوصف</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">الصلاحيات</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">عدد المستخدمين</th>
                <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151;">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $role)
              <tr style="border-top: 1px solid #e5e7eb;">
                <td style="padding: 1rem; text-align: center; color: #1f2937;">
                  <strong>{{ $role->name }}</strong>
                </td>
                <td style="padding: 1rem; text-align: center; color: #4b5563;">
                  {{ $role->description ?? 'لا يوجد وصف' }}
                </td>
                <td style="padding: 1rem; text-align: center;">
                  @foreach($role->permissions->take(3) as $permission)
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #dbeafe; color: #1e40af; border-radius: 9999px; font-size: 0.7rem; margin: 0.1rem;">
                      {{ $permission->name }}
                    </span>
                  @endforeach
                  @if($role->permissions->count() > 3)
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #f3f4f6; color: #374151; border-radius: 9999px; font-size: 0.7rem;">
                      +{{ $role->permissions->count() - 3 }}
                    </span>
                  @endif
                  @if($role->permissions->count() == 0)
                    <span style="display: inline-block; padding: 0.25rem 0.5rem; background: #f3f4f6; color: #374151; border-radius: 9999px; font-size: 0.75rem;">
                      لا توجد صلاحيات
                    </span>
                  @endif
                </td>
                <td style="padding: 1rem; text-align: center; color: #4b5563;">
                  {{ $role->users->count() }} مستخدم
                </td>
                <td style="padding: 1rem; text-align: center;">
                  <a href="{{ route('admin.roles.edit', $role) }}" style="color: #3b82f6; text-decoration: none; margin: 0 0.5rem;">
                    <i class="fas fa-edit"></i>
                  </a>

                  @if(!in_array($role->name, ['super_admin', 'admin']))
                  <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; margin: 0 0.5rem;">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                  @else
                  <span style="color: #9ca3af; margin: 0 0.5rem;" title="لا يمكن حذف هذا الدور">
                    <i class="fas fa-trash"></i>
                  </span>
                  @endif
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
