@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div style="padding: 3rem 0;">
  <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

      <!-- Modal Content -->
      <div style="position: relative; z-index: 50; width: 100%; max-width: 42rem; margin: 2rem auto;
                background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">

        <!-- Header -->
        <div style="text-align: center; margin-bottom: 1.5rem;">
          <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">
            إدارة صلاحيات المستخدم: {{ $user->name }}
          </h3>
          <p style="color: #6b7280; font-size: 0.875rem;">البريد الإلكتروني: {{ $user->email }}</p>
        </div>

        <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}">
          @csrf
          @method('PUT')

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">

            <!-- الأدوار -->
            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.25rem; background: #f9fafb;">
              <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #374151; text-align: center;">
                <i class="fas fa-users" style="margin-left: 0.5rem;"></i>
                الأدوار
              </h4>
              <div style="display: grid; gap: 0.75rem; max-height: 300px; overflow-y: auto;">
                @foreach($roles as $role)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: white; border-radius: 0.375rem;">
                  <input type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                         {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                         style="width: 1.125rem; height: 1.125rem; border: 1px solid #d1d5db;
                                border-radius: 0.25rem; accent-color: #2563eb;">
                  <div style="flex: 1;">
                    <label for="role_{{ $role->id }}" style="font-size: 0.875rem; font-weight: 500; color: #374151; display: block;">
                      {{ $role->name }}
                    </label>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                      {{ $role->description }}
                    </p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>

            <!-- الصلاحيات المباشرة -->
            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.25rem; background: #f9fafb;">
              <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #374151; text-align: center;">
                <i class="fas fa-key" style="margin-left: 0.5rem;"></i>
                الصلاحيات المباشرة
              </h4>
              <div style="display: grid; gap: 0.75rem; max-height: 300px; overflow-y: auto;">
                @foreach($permissions as $permission)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: white; border-radius: 0.375rem;">
                  <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}"
                         {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}
                         style="width: 1.125rem; height: 1.125rem; border: 1px solid #d1d5db;
                                border-radius: 0.25rem; accent-color: #2563eb;">
                  <div style="flex: 1;">
                    <label for="permission_{{ $permission->id }}" style="font-size: 0.875rem; font-weight: 500; color: #374151; display: block;">
                      {{ $permission->name }}
                    </label>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                      {{ $permission->description }}
                    </p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>

          <!-- ملاحظات -->
          <div style="background: #eff6ff; border: 1px solid #dbeafe; border-radius: 0.375rem; padding: 1rem; margin-bottom: 1.5rem;">
            <h5 style="font-size: 0.875rem; font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">
              <i class="fas fa-info-circle" style="margin-left: 0.5rem;"></i>
              ملاحظات هامة:
            </h5>
            <ul style="font-size: 0.75rem; color: #374151; margin: 0; padding-right: 1rem;">
              <li>الأدوار تحتوي على مجموعة من الصلاحيات المحددة مسبقاً</li>
              <li>الصلاحيات المباشرة تمنح للمستخدم بشكل فردي</li>
              <li>المستخدم يحصل على جميع صلاحيات الأدوار المعينة له</li>
            </ul>
          </div>

          <div style="display: flex; justify-content: center; gap: 0.75rem; margin-top: 1rem;">
            <a href="{{ route('admin.users.index') }}"
               style="padding: 0.75rem 1.5rem; background-color: #f3f4f6;
                      color: #374151; border-radius: 0.375rem; text-decoration: none;
                      font-size: 0.875rem; transition: background-color 0.2s; display: flex; align-items: center;">
              <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
              رجوع
            </a>
            <button type="submit"
                    style="padding: 0.75rem 1.5rem; background-color: #10b981;
                           color: white; border-radius: 0.375rem; border: none;
                           font-size: 0.875rem; cursor: pointer; transition: background-color 0.2s; display: flex; align-items: center;">
              <i class="fas fa-save" style="margin-left: 0.5rem;"></i>
              حفظ التغييرات
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
