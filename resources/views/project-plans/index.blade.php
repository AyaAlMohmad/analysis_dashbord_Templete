@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* نفس الـ CSS السابق مع إضافة تنسيق لصف الإجراءات */
        .section-actions {
            background-color: #f8f9fa !important;
            border-bottom: 2px solid #2F5496;
        }

        .section-actions td {
            padding: 8px !important;
            text-align: center;
        }

        .section-action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        /* باقي الـ CSS كما هو */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: white;
            padding: 10px;
            direction: rtl;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            overflow-x: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding: 15px;
            background: #2F5496;
            color: white;
            border-radius: 4px;
            font-size: 16px;
            position: relative;
        }

        .logo-container {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }

        .logo {
            max-height: 60px;
            max-width: 150px;
        }

        .header-content {
            margin: 0 200px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 2px solid #2F5496;
            font-size: 12px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #A6A6A6;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
            line-height: 1.3;
        }

        th {
            background-color: #2F5496;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            font-size: 12px;
            border: 1px solid #A6A6A6;
            padding: 8px 6px;
        }

        .requirements-completed {
            background-color: #92D050 !important;
            color: #000;
            font-weight: bold;
        }

        .requirements-inprogress {
            background-color: #FFC000 !important;
            color: #000;
            font-weight: bold;
        }

        .requirements-notstarted {
            background-color: #FFFFFF !important;
            color: #000;
        }

        .section-title {
            background-color: #2F5496 !important;
            color: white !important;
            font-size: 16px !important;
            font-weight: bold !important;
        }

        .section-title td {
            padding: 12px !important;
            font-size: 16px !important;
        }

        .main-item td {
            font-weight: bold;
        }

        .sub-item .col-item {
            padding-right: 40px !important;
        }

        .notes-cell {
            text-align: right;
            max-width: 250px;
            white-space: normal;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* أعمدة محددة */
        .col-id {
            width: 4%;
            min-width: 40px;
        }

        .col-item {
            width: 18%;
            text-align: right;
            min-width: 180px;
        }

        .col-requirements {
            width: 18%;
            text-align: right;
            min-width: 180px;
        }

        .col-start,
        .col-end,
        .col-updated {
            width: 8%;
            min-width: 90px;
        }

        .col-duration {
            width: 6%;
            min-width: 70px;
        }

        .col-department {
            width: 10%;
            text-align: right;
            min-width: 110px;
        }

        .col-responsible {
            width: 12%;
            text-align: right;
            min-width: 130px;
        }

        .col-notes {
            width: 16%;
            text-align: right;
            min-width: 160px;
        }

        .col-actions {
            width: 10%;
            min-width: 100px;
        }

        .empty-cell:before {
            content: "-";
            color: #A6A6A6;
        }

        .date-cell {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: normal;
        }

        /* أزرار التحكم */
        .controls {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn {
            background: #2F5496;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background 0.3s;
        }

        .btn:hover {
            background: #1e3a6d;
        }

        .btn-excel {
            background: #107c41;
        }

        .btn-excel:hover {
            background: #0d6635;
        }

        .btn-print {
            background: #17a2b8;
        }

        .btn-print:hover {
            background: #138496;
        }

        .btn-add {
            background: #28a745;
        }

        .btn-add:hover {
            background: #218838;
        }

        .btn-edit {
            background: #ffc107;
            color: #000;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-complete {
            background: #28a745;
            color: white;
        }

        .btn-complete:hover {
            background: #218838;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 3px;
        }

        /* تنسيقات الطباعة */
        @media print {

            .controls,
            .action-buttons,
            .section-actions {
                display: none !important;
            }

            body {
                padding: 0;
                margin: 0;
                background: white;
            }

            .container {
                max-width: 100%;
                margin: 0;
            }

            .header {
                margin-bottom: 10px;
                page-break-after: avoid;
            }

            table {
                page-break-inside: auto;
                border: 2px solid #2F5496 !important;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th {
                background-color: #2F5496 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .requirements-completed {
                background-color: #92D050 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .requirements-inprogress {
                background-color: #FFC000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .section-title {
                background-color: #2F5496 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .main-item {
                background-color: #E6E6E6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .sub-item {
                background-color: #F8F9FA !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* تنسيقات للأزرار المخفية */
        .no-permission {
            color: #6c757d;
            font-style: italic;
        }

        .permission-denied {
            color: #6c757d;
            font-style: italic;
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* تنسيق زر الإكمال */
        .complete-form {
            display: inline;
        }

        .col-status {
            width: 12% !important;
            min-width: 150px !important;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin: 2px;
            min-width: 80px;
        }

        .status-dropdown {
            width: 100%;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            background: white;
            cursor: pointer;
            text-align: center;
        }

        .status-dropdown:focus {
            outline: none;
            border-color: #2F5496;
        }

        .status-dropdown.completed {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }

        .status-dropdown.inprogress {
            background-color: #ffc107;
            color: black;
            border-color: #ffc107;
        }

        .status-dropdown.notstarted {
            background-color: #6c757d;
            color: white;
            border-color: #6c757d;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-inprogress {
            background-color: #ffc107;
            color: black;
        }

        .status-notstarted {
            background-color: #6c757d;
            color: white;
        }

        .status-select-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .status-display {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            min-width: 100px;
        }
    </style>



    <div class="container">

        @if (auth()->user()->canExportData())
            <button class="btn btn-excel" onclick="exportToExcel()">📊 تصدير Excel</button>
        @endif

        @if (auth()->user()->canCreateProjects())
            <a href="{{ route('admin.project-plans.create', ['site' => $site]) }}" class="btn btn-add">➕ إضافة بند</a>
        @endif



        <div class="header">
            <div class="logo-container">
                @if ($site == 'jeddah')
                <img src="{{ asset('images/JeddahLogo.png') }}" class="logo" alt="Logo">
                @elseif ($site == 'dhahran')
             <img src="{{ asset('images/logo4.png') }}" class="logo"  alt="Azyan Al Dhahran">
             @elseif ($site == 'bashaer')
               <img src="{{ asset('images/logo3.png') }}" class="logo"  alt="Azyan Al Bashaer">
             @elseif ($site == 'alfursan')
             <img src="{{ asset('images/alfursanWhite.png') }}" class="logo"  alt="Azyan Al Bashaer">
                @endif
            </div>
            <div class="header-content">
                <h1>خطة متابعة مشروع {{ $siteName }} (تطوير بنية فوقية)</h1>
                <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($mainSections->count() > 0)
            <table id="project-table">
                <thead>
                    <tr>
                        <th class="col-id">م</th>
                        <th class="col-item">البند</th>
                        <th class="col-requirements">المتطلبات</th>
                        <th class="col-start">البداية</th>
                        <th class="col-end">النهاية</th>
                        <th class="col-updated">النهاية المحققة</th>
                        <th class="col-duration">المدة</th>
                        <th class="col-department">الإدارة</th>
                        <th class="col-responsible">المسؤول</th>
                        <th class="col-notes">ملاحظات</th>
                        <th class="col-status">الحالة</th>
                        @if (auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects())
                            <th class="col-actions">الإجراءات</th>
                        @else
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mainSections as $section)
                        <tr class="section-title">
                            <td
                                colspan="{{ auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects() ? '11' : '11' }}">
                                <strong>{{ $section->item_name }}</strong>
                                @if ($section->responsible)
                                    <br><small>المسؤول: {{ $section->responsible }}</small>
                                @endif
                            </td>


                            @if (auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects())
                                <td colspan="{{ auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects() ? '12' : '11' }}"
                                    class="text-center action-buttons">
                                    <div class="section-action-buttons">
                                        @if (auth()->user()->canEditProjects())
                                            <a href="{{ route('admin.project-plans.edit',['site' => $site, $section->id]) }}"
                                                class="btn btn-edit btn-small" title="تعديل القسم">✏️ تعديل القسم</a>
                                        @else
                                            <span class="permission-denied" title="لا تملك صلاحية التعديل">✏️ تعديل
                                                القسم</span>
                                        @endif

                                        @if (auth()->user()->canDeleteProjects())
                                            <form action="{{ route('admin.project-plans.destroy', ['site' => $site, $section->id]) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete btn-small"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا القسم وجميع بنوده؟')"
                                                    title="حذف القسم">🗑️ حذف القسم</button>
                                            </form>
                                        @else
                                            <span class="permission-denied" title="لا تملك صلاحية الحذف">🗑️ حذف
                                                القسم</span>
                                        @endif
                                    </div>
                                </td>
                        </tr>
                    @endif
                    </tr>
                    @php
                        $mainItems = $allPlans
                            ->where('parent_section', $section->parent_section)
                            ->where('item_type', 'main')
                            ->where('parent_id', null)
                            ->sortBy('sort_order');
                    @endphp

                    @foreach ($mainItems as $mainItem)
                        @php
                            $subItems = $allPlans
                                ->where('parent_id', $mainItem->id)
                                ->where('item_type', 'sub')
                                ->sortBy('sort_order');

                            $subItemsCount = $subItems->count();

                            $isResponsible = auth()->user()->name == $mainItem->responsible;
                            $canEditItem =
                                auth()->user()->canEditProjects() ||
                                (auth()->user()->canEditOwnProjects() && $isResponsible);
                            $canDeleteItem =
                                auth()->user()->canDeleteProjects() ||
                                (auth()->user()->canDeleteOwnProjects() && $isResponsible);
                            $canChangeStatus =
                                auth()->user()->canUpdateStatus() ||
                                (auth()->user()->canUpdateOwnStatus() && $isResponsible);

                            $currentStatus = str_replace('status-', '', $mainItem->status_class);
                        @endphp

                        <!-- البند الرئيسي مع البنود الفرعية -->
                        @if ($subItemsCount > 0)
                            <tr class="main-item">
                                <td rowspan="{{ $subItemsCount + 1 }}">
                                    {{ $mainItem->item_number ?? $loop->parent->iteration . '.' . $loop->iteration }}
                                </td>
                                <td class="col-item text-right" rowspan="{{ $subItemsCount + 1 }}">
                                    <strong>{{ $mainItem->item_name }}</strong>
                                    @if ($isResponsible)
                                        <span class="permission-badge">مسؤول</span>
                                    @endif
                                </td>

                                <td class="col-requirements text-right requirements-{{ $currentStatus }}">
                                    {{ $mainItem->requirements ?? '-' }}
                                </td>
                                <td class="date-cell">{{ $mainItem->safe_start_date }}</td>
                                <td class="date-cell">{{ $mainItem->safe_end_date }}</td>
                                <td class="date-cell">
                                    {{ $mainItem->updated_end_date ? $mainItem->updated_end_date->format('Y-m-d') : '-' }}
                                </td>
                                <td>{{ $mainItem->duration ?: '-' }}</td>
                                <td class="col-department text-right">{{ $mainItem->department ?: '-' }}</td>
                                <td class="col-responsible text-right">{{ $mainItem->responsible ?: '-' }}</td>
                                <td class="col-notes notes-cell">{{ $mainItem->notes }}</td>
                                <td class="col-status text-center">
                                    @if ($canChangeStatus)
                                        <select class="status-dropdown {{ $currentStatus }}"
                                            data-item-id="{{ $mainItem->id }}" onchange="updateStatus(this)">
                                            <option value="notstarted"
                                                {{ $currentStatus === 'notstarted' ? 'selected' : '' }}>لم يبدأ</option>
                                            <option value="inprogress"
                                                {{ $currentStatus === 'inprogress' ? 'selected' : '' }}>قيد التنفيذ
                                            </option>
                                            <option value="completed"
                                                {{ $currentStatus === 'completed' ? 'selected' : '' }}>تم</option>
                                        </select>
                                    @else
                                        <span class="status-display status-{{ $currentStatus }}">
                                            @if ($currentStatus === 'completed')
                                                تم
                                            @elseif($currentStatus === 'inprogress')
                                                قيد التنفيذ
                                            @else
                                                لم يبدأ
                                            @endif
                                        </span>
                                    @endif
                                </td>

                                @if (auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects())
                                    <td class="col-actions action-buttons" rowspan="{{ $subItemsCount + 1 }}">
                                        @if ($canEditItem)
                                            <a href="{{ route('admin.project-plans.edit',['site' => $site,$mainItem->id]) }}"
                                                class="btn btn-edit btn-small" title="تعديل البند">✏️</a>
                                        @else
                                            <span class="permission-denied" title="لا تملك صلاحية التعديل">✏️</span>
                                        @endif

                                        @if ($canDeleteItem)
                                            <form action="{{ route('admin.project-plans.destroy',['site'=>$site,$mainItem->id]) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete btn-small"
                                                    onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                    title="حذف البند">🗑️</button>
                                            </form>
                                        @else
                                            <span class="permission-denied" title="لا تملك صلاحية الحذف">🗑️</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>

                            <!-- البنود الفرعية -->
                            @foreach ($subItems as $subItem)
                                @php
                                    $isSubResponsible = auth()->user()->name == $subItem->responsible;
                                    $canEditSubItem =
                                        auth()->user()->canEditProjects() ||
                                        (auth()->user()->canEditOwnProjects() && $isSubResponsible);
                                    $canDeleteSubItem =
                                        auth()->user()->canDeleteProjects() ||
                                        (auth()->user()->canDeleteOwnProjects() && $isSubResponsible);
                                    $canChangeSubStatus =
                                        auth()->user()->canUpdateStatus() ||
                                        (auth()->user()->canUpdateOwnStatus() && $isSubResponsible);

                                    $subCurrentStatus = str_replace('status-', '', $subItem->status_class);
                                @endphp

                                <tr class="sub-item">
                                    <td class="col-requirements text-right requirements-{{ $subCurrentStatus }}">
                                        {{ $subItem->requirements ?? '-' }}
                                    </td>
                                    <td class="date-cell">{{ $subItem->safe_start_date }}</td>
                                    <td class="date-cell">{{ $subItem->safe_end_date }}</td>
                                    <td class="date-cell">
                                        {{ $subItem->updated_end_date ? $subItem->updated_end_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td>{{ $subItem->duration ?: '-' }}</td>
                                    <td class="col-department text-right">{{ $subItem->department ?: '-' }}</td>
                                    <td class="col-responsible text-right">
                                        {{ $subItem->responsible ?: '-' }}
                                        @if ($isSubResponsible)
                                            <span class="permission-badge">مسؤول</span>
                                        @endif
                                    </td>
                                    <td class="col-notes notes-cell">{{ $subItem->notes }}</td>
                                    <td class="col-status text-center">
                                        @if ($canChangeSubStatus)
                                            <select class="status-dropdown {{ $subCurrentStatus }}"
                                                data-item-id="{{ $subItem->id }}" onchange="updateStatus(this)">
                                                <option value="notstarted"
                                                    {{ $subCurrentStatus === 'notstarted' ? 'selected' : '' }}>لم يبدأ
                                                </option>
                                                <option value="inprogress"
                                                    {{ $subCurrentStatus === 'inprogress' ? 'selected' : '' }}>قيد التنفيذ
                                                </option>
                                                <option value="completed"
                                                    {{ $subCurrentStatus === 'completed' ? 'selected' : '' }}>تم</option>
                                            </select>
                                        @else
                                            <span class="status-display status-{{ $subCurrentStatus }}">
                                                @if ($subCurrentStatus === 'completed')
                                                    تم
                                                @elseif($subCurrentStatus === 'inprogress')
                                                    قيد التنفيذ
                                                @else
                                                    لم يبدأ
                                                @endif
                                            </span>
                                        @endif
                                    </td>

                                    @if (auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects())
                                        <td class="col-actions action-buttons">
                                            @if ($canEditSubItem)
                                               <a href="{{ route('admin.project-plans.edit',['site'=>$site,$subItem->id]) }}"
                                                    class="btn btn-edit btn-small" title="تعديل البند الفرعي">✏️</a>
                                            @else
                                                <span class="permission-denied" title="لا تملك صلاحية التعديل">✏️</span>
                                            @endif

                                            @if ($canDeleteSubItem)
                                                <form action="{{ route('admin.project-plans.destroy',['site'=>$site,$subItem->id]) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-delete btn-small"
                                                        onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                        title="حذف البند الفرعي">🗑️</button>
                                                </form>
                                            @else
                                                <span class="permission-denied" title="لا تملك صلاحية الحذف">🗑️</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <!-- إذا لم يكن هناك بنود فرعية -->
                            @php
                                $isResponsible = auth()->user()->name == $mainItem->responsible;
                                $canEditItem =
                                    auth()->user()->canEditProjects() ||
                                    (auth()->user()->canEditOwnProjects() && $isResponsible);
                                $canDeleteItem =
                                    auth()->user()->canDeleteProjects() ||
                                    (auth()->user()->canDeleteOwnProjects() && $isResponsible);
                                $canChangeStatus =
                                    auth()->user()->canUpdateStatus() ||
                                    (auth()->user()->canUpdateOwnStatus() && $isResponsible);

                                $currentStatus = str_replace('status-', '', $mainItem->status_class);
                            @endphp

                            <tr class="main-item">
                                <td>{{ $mainItem->item_number ?? $loop->parent->iteration . '.' . $loop->iteration }}</td>
                                <td class="col-item text-right">
                                    <strong>{{ $mainItem->item_name }}</strong>
                                    @if ($isResponsible)
                                        <span class="permission-badge">مسؤول</span>
                                    @endif
                                </td>

                                <td class="col-requirements text-right requirements-{{ $currentStatus }}">
                                    {{ $mainItem->requirements ?? '-' }}
                                </td>
                                <td class="date-cell">{{ $mainItem->safe_start_date }}</td>
                                <td class="date-cell">{{ $mainItem->safe_end_date }}</td>
                                <td class="date-cell">
                                    {{ $mainItem->actual_end_date ? $mainItem->actual_end_date->format('Y-m-d') : '-' }}
                                </td>
                                <td>{{ $mainItem->duration ?: '-' }}</td>
                                <td class="col-department text-right">{{ $mainItem->department ?: '-' }}</td>
                                <td class="col-responsible text-right">{{ $mainItem->responsible ?: '-' }}</td>
                                <td class="col-notes notes-cell">{{ $mainItem->notes }}</td>
                                <td class="col-status text-center">
                                    @if ($canChangeStatus)
                                        <select class="status-dropdown {{ $currentStatus }}"
                                            data-item-id="{{ $mainItem->id }}" onchange="updateStatus(this)">
                                            <option value="notstarted"
                                                {{ $currentStatus === 'notstarted' ? 'selected' : '' }}>لم يبدأ</option>
                                            <option value="inprogress"
                                                {{ $currentStatus === 'inprogress' ? 'selected' : '' }}>قيد التنفيذ
                                            </option>
                                            <option value="completed"
                                                {{ $currentStatus === 'completed' ? 'selected' : '' }}>تم</option>
                                        </select>
                                    @else
                                        <span class="status-display status-{{ $currentStatus }}">
                                            @if ($currentStatus === 'completed')
                                                تم
                                            @elseif($currentStatus === 'inprogress')
                                                قيد التنفيذ
                                            @else
                                                لم يبدأ
                                            @endif
                                        </span>
                                    @endif
                                </td>

                                @if (auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects())
                                    <td class="col-actions action-buttons">
                                        @if ($canEditItem)
                                            <a href="{{ route('admin.project-plans.edit',['site'=>$site,$mainItem->id]) }}"
                                                class="btn btn-edit btn-small" title="تعديل البند">✏️</a>
                                        @else
                                            <span class="permission-denied" title="لا تملك صلاحية التعديل">✏️</span>
                                        @endif

                                        @if ($canDeleteItem)
                                            <form action="{{ route('admin.project-plans.destroy',['site'=>$site,$mainItem->id]) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete btn-small"
                                                    onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                    title="حذف البند">🗑️</button>
                                            </form>
                                        @else
                                            <span class="permission-denied" title="لا تملك صلاحية الحذف">🗑️</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @endforeach

                    @if ($mainItems->count() == 0)
                        <tr>
                            <td colspan="{{ auth()->user()->canEditProjects() || auth()->user()->canDeleteProjects() ? '12' : '11' }}"
                                class="text-center text-muted">
                                لا توجد بنود في هذا القسم
                            </td>
                        </tr>
                    @endif
        @endforeach
        </tbody>
        </table>
    @else
        <div class="alert alert-info text-center">
            <h4>لا توجد أقسام</h4>
            <p>لم يتم إضافة أي أقسام بعد. يجب إنشاء الأقسام الرئيسية أولاً.</p>
            @if (auth()->user()->canCreateProjects())
                <a href="{{ route('admin.project-plans.create',['site'=>$site]) }}" class="btn btn-primary">إنشاء قسم جديد</a>
            @else
                <p class="text-muted">ليس لديك صلاحية لإنشاء أقسام جديدة</p>
            @endif
        </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function exportToExcel() {
            @if (!auth()->user()->canExportData())
                alert('ليس لديك صلاحية لتصدير البيانات');
                return;
            @endif

            const table = document.getElementById('project-table');
            if (table) {
                const ws = XLSX.utils.table_to_sheet(table);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "خطة المشروع");

                const timestamp = new Date().toISOString().slice(0, 10);
                XLSX.writeFile(wb, `خطة_مشروع_{{ $siteName }}_${timestamp}.xlsx`);
            } else {
                alert('لا توجد بيانات لتصديرها');
            }
        }

        function updateStatus(selectElement) {
            @if (!auth()->user()->canUpdateStatus())
                alert('ليس لديك صلاحية لتحديث الحالة');
                return;
            @endif

            const itemId = selectElement.getAttribute('data-item-id');
            const newStatus = selectElement.value;
            const currentClass = selectElement.className;

            let message = '';
            if (newStatus === 'completed') {
                message = 'هل تريد وضع علامة تم؟';
            } else if (newStatus === 'inprogress') {
                message = 'هل تريد وضع علامة قيد التنفيذ؟';
            } else {
                message = 'هل تريد وضع علامة لم يبدأ؟';
            }

            if (!confirm(message)) {
                const previousStatus = currentClass.replace('status-dropdown ', '').replace('completed', '').replace(
                    'inprogress', '').replace('notstarted', '').trim();
                selectElement.value = previousStatus;
                return;
            }

            fetch(`/admin/project-plans/${itemId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        selectElement.className = `status-dropdown ${newStatus}`;

                        const requirementsCell = selectElement.closest('tr').querySelector('.col-requirements');
                        if (requirementsCell) {
                            requirementsCell.className = `col-requirements text-right requirements-${newStatus}`;
                        }

                        alert('تم تحديث الحالة بنجاح');
                    } else {
                        alert('حدث خطأ: ' + data.message);
                        const previousStatus = currentClass.replace('status-dropdown ', '').replace('completed', '')
                            .replace('inprogress', '').replace('notstarted', '').trim();
                        selectElement.value = previousStatus;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ في الاتصال');
                    const previousStatus = currentClass.replace('status-dropdown ', '').replace('completed', '')
                        .replace('inprogress', '').replace('notstarted', '').trim();
                    selectElement.value = previousStatus;
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.status-dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('focus', function() {
                    this.style.boxShadow = '0 0 5px rgba(47, 84, 150, 0.5)';
                });

                dropdown.addEventListener('blur', function() {
                    this.style.boxShadow = 'none';
                });
            });
        });
    </script>
@endsection
