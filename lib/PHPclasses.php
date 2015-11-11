<?php //require_once('PHPDB/PHPclasses.php');

    /* how to use DB
    $dblink = new DB('hostname','database','username','password');
    $query = $dblink->Q("SQL query");
    while($row = mysqli_fetch_array($query['result'])) {
    */
    class DB {
        public $connection, $connected = false;
        
        public function __construct($hostname, $database, $username, $password) {
            $this->connection = mysqli_connect($hostname, $username, $password, $database);
            if(mysqli_connect_errno()){
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            else {
                $this->connected = true;
            }
        }
        
        public function Q($q) {
            if($this->connected == true) {
                if($search['result'] = mysqli_query($this->connection, $q)) {
                    $search['num_rows']=mysqli_num_rows($search['result']);
                    return $search;
                }
            }
            else {
                echo "You are not connected to the database.";
            }
        }
        
    }

    /* how to use getPage
    $pageArray = array('page1','page2','page3','page4');
    $pagelink = new getPage('homepage',$pageArray,'urlIdentifier');
    */
    class getPage {
        public $pages;
        public $homepage;
        public $urlidentifier;
        
        public function __construct($homepage, $pages, $urlidentifier) {
            $this->pages = $pages;
            $this->homepage = $homepage;
            $this->urlidentifier = $urlidentifier;
            
            if(isset($_GET[$this->urlidentifier])) {
                if(in_array($_GET[$this->urlidentifier], $this->pages)) {
                    $_page = $_GET[$this->urlidentifier];
                }
                else {
                    $_page = $this->homepage;
                }
            }
            else {
                $_page = $this->homepage;
            }
               
            require_once($_page . ".php");
        }

    }

    /* how to use cartManager
    $cart = new cartMantager();
    */
    class cartManager {
        public $taxrate = 0.066;
        
        public function cartExists() { /*   $cart->cartExists();   */
            if(isset($_SESSION['cart'])){
                return true;
            }
            else {
                return false;
            }
        }
        
        public function createCart() { /*   $cart->createCart();   */
            $_SESSION['cart'] = array();
        }
        
        public function insertToCart($prodID, $prodName, $price, $qty) { /*   $cart->insertToCart($prodID, $prodName, $price, $qty);   */
            if(array_key_exists($prodID, $_SESSION['cart'])) {
                $_SESSION['cart'][$prodID]['qty'] = $_SESSION['cart'][$prodID]['qty'] + $qty;
            }
            else {
                $_SESSION ['cart'][$prodId]['prodID'] = $prodID;
                $_SESSION ['cart'][$prodId]['prodName'] = $prodName;
                $_SESSION ['cart'][$prodId]['price'] = $price;
                $_SESSION ['cart'][$prodId]['qty'] = $qty;
            }
        }
        
        public function calcSubTotal() {
            $subtotal = 0;
            foreach($_SESSION['cart'] as $product) {
                $qty = $product['qty'];
                $price = $product['price'];
                
                $subtotal = $subtotal + ($qty * $price);
            }
            $result = '$' . number_format($subtotal, 2);
            return $result;
        }
        
        public function calcTax() {
            $subtotal = 0;
            foreach($_SESSION['cart'] as $product) {
                $qty = $product['qty'];
                $price = $product['price'];
                
                $subtotal = $subtotal + ($qty * $price);
            }
            $tax = $subtotal * $this->taxrate;
            $result = '$' . number_format($tax, 2);
            return $result;
        }
        
        public function calcTotal() {
            $subtotal = 0;
            foreach($_SESSION['cart'] as $product) {
                $qty = $product['qty'];
                $price = $product['price'];
                
                $subtotal = $subtotal + ($qty * $price);
            }
            $tax = $subtotal * $this->taxrate;
            $total = $subtotal + $tax;
            $result = '$' . number_format($total, 2);
            return $result;
        }
    }

    /* how to use paypalCartManager
    $cart = new paypalCartMantager();
    */
    class paypalCartManager {
        
        public function paypalCartExists() { /*   $cart->paypalCartExists();   */
            if(isset($_SESSION['paypalCart'])){
                return true;
            }
            else {
                return false;
            }
        }
        
        public function createPaypalCart() {/*   $cart->createPaypalCart();   */
            $_SESSION['paypalCart'] = array();
        }
        
        public function insertToPaypalCart($prodID, $prodName, $price, $qty) { /*   $cart->insertToCart($prodID, $prodName, $price, $qty);   */
            if(array_key_exists($prodID, $_SESSION['paypalCart'])) {
                $_SESSION['paypalCart'][$prodID]['qty'] = $_SESSION['paypalCart'][$prodID]['qty'] + $qty;
            }
            else {
                $_SESSION ['paypalCart'][$prodId]['prodID'] = $prodID;
                $_SESSION ['paypalCart'][$prodId]['prodName'] = $prodName;
                $_SESSION ['paypalCart'][$prodId]['price'] = $price;
                $_SESSION ['paypalCart'][$prodId]['qty'] = $qty;
            }
        }
        
        public function calcSubTotal() {
            $subtotal = 0;
            foreach($_SESSION['paypalCart'] as $product) {
                $qty = $product['qty'];
                $price = $product['price'];
                
                $subtotal = $subtotal + ($qty * $price);
            }
            $result = '$' . number_format($subtotal, 2);
            return $result;
        }
        
        public function calcTax() {
            $subtotal = 0;
            foreach($_SESSION['paypalCart'] as $product) {
                $qty = $product['qty'];
                $price = $product['price'];
                
                $subtotal = $subtotal + ($qty * $price);
            }
            $tax = $subtotal * $this->taxrate;
            $result = '$' . number_format($tax, 2);
            return $result;
        }
        
        public function calcTotal() {
            $subtotal = 0;
            foreach($_SESSION['paypalCart'] as $product) {
                $qty = $product['qty'];
                $price = $product['price'];
                
                $subtotal = $subtotal + ($qty * $price);
            }
            $tax = $subtotal * $this->taxrate;
            $total = $subtotal + $tax;
            $result = '$' . number_format($total, 2);
            return $result;
        }
    }

?>