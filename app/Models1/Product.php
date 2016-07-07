<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
class Product extends Eloquent {
	public $timestamps = false;
	protected $table = 'product';
	protected $primaryKey = 'b_id';
	public function save_data(){
		
	}
}
