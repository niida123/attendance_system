<style>
    /* Select2 + inputs unified at 36px */
.select2-container .select2-selection--single { height: 36px !important; border-radius: 10px !important; border-color: #e5e7eb !important; }
.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: 34px !important; font-size: .85rem; color: #374151; }
.select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow { height: 34px !important; }

.flatpickr-input { background:#fff !important; }

#filterDate, #filterOffice, #filterDepartment, #filterEmployee {
    height: 36px;
    border-radius: 10px;
    border-color: #e5e7eb;
    font-size: .85rem;
}

#filterDate:focus,
.select2-container--bootstrap4.select2-container--focus .select2-selection--single {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important;
}

.btn-filter-reset {
    height: 36px;
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    color: #6b7280;
    font-weight: 600;
    font-size: .8rem;
    padding: 0 16px;
    transition: all .2s;
}
.btn-filter-reset:hover {
    background: #f3f4f6;
    color: #111827;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
}

@media (max-width: 992px) {
    #statsRow .col-lg-2 { flex: 0 0 33.33%; max-width: 33.33%; }
}
@media (max-width: 576px) {
    #statsRow [id^="stat_"] { font-size: 1.05rem !important; }
    #statsRow .col-6 { margin-bottom: 10px; }
}

#reportTable tbody tr { transition: background .15s; }
#reportTable tbody tr:hover { background: #f5f6ff !important; }
#reportTable tbody td { padding: 12px 16px; vertical-align: middle; font-size: .85rem; color: #374151; border-color: #f3f4f6; }

.rpt-badge {
    padding: 5px 12px; border-radius: 20px; font-size: .72rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; line-height: 1;
}
.badge-present { background: #ecfdf5; color: #059669; }
.badge-late    { background: #fef3c7; color: #d97706; }
.badge-absent  { background: #fef2f2; color: #ef4444; }
.badge-leave   { background: #ede9fe; color: #7c3aed; }
.badge-holiday { background: #fef9c3; color: #a16207; }
.badge-halfday { background: #e0f2fe; color: #0369a1; }

.dataTables_wrapper .dataTables_info {
    font-size: .78rem; color: #9ca3af; padding-top: 14px; padding-left: 4px;
}
.dataTables_wrapper .dataTables_paginate { padding-top: 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px !important; margin: 0 2px; border: none !important; background: transparent !important;
    font-size: .82rem; color: #6b7280 !important; padding: 5px 11px !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: linear-gradient(135deg, #4f46e5, #7c3aed) !important; color: #fff !important;
    border: none !important; box-shadow: 0 3px 10px rgba(79, 70, 229, .35);
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #eef2ff !important; color: #4f46e5 !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { color: #d1d5db !important; }

#toast-container > .toast { border-radius: 12px !important; box-shadow: 0 8px 30px rgba(0,0,0,.12) !important; }

@media print {
    .main-header, .main-sidebar, .main-footer, .content-header, .card-header,
    .dataTables_paginate, .dataTables_info { display: none !important; }
    body, .content-wrapper { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    .card { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
    .card-body { padding: 0 !important; background: #fff !important; }
    #reportTable { min-width: 100% !important; font-size: 11px !important; }
    #reportTable thead tr { background: #f3f4f6 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    #reportTable th, #reportTable td { padding: 8px 10px !important; border: 1px solid #e5e7eb !important; }
}

@media (max-width:576px) {
    .card-body { padding: 16px !important; }
}
/* Select2 dropdown panel */
.select2-dropdown {
    border-radius: 10px !important;
    border-color: #e5e7eb !important;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
}

.select2-search--dropdown .select2-search__field {
    border-radius: 8px !important;
    border-color: #e5e7eb !important;
    padding: 6px 10px !important;
}

.select2-results__option--highlighted[aria-selected] {
    background: #4f46e5 !important;
    color: #fff !important;
}

.select2-results__option[aria-selected=true] {
    background: #eef2ff !important;
    color: #4f46e5 !important;
}
/* Force consistent text styling inside select2 rendered box */
.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    font-size: .85rem !important;
    font-weight: 400 !important;
    color: #374151 !important;
    padding-left: 12px !important;
}

.select2-selection__placeholder {
    color: #374151 !important;
}

/* Match date input font */
#filterDate {
    font-size: .85rem;
    color: #374151;
}
.select2-container--bootstrap4 .select2-selection--single {
    display: flex !important;
    align-items: center !important;
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 12px !important;
    font-size: .85rem !important;
    color: #374151 !important;
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    top: 0 !important;
}
.select2-results__option {
    font-size: .85rem !important;
    padding: 8px 12px !important;
}

.select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
    font-size: .85rem !important;
}
</style>
