@extends('layouts.app')

@section('content')
@if(auth()->user()->role === 'admin')
<div class="container mt-5" x-data="{
    query: '',
    selected: null,
    editing: false,
    students: [
        { id: '2026-00123', name: 'Juan Dela Cruz', program: 'BS Information Technology', year_level: '2', semester: '1', sex: 'male', applicant_type: 'new', birthdate: '2006-03-14', address: 'Blk 4 Lot 12, Sampaguita St., Quezon City', email: 'juan.delacruz@email.com', contact_number: '0917-123-4567', emergency_contact: 'Rosa Dela Cruz / 0917-765-4321', last_school: 'Quezon City Science HS' },
        { id: '2026-00124', name: 'Maria Santos', program: 'BS Computer Science', year_level: '3', semester: '1', sex: 'female', applicant_type: 'old', birthdate: '2005-07-22', address: '123 Mabini St., Manila', email: 'maria.santos@email.com', contact_number: '0918-234-5678', emergency_contact: 'Pedro Santos / 0918-876-5432', last_school: 'Manila Science HS' },
        { id: '2026-00125', name: 'Mark Reyes', program: 'BS Information Technology', year_level: '1', semester: '1', sex: 'male', applicant_type: 'transferee', birthdate: '2007-01-09', address: '45 Rizal Ave., Pasig City', email: 'mark.reyes@email.com', contact_number: '0919-345-6789', emergency_contact: 'Liza Reyes / 0919-987-6543', last_school: 'Pasig National HS' },
    ],
    get filtered() {
        if (!this.query.trim()) return this.students;
        const q = this.query.toLowerCase();
        return this.students.filter(s => s.name.toLowerCase().includes(q) || s.id.toLowerCase().includes(q));
    },
    open(student) { this.selected = student; this.editing = false; },
    back() { this.selected = null; this.editing = false; }
}">
    <h2 class="page-title">Enrollment Form</h2>

    <!-- LIST VIEW -->
    <div x-show="!selected">
        <div class="row mb-4">
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Search by student name or ID" x-model="query">
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Student</th>
                            <th class="py-3">Student No.</th>
                            <th class="py-3">Program</th>
                            <th class="py-3">Year Level</th>
                            <th class="py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="s in filtered" :key="s.id">
                            <tr>
                                <td class="px-4">
                                    <a href="#" class="text-maroon fw-semibold text-decoration-none" @click.prevent="open(s)" x-text="s.name"></a>
                                </td>
                                <td x-text="s.id"></td>
                                <td x-text="s.program"></td>
                                <td x-text="s.year_level + (s.year_level == '1' ? 'st' : s.year_level == '2' ? 'nd' : s.year_level == '3' ? 'rd' : 'th') + ' Year'"></td>
                                <td><button class="btn btn-sm btn-outline-maroon" @click="open(s)">View</button></td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0">
                            <td colspan="5" class="text-center text-muted py-4">No matching students found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- DETAIL / EDIT VIEW -->
    <div x-show="selected" x-cloak>
        <template x-if="selected">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button class="btn btn-link text-maroon ps-0" @click="back()"><i class="bi bi-arrow-left"></i> Back to list</button>
                    <div>
                        <button class="btn btn-outline-maroon" x-show="!editing" @click="editing = true">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn btn-secondary" x-show="editing" @click="editing = false">Cancel</button>
                        <button class="btn btn-maroon" x-show="editing" @click="editing = false">Save Changes</button>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="text-maroon mb-1" x-text="selected.name"></h5>
                        <p class="text-muted small mb-4">Student No. <span x-text="selected.id"></span></p>

                        <fieldset :disabled="!editing">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" x-model="selected.name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Birthdate</label>
                                    <input type="date" name="birthdate" class="form-control" x-model="selected.birthdate">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sex</label>
                                    <select class="form-select" name="sex" x-model="selected.sex">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Applicant Type</label>
                                    <select class="form-select" name="applicant_type" x-model="selected.applicant_type">
                                        <option value="new">New</option>
                                        <option value="old">Old</option>
                                        <option value="transferee">Transferee</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Program</label>
                                    <select class="form-select" name="program" x-model="selected.program">
                                        <option value="BS Information Technology">BS Information Technology</option>
                                        <option value="BS Computer Science">BS Computer Science</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Year Level</label>
                                    <select class="form-select" name="year_level" x-model="selected.year_level">
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Semester</label>
                                    <select class="form-select" name="semester" x-model="selected.semester">
                                        <option value="1">1st Sem</option>
                                        <option value="2">2nd Sem</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">Home Address</label>
                                    <input type="text" name="address" class="form-control" x-model="selected.address">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" x-model="selected.email">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" x-model="selected.contact_number">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Emergency Contact</label>
                                    <input type="text" name="emergency_contact" class="form-control" x-model="selected.emergency_contact">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last School Attended</label>
                                    <input type="text" name="last_school" class="form-control" x-model="selected.last_school">
                                </div>
                            </div>
                        </fieldset>

                        <div class="mb-0">
                            <label class="form-label">Submitted Documents</label>
                            <div class="border rounded p-3 bg-light">
                                <a href="#" class="text-maroon"><i class="bi bi-file-earmark-pdf"></i> Transcript_of_Records.pdf</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
@else
<div class="container mt-5">
    <h2 class="page-title">Student Enrollment Form</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="/enroll">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Enter last name" value="{{ old('last_name') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="Enter first name" value="{{ old('first_name') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" placeholder="Enter middle name" value="{{ old('middle_name') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sex</label>
                        <select class="form-select" name="sex">
                            <option selected disabled>Select...</option>
                            <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Applicant Type</label>
                        <select class="form-select" name="applicant_type">
                            <option selected disabled>Select...</option>
                            <option value="new" {{ old('applicant_type') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="old" {{ old('applicant_type') == 'old' ? 'selected' : '' }}>Old</option>
                            <option value="transferee" {{ old('applicant_type') == 'transferee' ? 'selected' : '' }}>Transferee</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program">
                            <option selected disabled>Select...</option>
                            <option value="bsit" {{ old('program') == 'bsit' ? 'selected' : '' }}>BS Information Technology</option>
                            <option value="bscs" {{ old('program') == 'bscs' ? 'selected' : '' }}>BS Computer Science</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Year Level</label>
                        <select class="form-select" name="year_level">
                            <option selected disabled>Select...</option>
                            <option value="1" {{ old('year_level') == '1' ? 'selected' : '' }}>1st Year</option>
                            <option value="2" {{ old('year_level') == '2' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3" {{ old('year_level') == '3' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4" {{ old('year_level') == '4' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Semester</label>
                        <select class="form-select" name="semester">
                            <option selected disabled>Select...</option>
                            <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>2nd Semester</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Home Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Enter full address" value="{{ old('address') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email address" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" placeholder="Enter contact number" value="{{ old('contact_number') }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact" class="form-control" placeholder="Name and Number" value="{{ old('emergency_contact') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last School Attended</label>
                        <input type="text" name="last_school" class="form-control" placeholder="Enter school name" value="{{ old('last_school') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Upload Documents (Transcripts of records, etc.)</label>
                    <div class="border border-dashed p-4 text-center bg-light rounded">
                        <p class="text-muted mb-0">Drag file or browse</p>
                        <input class="form-control mt-2" type="file" name="documents[]" multiple>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4">Save Draft</button>
                    <button type="submit" class="btn btn-maroon px-4">Submit Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection