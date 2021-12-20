<?php
/**
 * Generate an SuspectDevice PDF report
 */

include_once 'PDFReport/FPDF.php';

class SuspectDeviceReport extends FPDF {

	public $pageWidth = 735;
	public $pageHeight = 470;

	private $reportGenerated;

	private $colwidths;
	private $headers;

	private $letterDim = array(
		'mm' => array('width' => 215.9, 'height' =>279.4),
		'in' => array('width' => 8.5, 'height' =>11.0)
	);

	/**
	 * Construct PDF Document
	 *
	 * @param string $orient The default page orientation.
	 * @param string $unit The default page unit measure.
	 * @param string $format The default page format.
	 * @param string $title The report title.
	 */
	function __construct($orient, $unit, $format, $title) {

		parent::__construct($orient, $unit, $format);

		$this->title = $title;

		// Calculate date values
		$this->reportGenerated = time();
	}

	/**
	 * Insert page header
	 *
	 * @override
	 */
	function Header() {

		if ($this->PageNo() > 1) {
			//$this->Image(realpath(dirname(__FILE__)) . '/logo.jpg', 10, 8, 33, 24);

			// Title
			$this->SetFont('Arial', '', 10);
			$this->Cell(0, 10, $this->title, 0, 1, 'C');
		}
	}

	/**
	 * Insert page footer
	 *
	 * @override
	 */
	function Footer() {

		if ($this->PageNo() > 1) {

			// Position at 1.5 cm from bottom
			$this->SetY(-15);

			// Arial italic 8 gray
			$this->SetFont('Arial', 'I', 10);
			$this->SetTextColor(128);

			// Date generated
			$this->Cell(0, 5, 'Generated: ' . date('M j, Y', $this->reportGenerated), 0, 0, 'C');

			// Page number {nb} get replaced automatically
			$this->Cell(0, 5, 'Page '.$this->PageNo().' of {nb}', 0, 0, 'R');
		}
	}

	/**
	 * Inserts a report cover page
	 */
	function AddCoverPage() {

		$this->AddPage();

		$this->SetFont('Arial', 'B', 24);
		$this->SetTextColor(0);
		$this->Cell(0, 100, $this->title, 0, 1, 'C');

		$this->SetFont('Arial', 'B', 16);
		$this->SetTextColor(128);
		$this->Cell(0, 20, $this->subject, 0, 1, 'C');

		$this->SetFont('Arial', 'I', 12);
		$this->SetTextColor(191);
		$this->Cell(0, 50, 'Generated: ' . date('M j, Y', $this->reportGenerated), 0, 0, 'C');
	}

	/**
	 * Generates a table from the data.
	 *
	 * @param array $colwidth Array of column widths.
	 * @param array $headers Array of column header strings.
	 * @param array $data Array of data to display.
	 * @return void
	 */
	function GenerateTable($colwidths, $headers, $data) {

		$this->headers = $headers;
		$this->colwidths = $colwidths;

		$this->SetFont('Arial', '', 10);

		//Data
		$fill=false;
		$i = 0;
		foreach($data as $row) {

			$i = $i % 27;

			if ($i == 0) {
				//Colors, line width and bold font
				$this->SetFillColor(116, 143, 165);
				$this->SetTextColor(255);
				$this->SetDrawColor(128, 0, 0);

				$this->SetFont('','B');

				$this->GenerateTableHeaders();
			}

			// Color and font restoration
			$this->SetFillColor(224, 235, 255);
			$this->SetTextColor(0);
			$this->SetFont('');

			$this->SetLineWidth(.3);

			for ($i = 0; $i < count($row); $i++) {
				$this->Cell($colwidths[$i], 6, $row[$i], 'LR', 0, 'L', $fill);;
			}

			$this->Ln();
			$fill=!$fill;

			$i++;
		}

		$this->Cell(array_sum($colwidths), 0, '', 'T');
	}

	/**
	 * Generates the table headers
	 *
	 * @return void
	 */
	private function GenerateTableHeaders() {

		// Column headers
		$secondLine = array();
		for($i = 0; $i < count($this->headers); $i++) {
			$parts = explode("\n", $this->headers[$i]);
			$this->Cell($this->colwidths[$i], 6, $parts[0], 'LTR', 0, 'C', TRUE);
			if (count($parts) > 1) {
				$secondLine[] = $parts[1];
			}
			else {
				$secondLine[] = ' ';
			}
		}
		$this->Ln();
		for($i = 0; $i < count($secondLine); $i++) {
			$this->Cell($this->colwidths[$i], 6, $secondLine[$i], 'LBR', 0, 'C', TRUE);
		}
		$this->Ln();
	}
	/**
	 * Center a string in the center of an image
	 */
	private function imageStringCenter($image, $fontSize, $text, $color) {

		$imageHeight = imagesy($image);
		$imageWidth = imagesx($image);

		$fontHeight = imagefontheight($fontSize);
		$fontWidth = imagefontwidth($fontSize);

		$totalLines = floor($imageHeight / $fontHeight);
		$totalCols = floor($imageWidth / $fontWidth);

		// Break text into lines with wrapping
		$lines = wordwrap($text, $totalCols, "\n", true);
		$lines = explode("\n", $lines);
		$lineNumber = floor(($totalLines - count($lines)) / 2);
		foreach($lines as $line) {

			$left = ceil(($imageWidth - ($fontWidth * strlen($line))) / 2);
			$top = $lineNumber++ * $fontHeight;

			imagestring($image, $fontSize, $left, $top, $line, $color);
		}
	}
}

//
// REPORT PARAMETERS
//

//
// PDF REPORT GENERATION
//
?>
