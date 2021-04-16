<?php 

class Travel
{
	private $serviceURL;

	// Enter your code here
	function __construct($serviceURL){
		$this->setServiceURL($serviceURL);
	}

	public function setServiceURL($serviceURL){
		$this->serviceURL = $serviceURL;
	}

	public function getServiceURL(){
		return $this->serviceURL;
	}

	public function getDataFromAPI(){
		$curl = curl_init($this->getServiceURL());
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
		    $info = curl_getinfo($curl);
		    curl_close($curl);
    		die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response, true);

		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
		    die('error occured: ' . $decoded->response->errormessage);
		}
		return $decoded;
	}
}
class Company
{

	private $serviceURL;

	// Enter your code here
	function __construct($serviceURL){
		$this->setServiceURL($serviceURL);
	}

	public function setServiceURL($serviceURL){
		$this->serviceURL = $serviceURL;
	}

	public function getServiceURL(){
		return $this->serviceURL;
	}

	public function getDataFromAPI(){
		$curl = curl_init($this->getServiceURL());
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		if ($curl_response === false) {
		    $info = curl_getinfo($curl);
		    curl_close($curl);
    		die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($curl);
		$decoded = json_decode($curl_response, true);

		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
		    die('error occured: ' . $decoded->response->errormessage);
		}
		return $decoded;
	}
}
class TestScript
{
	public $companies;
	public $travels;
    public function execute()
    {
        $companyObj = new Company('https://5f27781bf5d27e001612e057.mockapi.io/webprovise/companies');
        $travelObj = new Travel('https://5f27781bf5d27e001612e057.mockapi.io/webprovise/travels');

        // get list company 
	    $this->companies = $companyObj->getDataFromAPI();
	    $this->travels = $travelObj->getDataFromAPI();
	    $tree_company_list = $this->buildTree($this->companies, $this->travels);
	    print_r($tree_company_list);
    }

    public function buildTree($companyDetails, $travelDetails, $parentId = 0){
    	$branch = [];
    	$info = [];
    	$cost = 0;
	    foreach ($companyDetails as $element) {

	        if ($element['parentId'] == $parentId) {
	        	foreach($travelDetails as $item){
	        		if($element['id'] === $item['companyId']){
	        			$cost += (float)$item['price'];
	        		}
	        	}

	        	$info = [
	        		'id' => $element['id'],
	        		// 'parentId' => $element['parentId'],
	            	'name' => $element['name'],
	            	'cost' => $cost,
	        	];
	            $children = $this->buildTree($companyDetails, $travelDetails, $element['id']);
	            if ($children) {
	                $info['children'] = $children;
	            }
	            $branch[] = $info;

	        }
	    }

	    return $branch;
	}
}
(new TestScript())->execute();