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

class StockAdjustExport implements
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
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 10,
            'J' => 10,
            'K' => 10,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function array(): array
    {
        $stocktake = $this->entry;
        return [
            ['MR HANG - STOCK ADJUSTMENT'],
            ['ADJUST NO ', $stocktake->stk_no],
            ['ACTION BY ', $stocktake->createdBy],
            ['ACTION DATE ', Carbon::parse($stocktake->created_at)->format('d-m-Y, h:i:s A')],
            ['REMARKS ', $stocktake->remarks],
            [''],
            ['ADJUSTMENT DETAILS'],
            self::columns($this->details)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A7:K7' . (count($this->details) + 3))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '467fd0'],
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->mergeCells('A7:K7');
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A7')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:K1')
                    ->getFont()->setSize(14)->setBold('A1:K1');

                $event->sheet->getDelegate()->getStyle('A2:A5')
                    ->getFont()->setSize(12)->setBold('A2:A5');

                $event->sheet->getDelegate()->getStyle('A6:K6')
                    ->getFont()->setSize(12)->setBold('A6:K6');

                $event->sheet->getDelegate()->getStyle('A1:K1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('467fd0');
                $event->sheet->getDelegate()->getStyle('A7:K8')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('467fd0');
            },
        ];
    }

    public function columns($details)
    {
        $results = [[
            '#', 'ITEM', 'QTY BEFOR', 'SI BEFOR', 'SO BEFOR', 'QTY MOVEMENT', 'SI MOVEMENT',
            'SO MOVEMENT', 'QTY AFTER', 'SI AFTER', 'SO AFTER'
        ]];
        foreach ($details as $index => $item) {
            array_push(
                $results,
                [
                    $index + 1,
                    $item->itemName($item->product_id),
                    $item->qty_before,
                    $item->si_before,
                    $item->so_before,
                    $item->qty_movement,
                    $item->si_movement,
                    $item->so_movement,
                    $item->qty_after ?? 0,
                    $item->si_after ?? 0,
                    $item->so_after ?? 0
                ]
            );
        }
        return $results;
    }
}
