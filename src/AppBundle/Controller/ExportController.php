<?php

namespace AppBundle\Controller;

use AppBundle\Service\ExportService;
use PhpOffice\PhpSpreadsheet\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends Controller
{

    private $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * @Route("/export/stocks", name="export_stocks")
     * @Security("has_role('ROLE_USER')")
     *
     * @throws Exception
     * @return Response
     */
    public function exportStocks()
    {
        $filename = 'stocks_report_'.date('Y-m-d_H-i');

        ob_start();
        $this->exportService->stocksReport();

        return new Response(
            ob_get_clean(),  // read from output buffer
            200,
            [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.xls"'
            ]
        );
    }

}
