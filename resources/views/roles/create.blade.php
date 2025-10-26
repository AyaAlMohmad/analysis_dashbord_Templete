@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div style="padding: 3rem 0;">
  <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

      <!-- Modal Content -->
      <div style="position: relative; z-index: 50; width: 100%; max-width: 42rem; margin: 2rem auto;
                background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">

        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">
          إضافة دور جديد
        </h3>

        <form method="POST" action="{{ route('admin.roles.store') }}" style="display: grid; gap: 1.25rem;">
          @csrf

          <div style="display: grid; gap: 0.5rem;">
            <label style="font-size: 0.875rem; color: #374151; font-weight: 500;">اسم الدور</label>
            <input type="text" name="name" required
                   style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
                          border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);"
                   placeholder="أدخل اسم الدور">
          </div>

          <div style="display: grid; gap: 0.5rem;">
            <label style="font-size: 0.875rem; color: #374151; font-weight: 500;">الوصف</label>
            <textarea name="description"
                      style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db;
                             border-radius: 0.375rem; font-size: 0.875rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);"
                      placeholder="أدخل وصفاً للدور" rows="3"></textarea>
          </div>

          <!-- الصلاحيات -->
          <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.25rem; background: #f9fafb;">
            <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #374151; text-align: center;">
              <i class="fas fa-key" style="margin-left: 0.5rem;"></i>
              الصلاحيات
            </h4>
            <div style="display: grid; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
              @foreach($permissions as $permission)
              <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: white; border-radius: 0.375rem;">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}"
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

          <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
            <a href="{{ route('admin.roles.index') }}"
               style="padding: 0.625rem 1.25rem; background-color: #f3f4f6;
                      color: #374151; border-radius: 0.375rem; text-decoration: none;
                      font-size: 0.875rem; transition: background-color 0.2s;">
              إلغاء
            </a>
            <button type="submit"
                    style="padding: 0.625rem 1.25rem; background-color: #2563eb;
                           color: white; border-radius: 0.375rem; border: none;
                           font-size: 0.875rem; cursor: pointer; transition: background-color 0.2s;">
              حفظ
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
