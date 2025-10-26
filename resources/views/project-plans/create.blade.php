{{-- resources/views/admin/project-plans/create.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .section-selector {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 2px solid #e9ecef;
    }
    .section-option {
        padding: 10px 15px;
        margin: 5px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }
    .section-option:hover {
        background-color: #e9ecef;
    }
    .section-option.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .parent-info {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
</style>

<div class="container">
    <h1>إضافة بند جديد</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.project-plans.store',['site'=>$site]) }}" method="POST">
        @csrf

  <!-- قسم تحديد الموقع -->
<div class="section-selector">
    <h5>اختر موقع البند:</h5>
    <div class="row">
        @php
            // $sections يحتوي الآن على جميع الأقسام من قاعدة البيانات
            // بما في ذلك: ما قبل الترسية، الترسية وتوقيع الاتفاقية، التنفيذ، التسليم، وأي قسم جديد
        @endphp

        @foreach($sections as $section)
        <div class="col-md-3">
            <div class="section-option {{ old('parent_section') == $section ? 'active' : '' }}"
                 onclick="selectSection('{{ $section->item_name }}')">
                {{ $section->item_name }}
            </div>
        </div>
        @endforeach
    </div>
    <input type="hidden" name="parent_section" id="parent_section" value="{{ old('parent_section', $sections[0] ?? '') }}">
</div>

        {{-- باقي النموذج بدون تغيير --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="item_type">نوع البند *</label>
                    <select name="item_type" id="item_type" class="form-control" required onchange="toggleParentSelection()">
                        <option value="">اختر نوع البند</option>
                        <option value="section" {{ old('item_type') == 'section' ? 'selected' : '' }}>قسم رئيسي</option>
                        <option value="main" {{ old('item_type') == 'main' ? 'selected' : '' }}>بند رئيسي</option>
                        <option value="sub" {{ old('item_type') == 'sub' ? 'selected' : '' }}>بند فرعي</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="parent_id">البند الأب</label>
                    <select name="parent_id" id="parent_id" class="form-control" onchange="updateParentInfo()">
                        <option value="">بدون أب (رئيسي)</option>
                        @foreach($mainItems as $item)
                            @if($item->parent_section == (old('parent_section', 'ما قبل الترسية')))
                                <option value="{{ $item->id }}"
                                        {{ old('parent_id') == $item->id ? 'selected' : '' }}
                                        data-type="{{ $item->item_type }}"
                                        data-section="{{ $item->parent_section ?? '' }}">
                                    @if($item->item_type == 'section')
                                        [قسم]
                                    @endif
                                    {{ $item->item_name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="parent-info" id="parent-info">
                        @if(old('parent_id'))
                            @php
                                $selectedParent = $mainItems->firstWhere('id', old('parent_id'));
                            @endphp
                            @if($selectedParent)
                                القسم: {{ $selectedParent->parent_section }} | النوع: {{ $selectedParent->item_type }}
                            @endif
                        @else
                            لم يتم اختيار أب
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="item_number">رقم البند</label>
                    <input type="number" name="item_number" id="item_number" class="form-control"
                           value="{{ old('item_number') }}" min="1" max="100">
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="item_name">اسم البند *</label>
                    <input type="text" name="item_name" id="item_name" class="form-control"
                           value="{{ old('item_name') }}" >
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="requirements">المتطلبات</label>
            <textarea name="requirements" id="requirements" class="form-control" rows="2">{{ old('requirements') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="start_date">تاريخ البداية</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="{{ old('start_date') }}" onchange="calculateDuration()">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="end_date">تاريخ النهاية</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="{{ old('end_date') }}" onchange="calculateDuration(); updateStatus()">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="actual_end_date">النهاية المحققة</label>
                    <input type="date" name="actual_end_date" id="actual_end_date" class="form-control"
                           value="{{ old('actual_end_date') }}" onchange="updateStatus()">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="duration">المدة (بالأيام)</label>
                    <input type="text" name="duration" id="duration" class="form-control"
                           value="{{ old('duration') }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="department">الإدارة</label>
                    <select name="department" id="department" class="form-control">
                        <option value="">-- اختر الإدارة --</option>
                        <option value="إدارة التنفيذ" {{ old('department') == 'إدارة التنفيذ' ? 'selected' : '' }}>إدارة التنفيذ</option>
                        <option value="إدارة التطوير" {{ old('department') == 'إدارة التطوير' ? 'selected' : '' }}>إدارة التطوير</option>
                        <option value="الإدارة العليا" {{ old('department') == 'الإدارة العليا' ? 'selected' : '' }}>الإدارة العليا</option>
                        <option value="-" {{ old('department') == '-' ? 'selected' : '' }}>-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="responsible">المسؤول</label>
                    <select name="responsible" id="responsible" class="form-control">
                        <option value="">-- اختر المسؤول --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->name }}" {{ old('responsible') == $user->name ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                        <option value="-" {{ old('responsible') == '-' ? 'selected' : '' }}>-</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات</label>
            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">الحالة *</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="notstarted" {{ old('status') == 'notstarted' ? 'selected' : '' }}>لم يبدأ</option>
                        <option value="inprogress" {{ old('status') == 'inprogress' ? 'selected' : '' }}>قيد التنفيذ</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sort_order">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control"
                           value="{{ old('sort_order', 0) }}" min="0">
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                حفظ البند
            </button>
            <a href="{{ route('admin.project_plan.index',['site'=>$site]) }}" class="btn btn-secondary">
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
    // تحديد القسم وتحديث قائمة الأباء
    function selectSection(sectionName) {
        // تحديث الأزرار النشطة
        document.querySelectorAll('.section-option').forEach(option => {
            option.classList.remove('active');
        });
        event.target.classList.add('active');

        // تحديث الحقل المخفي
        document.getElementById('parent_section').value = sectionName;

        // تصفية قائمة الأباء
        filterParentsBySection(sectionName);
    }

    // تصفية قائمة الأباء حسب القسم
    function filterParentsBySection(section) {
        const parentSelect = document.getElementById('parent_id');
        const options = parentSelect.querySelectorAll('option');

        // إخفاء جميع الخيارات أولاً
        options.forEach(option => {
            if (option.value === '') return; // لا تخفي خيار "بدون أب"
            option.style.display = 'none';
        });

        // إظهار الخيارات التي تنتمي للقسم المحدد
        options.forEach(option => {
            if (option.value === '') return;

            const optionSection = option.getAttribute('data-section');
            if (optionSection === section) {
                option.style.display = '';
            }
        });

        // إعادة تعيين التحديد
        parentSelect.value = '';
        updateParentInfo();
    }

    // تحديث معلومات البند الأب المحدد
    function updateParentInfo() {
        const parentSelect = document.getElementById('parent_id');
        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
        const parentInfo = document.getElementById('parent-info');

        if (selectedOption.value && selectedOption.getAttribute('data-section')) {
            const parentType = selectedOption.getAttribute('data-type') === 'section' ? 'قسم' : 'بند رئيسي';
            const parentSection = selectedOption.getAttribute('data-section');
            parentInfo.innerHTML = `القسم: ${parentSection} | النوع: ${parentType}`;
            parentInfo.className = 'parent-info text-success';
        } else {
            parentInfo.innerHTML = 'لم يتم اختيار أب';
            parentInfo.className = 'parent-info text-muted';
        }
    }

    // تبديل إمكانية اختيار الأب حسب نوع البند
    function toggleParentSelection() {
        const itemType = document.getElementById('item_type').value;
        const parentSelect = document.getElementById('parent_id');

        if (itemType === 'section') {
            parentSelect.disabled = true;
            parentSelect.value = '';
        } else if (itemType === 'main') {
            parentSelect.disabled = false;
            // للأقسام الرئيسية، يمكن أن يكون الأب قسم فقط
            filterParentOptionsByType('section');
        } else if (itemType === 'sub') {
            parentSelect.disabled = false;
            // للبنود الفرعية، يمكن أن يكون الأب بند رئيسي فقط
            filterParentOptionsByType('main');
        }

        updateParentInfo();
    }

    // تصفية خيارات الأباء حسب النوع
    function filterParentOptionsByType(type) {
        const parentSelect = document.getElementById('parent_id');
        const options = parentSelect.querySelectorAll('option');
        const currentSection = document.getElementById('parent_section').value;

        options.forEach(option => {
            if (option.value === '') return;

            const optionType = option.getAttribute('data-type');
            const optionSection = option.getAttribute('data-section');

            if (optionType === type && optionSection === currentSection) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });

        // إعادة تعيين التحديد إذا كان الخيار غير متاح
        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
        if (selectedOption.value && selectedOption.style.display === 'none') {
            parentSelect.value = '';
            updateParentInfo();
        }
    }

    // حساب المدة
    function calculateDuration() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const durationInput = document.getElementById('duration');

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            durationInput.value = diffDays + ' يوم';
        } else {
            durationInput.value = '';
        }
    }

    // تحديث الحالة تلقائياً بناءً على تاريخ النهاية المحققة
    function updateStatus() {
        const actualEndDate = document.getElementById('actual_end_date').value;
        const endDate = document.getElementById('end_date').value;
        const statusSelect = document.getElementById('status');

        if (actualEndDate) {
            // إذا كان هناك تاريخ للنهاية المحققة، فهذا يعني أن المهمة مكتملة
            statusSelect.value = 'completed';
        } else if (endDate) {
            const today = new Date();
            const end = new Date(endDate);

            if (today > end) {
                // إذا تجاوزنا تاريخ النهاية ولم يتم إدخال تاريخ النهاية المحققة
                statusSelect.value = 'inprogress';
            }
        }
    }

    // التهيئة الأولية
    document.addEventListener('DOMContentLoaded', function() {
        // تصفية الأباء حسب القسم الافتراضي
        const defaultSection = document.getElementById('parent_section').value;
        filterParentsBySection(defaultSection);

        // تهيئة باقي الدوال
        toggleParentSelection();
        calculateDuration();
        updateParentInfo();
    });
</script>
@endsection
