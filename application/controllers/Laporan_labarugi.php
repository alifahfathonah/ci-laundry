<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_labarugi extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		error_reporting(0);
		is_logged_in();
		is_admin();
		$this->load->model('Labarugi_model', 'Labarugi');
	}

	public function index()
	{
		$data = [
			'title' => 'Laporan Buku Besar',
			'content' => 'laporan/v_laporan_labarugi'
		];
		$this->load->view('layout/wrapper', $data);
	}


	// **
	// * fungsi cetakk laporan
	// *
	public function cetak_per_bulan()
	{
		$this->load->library('dompdf_gen');

		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');

		$data['debit'] = $this->Labarugi->getDebitPerBulan($bulan, $tahun);
		$data['kredit'] = $this->Labarugi->getKreditPerBulan($bulan, $tahun);
		$data['bulan'] = $bulan;
		$data['tahun'] = $tahun;

		$this->load->view('laporan/export/laporan_laba_rugi', $data);

		$paper_size = 'A4';
		$orientation = 'potrait';
		$html = $this->output->get_output();

		$this->dompdf->set_paper($paper_size, $orientation);

		$this->dompdf->load_html($html);
		$this->dompdf->render();
		ob_end_clean();
		$this->dompdf->stream("laporan_laba_rugi_perbulan.pdf", array('Attachment' => 0));
	}


	public function cetak_per_periode()
	{
		$this->load->library('dompdf_gen');

		$tglAwal = $this->input->post('tgl_awal');
		$tglAkhir = $this->input->post('tgl_akhir');

		$data['debit'] = $this->Labarugi->getDebitPerPeriode($tglAwal, $tglAkhir);
		$data['kredit'] = $this->Labarugi->getKreditPerPeriode($tglAwal, $tglAkhir);
		$data['tgl_awal'] = $tglAwal;
		$data['tgl_akhir'] = $tglAkhir;

		$this->load->view('laporan/export/laporan_laba_rugi', $data);

		$paper_size = 'A4';
		$orientation = 'potrait';
		$html = $this->output->get_output();

		$this->dompdf->set_paper($paper_size, $orientation);

		$this->dompdf->load_html($html);
		$this->dompdf->render();
		ob_end_clean();
		$this->dompdf->stream("laporan_laba_rugi_perperiode.pdf", array('Attachment' => 0));
	}
}

/* End of file Laporan_Labarugi.php */
