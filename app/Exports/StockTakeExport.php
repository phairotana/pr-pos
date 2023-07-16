<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class StockTakeExport implements
    FromArray,
    WithColumnWidths,
    WithEvents,
    WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $entry;
    protected $details;
    public function __construct($entry, $details)
    {
        $this->entry = $entry;
        $this->details = $details;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 40,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function array(): array
    {
        $stocktake = $this->entry;
        return [
            ['MR HANG - STOCK TAKE'],
            ['STK NO ', $stocktake->stk_no],
            ['ACTION BY ', $stocktake->createdBy],
            ['ACTION DATE ', Carbon::parse($stocktake->created_at)->format('d-m-Y, h:i:s A')],
            ['REMARKS ', $stocktake->remarks],
            [''],
            ['ITEM DETAILS'],
            self::columns($this->details)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A7:G' . (count($this->details) + 3))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '467fd0'],
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A6:G6');
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A6')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getFont()->setSize(14)->setBold('A1:G1');

                $event->sheet->getDelegate()->getStyle('A2:A5')
                    ->getFont()->setSize(12)->setBold('A2:A5');

                $event->sheet->getDelegate()->getStyle('A6:G6')
                    ->getFont()->setSize(12)->setBold('A6:G6');

                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('467fd0');
                $event->sheet->getDelegate()->getStyle('A7:G7')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('467fd0');
            },
        ];
    }

    public function columns($details)
    {
        $results = [['#', 'CATEGORIES', 'ITEMS', 'EXPECTED', 'COUNTED', 'DIFFERENCE', 'NOTED']];
        foreach ($details as $index => $item) {
            array_push(
                $results,
                [
                    $index + 1,
                    $item->CategoryName,
                    $item->itemName($item->product_id),
                    $item->expected,
                    $item->counted,
                    $item->difference,
                    $item->note
                ]
            );
        }
        return $results;
    }
}
