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

class QuarterlyAttendanceExport implements FromArray, WithHeadings, WithEvents, WithStyles, WithColumnWidths
{
    protected array $rows;
    protected array $summary;
    protected array $meta;
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
            $r['no'], $r['employee_code'], $r['employee_name'], $r['department'], $r['office'],
            $r['present_days'], $r['late_days'], $r['absent_days'], $r['leave_days'], $r['holiday_days'],
            $r['total_working_hours'], $r['total_overtime_hours'],
        ], $this->rows);
    }

    public function headings(): array
    {
        return [
            'No.', 'Employee Code', 'Employee Name', 'Department', 'Office',
            'Present Days', 'Late Days', 'Absent Days', 'Leave Days', 'Holiday Days',
            'Total Working Hours', 'Total Overtime Hours',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 16, 'C' => 24, 'D' => 18, 'E' => 18,
            'F' => 13, 'G' => 11, 'H' => 13, 'I' => 12, 'J' => 13, 'K' => 16, 'L' => 16,
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
                $lastCol = 'L';

                $sheet->insertNewRowBefore(1, $this->headerRowCount);

                $sheet->setCellValue('A1', $this->meta['company_name']);
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

                $sheet->setCellValue('A2', $this->meta['title']);
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                $sheet->setCellValue('A3', $this->meta['period']);
                $sheet->mergeCells("A3:{$lastCol}3");

                $sheet->setCellValue('A4', 'Office: ' . $this->meta['office'] . '   |   Department: ' . $this->meta['department']);
                $sheet->mergeCells("A4:{$lastCol}4");

                $sheet->setCellValue('A5', 'Generated: ' . $this->meta['generated_at'] . ' by ' . $this->meta['generated_by']);
                $sheet->mergeCells("A5:{$lastCol}5");
                $sheet->getStyle('A5')->getFont()->setItalic(true)->setSize(9);

                $sheet->setCellValue('A7',
                    "Total Employees: {$this->summary['total_employees']}   |   Present: {$this->summary['total_present']}   |   " .
                    "Late: {$this->summary['total_late']}   |   Absent: {$this->summary['total_absent']}   |   " .
                    "Leave: {$this->summary['total_leave']}   |   Holiday: {$this->summary['total_holiday']}   |   " .
                    "Working Hrs: {$this->summary['total_working_hours']}   |   Overtime Hrs: {$this->summary['total_overtime_hours']}"
                );
                $sheet->mergeCells("A7:{$lastCol}7");
                $sheet->getStyle('A7')->getFont()->setBold(true);
                $sheet->getStyle('A7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EEF2FF');

                $headingRow = $this->headerRowCount;
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$headingRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$headingRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$headingRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$lastRow}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E7EB');

                $sheet->getHeaderFooter()->setOddFooter('&L' . $this->meta['company_name'] . '&R Page &P of &N');
            },
        ];
    }
}
