<?php


namespace AppBundle\Service;


use AppBundle\Entity\Client;
use AppBundle\Repository\ClientRepositoryInterface;
use AppBundle\Repository\WarehouseRepositoryInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportService
{

    private $spreadsheet;
    private $warehouseRepository;
    private $clientRepository;

    private $index;

    public function __construct(WarehouseRepositoryInterface $warehouseRepository, ClientRepositoryInterface $clientRepository)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->warehouseRepository = $warehouseRepository;
        $this->clientRepository = $clientRepository;
        $this->index = 0;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function stocksReport()
    {

        $warehouse = $this->getWarehouseStocks();
        $clients = $this->getClients();

        $this->setProperties();

        $this->createSheet($this->index, $warehouse, 'Склад');

        foreach($clients as $client) {

            $clientStocks = $this->getClientStocks($client);

            if ($clientStocks === null) {
                continue;
            }

            $this->index++;

            $this->createSheet($this->index, $clientStocks, $client->getName());

            foreach ($clientStocks as $key => $item) {
                if (!key_exists($key, $warehouse)) {
                    $warehouse[$key]['title'] = $item['title'];
                    $warehouse[$key]['quantity'] = 0;
                }
                $warehouse[$key]['quantity'] += $item['quantity'];
            }

        }

        $this->index++;

        $this->sortAlphabetically($warehouse,'title');

        $this->createSheet($this->index, $warehouse, 'Тотал');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->spreadsheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($this->spreadsheet, 'Xls');

        $writer->save('php://output');
    }

    private function setProperties()
    {
        // Set document properties
        $this->spreadsheet->getProperties()
            ->setCreator('Avliga CRM')
            ->setLastModifiedBy('Avliga CRM')
            ->setTitle('Office 2007 XLSX')
            ->setSubject('Office 2007 XLSX')
            ->setDescription('Avliga CRM Stocks Report')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Stocks Report');
    }

    /**
     * @param $index
     * @param $data
     * @param $title
     * @throws Exception
     */
    private function createSheet($index, $data, $title)
    {
        $column = 'A';
        $row = 1;

        if ($index > 0) {
            $this->spreadsheet->createSheet();
        }

        $sheet = $this->spreadsheet->setActiveSheetIndex($index);

        $columns = array_keys($data[key($data)]);

        foreach ($columns as $columnName) {
            $sheet->setCellValue($column.$row, ucfirst($columnName));
            $column++;
        }

        $row++;

        foreach ($data as $item) {
            $column = 'A';
            foreach ($item as $value) {
                $sheet->setCellValue($column.$row, $value);
                $column++;
            }
            $row++;
        }

        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setAutoSize(true);

        // Rename worksheet
        $this->spreadsheet->getActiveSheet()->setTitle($this->sheetTitle($title));
    }

    private function getWarehouseStocks()
    {
        $stocks = $this->warehouseRepository->getStocks();

        $data = [];

        foreach ($stocks as $item) {
            $data[$item['id']] = [
                'title' => $item['title'],
                'quantity' => $item['stocks']
            ];
        }

        $this->sortAlphabetically($data,'title');

        return $data;
    }

    private function getClients()
    {
        return $this->clientRepository->listActive();
    }

    private function getClientStocks(Client $client): ?array
    {
        $clientInfo = $this->clientRepository->getClientInfo($client);

        $stocks = array_map(function ($item){
            $item['reported'] = $item['reported'] === null ? 0 : $item['reported'];
            $item['returned'] = $item['returned'] === null ? 0 : $item['returned'];
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'quantity' => $item['ordered'] - $item['reported'] - $item['returned']
            ];
        }, $clientInfo);

        $stocks = array_filter($stocks, function ($item) {
            return $item['quantity'] > 0;
        });

        if (count($stocks) == 0) {
            return null;
        }

        $data = [];

        foreach ($stocks as $item) {
            $data[$item['id']] = [
                'title' => $item['title'],
                'quantity' => $item['quantity']
            ];
        }

        $this->sortAlphabetically($data,'title');

        return $data;

    }

    private function sortAlphabetically(&$data, $column)
    {
        uasort($data, function ($item1, $item2) use ($column) {
            return $item1[$column] <=> $item2[$column];
        });
    }

    private function sheetTitle($title) {
        if (mb_strlen($title) > Worksheet::SHEET_TITLE_MAXIMUM_LENGTH) {
            $title = mb_substr($title, 0, Worksheet::SHEET_TITLE_MAXIMUM_LENGTH - 2).'..';
        }
        return mb_strtoupper($title);
    }

}