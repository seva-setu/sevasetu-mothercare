<?php namespace App\Http;
/*use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
*/
use Hashids\Hashids;
use Torann\Hashids\HashidsServiceProvider;
use Cache;
class Helpers {
	
	public function __construct(){
		if(!defined('SITENAME'))
			define('SITENAME',' - Mother Care');
	}
	/*
	 * Permission Manager Method
	 * Command: composer dump-autoload
	 */
	//protected $permissions;
    public function checkpermission($vrole_id){
        $permissions = array(
        	'addcallreport' => true,
        	'editcallsummary' => true,
        	'addactionitem' => true,
        	'editactionitem'=>true,
        	'cancompleteaction' => true,
        	'canaddchecklist' => false,
        	'canmodifychecklist' => false,
        	'canupdatechecklist' => true,
        	'canaddlocation' => false,
        	'manageintervention' => false,
        	'canassigncallchampion' => false,
        	'canweeklyreport'	=> true
        );
       
		/*
		 * Roles: Admin - 0, Coordinator - 1, CallChampion - 2, FieldWorker - 3
		 */
        switch($vrole_id){
        	case 0:
        		$permissions['canaddchecklist'] 		= true; 
        		$permissions['canaddlocation']			= true;
        		$permissions['manageintervention']		= true;
        		$permissions['canassigncallchampion'] 	= true;
        		break;
        	case 1:
        		$permissions['canassigncallchampion'] 	= true;
           		break;
        	case 2:
        		$permissions['cancompleteaction']		= false;
        		break;
        	case 3: 
        		$permissions['addcallreport'] 			= false;
        		$permissions['editcallsummary'] 		= false;
        		$permissions['addactionitem'] 			= false;
        		$permissions['editactionitem'] 			= false;
        		$permissions['canupdatechecklist'] 		= false;
        		$permissions['canweeklyreport'] 		= false;
	       		break;
       }
        
        return $permissions;
    }
    
    
    public function clearBen_Data(){
    	Cache::forget('ben_data');
    	Cache::forget('count');
    }
    
    public function downlaodreport($dataArray,$fileType,$viewPath){
       	$pdf = PDF::setPaper('a4')->setOrientation('landscape')->loadView(viewPath, $dataArray);
    	return $pdf->download('Weekly_Call_List_'.date('d/m/Y').'.pdf');
    }
	
	public function decode($id=0){
  		if($id){
  			$hashids = new Hashids();
  			$arr = $hashids->decode($id);
  			return (!empty($arr)) ? $id=$arr[0] : 0;
  			//return $id=$arr[0];
  		}else
  			return 0;
  	}

   /* public function makeLengthAware($collection, $total, $perPage,$currpage){
      		$paginator = new LengthAwarePaginator(
    				$collection,
    				$total,
    				$perPage,
    				//Paginator::resolveCurrentPage(),
    				['path' => Paginator::resolveCurrentPath()]);
      		
    		return str_replace('/?', '?', $paginator->render());
    }*/
    	 
    	
}