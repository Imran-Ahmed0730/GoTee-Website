@extends('admin.master')
@section('title', isset($item) ? 'Edit Employee' : 'Create Employee')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.employee.index') }}"><i class="fa-solid fa-users" aria-hidden="true"></i><span>Employees</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit Employee' : 'Create Employee' }}</h1>
                <a href="{{ route('admin.employee.index') }}" class="btn btn-primary">&larr; Back to Employees</a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ isset($item) ? route('admin.employee.update', $item) : route('admin.employee.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif
                    <ul class="nav nav-tabs" id="employeeForm" role="formTabs">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-info-tab" data-bs-toggle="tab" data-bs-target="#personalInfo" type="button" role="tab" aria-controls="personalInfo" aria-selected="true">Personal Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="emergency-contact-info-tab" data-bs-toggle="tab" data-bs-target="#emergencyContactInfo" type="button" role="tab" aria-controls="emergencyContactInfo" aria-selected="false">Emergency Contact Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employment-info-tab" data-bs-toggle="tab" data-bs-target="#employmentInfo" type="button" role="tab" aria-controls="employmentInfo" aria-selected="false">Employment Information</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-4" id="myTabContent">
                        <div class="tab-pane fade show active" id="personalInfo" role="tabpanel" aria-labelledby="personal-info-tab">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $item->user->first_name ?? '') }}" placeholder="Enter employee first name" class="form-control @error('first_name') is-invalid @enderror" maxlength="255" required autofocus autocomplete="given-name">
                                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $item->user->last_name ?? '') }}" placeholder="Enter employee last name" class="form-control @error('last_name') is-invalid @enderror" maxlength="255" autocomplete="family-name">
                                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input id="email" name="email" type="email" value="{{ old('email', $item->user->email ?? '') }}" placeholder="Enter employee email" class="form-control @error('email') is-invalid @enderror" maxlength="255" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input id="phone" name="phone" type="text" value="{{ old('phone', $item->phone ?? '') }}" placeholder="Enter employee phone number" class="form-control @error('phone') is-invalid @enderror" maxlength="20" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="father_name" class="form-label">Father's Name <span class="text-danger">*</span></label>
                                    <input id="father_name" name="father_name" type="text" value="{{ old('father_name', $item->father_name ?? '') }}" placeholder="Enter father's name" class="form-control @error('father_name') is-invalid @enderror" maxlength="255" required>
                                    @error('father_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                    <input id="mother_name" name="mother_name" type="text" value="{{ old('mother_name', $item->mother_name ?? '') }}" placeholder="Enter mother's name" class="form-control @error('mother_name') is-invalid @enderror" maxlength="255" required>
                                    @error('mother_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="present_address" class="form-label">Present Address <span class="text-danger">*</span></label>
                                    <textarea id="present_address" name="present_address" rows="4" placeholder="Enter present address" class="form-control @error('present_address') is-invalid @enderror" maxlength="5000" required>{{ old('present_address', $item->present_address ?? '') }}</textarea>
                                    @error('present_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="permanent_address" class="form-label">Permanent Address <span class="text-danger">*</span></label>
                                    <textarea id="permanent_address" name="permanent_address" rows="4" placeholder="Enter permanent address" class="form-control @error('permanent_address') is-invalid @enderror" maxlength="5000" required>{{ old('permanent_address', $item->permanent_address ?? '') }}</textarea>
                                    @error('permanent_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                        <option value="">Select gender</option>
                                        <option value="1" @selected((string) old('gender', $item->gender ?? '') === '1')>Male</option>
                                        <option value="2" @selected((string) old('gender', $item->gender ?? '') === '2')>Female</option>
                                        <option value="3" @selected((string) old('gender', $item->gender ?? '') === '3')>Other</option>
                                    </select>
                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="profile_photo_path" class="form-label">Profile Image @if (! isset($item))<span class="text-danger">*</span>@endif</label>
                                    <input id="profile_photo_path" name="profile_photo_path" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('profile_photo_path') is-invalid @enderror" @required(! isset($item))>
                                    <div class="form-text">JPG, PNG, or WebP; maximum 5 MB.</div>
                                    @if (isset($item) && $item->user->profile_photo_path && file_exists(public_path($item->user->profile_photo_path)))
                                        <div class="form-text">Choose a file only if you want to replace the current profile image.</div>
                                    @endif
                                    @error('profile_photo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if (!empty($item?->user?->profile_photo_path) && file_exists(public_path($item->user->profile_photo_path)))
                                        <img src="{{ asset($item->user->profile_photo_path) }}" alt="{{ $item->user->first_name }} {{ $item->user->last_name }}" id="preview-profile" class="img-thumbnail mt-3" style="max-width: 240px; max-height: 120px;">
                                    @else
                                        <img src="{{ asset('images/blog-category-placeholder.svg') }}" alt="Employee profile image preview" id="preview-profile" class="img-thumbnail mt-3" style="max-width: 240px; max-height: 120px;">
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="blood_group_id" class="form-label">Blood Group</label>
                                    <select name="blood_group_id" id="blood_group_id" class="form-select @error('blood_group_id') is-invalid @enderror">
                                        <option value="">Select blood group</option>
                                        @foreach ($bloodGroups as $bloodGroup)
                                            <option value="{{ $bloodGroup->id }}" @selected((string) old('blood_group_id', $item->blood_group_id ?? '') === (string) $bloodGroup->id)>
                                                {{ $bloodGroup->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('blood_group_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nid_no" class="form-label">NID Number <span class="text-danger">*</span></label>
                                    <input id="nid_no" name="nid_no" type="text" value="{{ old('nid_no', $item->nid_no ?? '') }}" placeholder="Enter NID number" class="form-control @error('nid_no') is-invalid @enderror" maxlength="255" required>
                                    @error('nid_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nid_image" class="form-label">NID Image @if (! isset($item))<span class="text-danger">*</span>@endif</label>
                                    <input id="nid_image" name="nid_image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('nid_image') is-invalid @enderror" @required(! isset($item))>
                                    <div class="form-text">JPG, PNG, or WebP; maximum 5 MB. The file is stored privately.</div>
                                    @if (isset($item) && $item->nid_image)
                                        <div class="form-text">A private NID image is already uploaded. Choose a file only to replace it.</div>
                                    @endif
                                    @error('nid_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="emergencyContactInfo" role="tabpanel" aria-labelledby="emergency-contact-info-tab">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contacting_person_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input id="contacting_person_first_name" name="contacting_person_first_name" type="text" value="{{ old('contacting_person_first_name', $item->contacting_person_first_name ?? '') }}" placeholder="Enter contacting person's first name" class="form-control @error('contacting_person_first_name') is-invalid @enderror" maxlength="255" required>
                                    @error('contacting_person_first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contacting_person_last_name" class="form-label">Last Name </label>
                                    <input id="contacting_person_last_name" name="contacting_person_last_name" type="text" value="{{ old('contacting_person_last_name', $item->contacting_person_last_name ?? '') }}" placeholder="Enter contacting person's last name" class="form-control @error('contacting_person_last_name') is-invalid @enderror" maxlength="255">
                                    @error('contacting_person_last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contacting_person_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input id="contacting_person_phone" name="contacting_person_phone" type="text" value="{{ old('contacting_person_phone', $item->contacting_person_phone ?? '') }}" placeholder="Enter contacting person's phone number" class="form-control @error('contacting_person_phone') is-invalid @enderror" maxlength="255" required>
                                    @error('contacting_person_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contacting_person_relation" class="form-label">Relation <span class="text-danger">*</span></label>
                                    <input id="contacting_person_relation" name="contacting_person_relation" type="text" value="{{ old('contacting_person_relation', $item->contacting_person_relation ?? '') }}" placeholder="Enter contacting person's relation" class="form-control @error('contacting_person_relation') is-invalid @enderror" maxlength="255" required>
                                    @error('contacting_person_relation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="contacting_person_address" class="form-label">Address </label>
                                    <textarea id="contacting_person_address" name="contacting_person_address" rows="4" placeholder="Enter contacting person's address" class="form-control @error('contacting_person_address') is-invalid @enderror" maxlength="5000">{{ old('contacting_person_address', $item->contacting_person_address ?? '') }}</textarea>
                                    @error('contacting_person_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="employmentInfo" role="tabpanel" aria-labelledby="employment-info-tab">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reporting_manager_id" class="form-label">Reporting Manager</label>
                                    <select name="reporting_manager_id" id="reporting_manager_id" class="form-select @error('reporting_manager_id') is-invalid @enderror">
                                        <option value="">Select reporting manager</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->user_id }}" @selected((string) old('reporting_manager_id', $item->reporting_manager_id ?? '') === (string) $employee->user_id)>
                                                {{ $employee?->user?->first_name }} {{ $employee?->user?->last_name }} {{ $employee->employee_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reporting_manager_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
                                        <option value="">Select department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @selected((string) old('department_id', $item->department_id ?? '') === (string) $department->id)>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="designation_id" class="form-label">Designation</label>
                                    <select name="designation_id" id="designation_id" class="form-select @error('designation_id') is-invalid @enderror">
                                        <option value="">Select designation</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}" @selected((string) old('designation_id', $item->designation_id ?? '') === (string) $designation->id)>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('designation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                                    <input id="joining_date" name="joining_date" type="date" value="{{ old('joining_date', isset($item) ? $item->joining_date->format('Y-m-d') : '') }}" class="form-control @error('joining_date') is-invalid @enderror" required>
                                    @error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                                    <input id="salary" name="salary" type="number" step="0.01" min="0" value="{{ old('salary', $item->salary ?? '0.00') }}" placeholder="Enter salary" class="form-control @error('salary') is-invalid @enderror" required>
                                    @error('salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <fieldset class="col-md-6 mb-4">
                                    <legend class="form-label">Team visibility</legend>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="show_as_team" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="show_as_team" id="show_as_team" value="1" @checked((string) old('show_as_team', isset($item) ? $item->getRawOriginal('show_as_team') : '0') === '1')>
                                        <label class="form-check-label" for="show_as_team">Show as a team member</label>
                                    </div>
                                    @error('show_as_team')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </fieldset>
                                <fieldset class="col-md-6 mb-4">
                                    <legend class="form-label">Status</legend>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="status" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" @checked((string) old('status', isset($item) ? $item->getRawOriginal('status') : '1') === '1')>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                    @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </fieldset>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.employee.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create employee' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
