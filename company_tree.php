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
		var_dump($curl);
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
    public function execute()
    {
        $companyObj = new Company('https://5f27781bf5d27e001612e057.mockapi.io/webprovise/companies');
        $travelObj = new Travel('https://5f27781bf5d27e001612e057.mockapi.io/webprovise/travels');

        // get list company 
	    $company_list = $companyObj->getDataFromAPI();
	    $travel_list = $travelObj->getDataFromAPI();
	    $tree_company_list = $this->buildTree($company_list, $travel_list, "0");
	    print_r($tree_company_list);
    }

    public function buildTree($arrOne,$arrTwo, $parent_id){
	    $result = [];
	    $cost = 0;
	    foreach($arrOne as $key => $item){
	    	
	        if($item['parentId'] === $parent_id){
	        	foreach($arrTwo as $key_2 => $item_2){
	        		if($item['id'] === $item_2['companyId']){
	        			$cost += (float)$item_2['price'];
	        		}
	        	}

	        	
	            unset($arrOne[$key]);
	          
	            $result = [
	            	'id' => $item['id'],
	            	'name' => $item['name'],
	            	'cost' => $cost,
	            ];

	            $child = $this->buildTree($arrOne, $arrTwo, $item['id']);
	            $result = array_merge($result, ['children' => $child]);

	        }
	    }

	    return $result;
	}
}
(new TestScript())->execute();