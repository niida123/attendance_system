<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Excel export for the Daily Attendance Report.
 *
 * Layout:
 *   Row 1-6  : Company / report meta block
 *   Row 7    : Summary cards (as a single labelled line)
 *   Row 9    : Column headings
 *   Row 10.. : Data
 */
class DailyAttendanceExport implements FromArray, WithHeadings, WithEvents, WithStyles, WithColumnWidths
{
    protected array $rows;
    protected array $summary;
    protected array $meta;

    /** Number of leading meta rows inserted before the headings row. */
    protected int $headerRowCount = 9;

    public function __construct(array $rows, array $summary, array $meta)
    {
        $this->rows    = $rows;
        $this->summary = $summary;
        $this->meta    = $meta;
    }

    public function array(): array
    {
        return array_map(fn($r) => [
            $r['no'],
            $r['employee_code'],
            $r['employee_name'],
            $r['department'],
            $r['office'],
            $r['check_in'] ?? '—',
            $r['check_out'] ?? '—',
            $r['working_hours'] ?? '—',
            $r['late_minutes'] ?? 0,
            $r['overtime_hours'] ?? 0,
            $r['status'],
        ], $this->rows);
    }

    public function headings(): array
    {
        return [
            'No.', 'Employee Code', 'Employee Name', 'Department', 'Office',
            'Check In', 'Check Out', 'Working Hours', 'Late (min)', 'Overtime (hr)', 'Status',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 16, 'C' => 24, 'D' => 18, 'E' => 18,
            'F' => 12, 'G' => 12, 'H' => 14, 'I' => 12, 'J' => 14, 'K' => 14,
        ];
    }

    public function styles($sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = 'K';

                // Push the auto-generated headings + data down to make room for meta rows.
                $sheet->insertNewRowBefore(1, $this->headerRowCount);

                $sheet->setCellValue('A1', $this->meta['company_name']);
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

                $sheet->setCellValue('A2', $this->meta['title']);
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                $sheet->setCellValue('A3', $this->meta['period']);
                $sheet->mergeCells("A3:{$lastCol}3");

                $sheet->setCellValue('A4', 'Office: ' . $this->meta['office'] . '   |   Department: ' . $this->meta['department'] .
                    ($this->meta['employee'] ? '   |   Employee: ' . $this->meta['employee'] : ''));
                $sheet->mergeCells("A4:{$lastCol}4");

                $sheet->setCellValue('A5', 'Generated: ' . $this->meta['generated_at'] . ' by ' . $this->meta['generated_by']);
                $sheet->mergeCells("A5:{$lastCol}5");
                $sheet->getStyle('A5')->getFont()->setItalic(true)->setSize(9);

                $sheet->setCellValue('A7',
                    "Total Employees: {$this->summary['total_employees']}   |   Present: {$this->summary['present']}   |   " .
                    "Late: {$this->summary['late']}   |   Absent: {$this->summary['absent']}   |   " .
                    "Leave: {$this->summary['leave']}   |   Holiday: {$this->summary['holiday']}"
                );
                $sheet->mergeCells("A7:{$lastCol}7");
                $sheet->getStyle('A7')->getFont()->setBold(true);
                $sheet->getStyle('A7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EEF2FF');

                // Style the headings row (row 9 after insert).
                $headingRow = $this->headerRowCount;
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$headingRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$headingRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$headingRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$lastRow}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E7EB');

                // Footer with page number placeholder (visible when printed from Excel).
                $sheet->getHeaderFooter()->setOddFooter('&L' . $this->meta['company_name'] . '&R Page &P of &N');
            },
        ];
    }
}
