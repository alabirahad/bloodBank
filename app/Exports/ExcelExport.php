<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelExport implements ShouldAutoSize, FromView, WithEvents {

    private $viewFile;
    private $data;

    public function __construct($viewFile, $data) {
        $this->viewFile = $viewFile;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $columns = [
                    'A', //'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'
//                    , 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
//                    , 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM'
//                    , 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'
//                    , 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM'
//                    , 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ'
//                    , 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM'
//                    , 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ'
//                    , 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM'
//                    , 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'
//                    , 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM'
//                    , 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ'
                ];
                foreach ($columns as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setWidth(5);
                }
                $event->sheet->getDelegate()->getStyle('A1:AAA500')->getAlignment()
                        ->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:A500')->getAlignment()
                        ->setVertical('center')
                        ->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('F6:AAA500')->getAlignment()
                        ->setVertical('center')
                        ->setHorizontal('center');
            }
        ];
    }

    public function view(): View {

        return view($this->viewFile, $this->data);
    }

}
