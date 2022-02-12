<?php

/**
 * date-created  4/4/16
 * created-by    Sourabh
 */
class Router
{
    
    private $_method;
    private $_handler;
    private $_getParams = null;
    private $_postParams = null;
    
    public function __construct($url)
    {
        
        //check method
        $this->_method = $_SERVER['REQUEST_METHOD'];
        
        //pre routing and db connection
        $explode = explode('/', $url);
        $size    = sizeof($explode);
        
        $db = Database::getInstance();
        
        $this->_getParams = array();
        $this->_postParams = array();

        //if POST, postParams
        if ($this->_method == 'POST') {
            $this->_postParams = json_decode(file_get_contents("php://input"), true); //convert postparameters in array...which is understand by php                
            //check for token here
        }
        if ($size > 1) {            
            for ($c = 1; $c < $size; $c++) {
                array_push($this->_getParams, $explode[$c]);
            }
        }        
        
        switch ($explode[0]) {

            case 'sync':
                $sales = new Sync($db, $this->_method, $this->_getParams, $this->_postParams);
                break;

            case 'customers':
                $customer = new Customers($db, $this->_method, $this->_getParams, $this->_postParams);
                break;

            case 'cars':
                $cars = new Cars($db, $this->_method, $this->_getParams, $this->_postParams);
                break;

            case 'transactions':
                $transactions = new Transactions($db, $this->_method, $this->_getParams, $this->_postParams);
                break;

            case 'payments':
                $payments = new Payments($db, $this->_method, $this->_getParams, $this->_postParams);
                break;
                
            case 'users':
                $users = new users($db, $this->_method, $this->_getParams, $this->_postParams);
                break;

            // api switch case
            case 'receiptbook':
                $receiptbook = new ReceiptBook($db, $this->_method, $this->_getParams, $this->_postParams);
                break;

            // api switch case
            case 'test':
                $test1 = new Test($db, $this->_method, $this->_getParams, $this->_postParams);
                break;

            // api switch case
            case 'admin':
                $admin = new Admin($db, $this->_method, $this->_getParams, $this->_postParams);
                break;
            
            default:
                echo "Please don't do stupid stuff";
                break;
        }
        
    }  
}
?>
