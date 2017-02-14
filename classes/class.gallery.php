<?php

class Gallery {
	
	private $gallery_id;
	private $db;
	private $table = 'gallery';
	private $images_table = 'images';
	private $path;
	
	function __construct($id,$gallery_path) {
		
		$this->path = $gallery_path;
		$this->gallery_id = $id;
		$this->db = new Database();
		
	}
	
	public function getPhotos() {
		
		$photos = $this->db->selectMultiple("SELECT * FROM $this->images_table WHERE gallery_id='$this->gallery_id'");
		
		return $photos;
		
	}
	
	public function getName() {
			
		$gallery = $this->db->select("SELECT gallery_name FROM $this->table WHERE id='$this->gallery_id'");
		
		return $gallery['gallery_name'];
		
	}
	
	public function getCover() {
			
		$gallery = $this->db->select("SELECT cover FROM $this->table WHERE id='$this->gallery_id'");
		
		return $gallery['cover'];
		
	}
	
	public function setCover($image) {
		
		$this->db->update($this->table,array('cover' => $image),"id='$this->gallery_id'");
		
	}
	
	public function howManyPhotos() {
		
		$photos = $this->db->select("SELECT COUNT(*) AS total FROM $this->images_table WHERE gallery_id='$this->gallery_id'");
		
		return $photos['total'];
		
	}
	
	private function emptyGallery() {
		
		$photos = $this->getPhotos();
		
		foreach($photos as $photo) {
			
			$tmp = getFileExt($photo['photo']);
			$crop_image = $this->path . $this->gallery_id . '/' . $tmp['name'] . '_crop' . strtolower($tmp['extension']);
			$small_image = $this->path . $this->gallery_id . '/' . $tmp['name'] . '_small' . strtolower($tmp['extension']);
			$orig_image = $this->path . $this->gallery_id . '/' . $photo['photo'];
			
			if (is_file($crop_image))
				unlink($crop_image);
			
			if (is_file($small_image))
				unlink($small_image);
			
			if (is_file($orig_image))
				unlink($orig_image);
			
		}
		
		rmdir($this->path . $this->gallery_id);
		
	}
	
	public function deleteGallery() {
		
		$this->emptyGallery(); // remove the folder gallery and the photos belonging
		
		$this->db->delete($this->table,"id='$this->gallery_id'");
		$this->db->delete($this->images_table,"gallery_id='$this->gallery_id'");
		
	}
	
}

?>