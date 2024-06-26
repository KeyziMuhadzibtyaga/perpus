<?php
class Peminjaman_model extends CI_Model{
    public function getListBuku(){
        $query = $this->db->get('buku')->result();
		return $query;
    }

    public function getListPeminjaman(){
		$array = array('UserId' => $this->session->userdata('userid'), 'StatusPeminjaman' => 'dipinjam');
        $query = $this->db->where($array)->get('peminjaman')->result();
		return $query;
    }

	public function kembali($id){
		$data = array(
			'StatusPeminjaman' => "dikembalikan"
		);

		$this->db->where('PeminjamanId', $id)->update('peminjaman', $data);
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function cariBuku($kode){
		$hm = $this->db->where('BukuId', $kode)->get('buku');
		return $hm;
	}

    public function insert()
	{
		$data = array(
			'UserId' => $this->input->post('userid'),
			'BukuId' => $this->input->post('bukuid'),
			'TanggalPeminjaman' => date('Y/m/d'),
			'TanggalPengembalian' => date('Y/m/d', strtotime('+4 days')),
			'StatusPeminjaman' => "dipinjam" //default status
		);

		$this->db->insert('peminjaman', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function updateBookStock($bukuid, $newStock) {
        $this->db->where('BukuId', $bukuid);
        $this->db->update('buku', array('Stok' => $newStock));
    }

	public function getPeminjamanInfo($id) {
		$query = $this->db->where('PeminjamanId', $id)->get('peminjaman');
		return $query->row_array();
	}
	
	public function increaseBookStock($bukuid) {
		$book = $this->db->where('BukuId', $bukuid)->get('buku')->row_array();
		$currentStock = $book['Stok'];
		$newStock = $currentStock + 1;
		$this->db->where('BukuId', $bukuid)->update('buku', array('Stok' => $newStock));
	}
}
?>