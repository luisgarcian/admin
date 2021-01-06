<?php
	class conexion{
		private $servidor;
                private $basedatos;		
                private $usuario;
                private $contrasena;
		private $driver ;
		
		public  $conexion;
		public  $num_rows = 0;
		private $result;
                private $seldb = false;

		public function __construct($tipo)
		{
                        if ($tipo == 'l') {
                           $local  = array ( "ZTSISPC03-PC\SQLEXPRESS", "Sirco", "", "", "{SQL Server}" );
	   		               list($this->servidor, $this->basedatos, $this->usuario, $this->contrasena, $this->driver) = $local;
                       
                        }
                        if ($tipo == 'r') {
                           $remoto = array ( "zapateriastorreon.database.windows.net", "SircoDWH", "pappos",  "SirCo_33", "sqlsrv:" );
                           list($this->servidor, $this->basedatos, $this->usuario, $this->contrasena, $this->driver) = $remoto;
                       
                        }
	        }	
		
                 function conectar() {

                        //try {
                             // Returns a MS SQL link identifier on success, or FALSE on error.
                           //$this->conexion = new PDO( "DRIVER={FreeTDS};SERVERNAME=".$this->servidor.";DATABASE=".$this->basedatos, $this->usuario, $this->contrasena);
                           //$this->conexion = new PDO("sqlsrv:server=".$this->servidor.";database=".$this->basedatos, $this->usuario, $this->contrasena);
                           //$this->conexion = new PDO( "DRIVER={unixODBC};server=".$this->servidor.";database=".$this->basedatos, $this->usuario, $this->contrasena);
                           
                           $connectionInfo = array("Database" => $this->basedatos, "UID" => $this->usuario, "PWD" => $this->contrasena);
                           $serverName = "zapateriastorreon.database.windows.net, 1433";
                        //    $this->conexion = sqlsrv_connect($serverName, $connectionInfo);
                        //    if ($this->conexion) {
                        //         echo "Got a connection!<br />";
                        //     } else {
                        //         echo "Connection could not be established.<br />";
                        //         die(print_r(sqlsrv_errors(), true));
                        //     }

                            try {
                                //$this->conexion = new PDO("odbc:server=$this->servidor;database=$this->basedatos", $this->usuario, $this->contrasena);
								
								//DRIVER={FreeTDS};SERVER=server.com;PORT=1433;DATABASE=dbname;UID=dbuser;PWD=dbpassword;TDS_Version=7.3;
								$cnninfo = "DRIVER={sqlsrv};odbc:server=".$this->servidor.";port=1433;database=".$this->basedatos;
								
								//$connection = odbc_connect("Driver={SQL Server Native Client 10.0};Server=$server;Database=$database;", $user, $password);
								
								
								//$this->conexion = new PDO($cnninfo, $this->usuario, $this->contrasena);
								//$this->conexion = new PDO ("dblib:host=".$this->servidor.":1433;dbname=".$this->basedatos, "$this->usuario", "$this->contrasena");
								$this->conexion = new PDO("odbc:Driver={FreeTDS};Server=$this->servidor;Database=$this->basedatos;", $this->usuario, $this->contrasena);
                                echo "Connected to ".$this->basedatos." at ".$this->servidor." successfully.";
                            } catch (PDOException $pe) {
                                die("Could not connect to the database $this->basedatos : " . $pe->getMessage());
                            }
			// }catch (Exception $e) 
                        // {
	                //   $this->conexion = 'Error de conexión' ;
      	                //    echo "ERROR: ". $e->getMessage();
	                // }	   
                }
		
		function query($sql) {

                        // Comprueba si fue posible seleccionar BDatos en conexion

                            // Returns a MS SQL result resource on success, 
                            // TRUE if no rows were returned, or FALSE on error.
                            if ( $this->conexion) { 
         		    $this->result  = sqlsrv_query( $this->conexion, $sql );
                            }
                            //Returns the number of rows, as an integer.
	         	    //$this->num_rows  = sqlsrv_num_rows($this->result);
			return $this->result;
		}


		public function fetch_row() {
			if ($this->num_rows >0) {
                           //Returns an array that corresponds to the fetched row, or FALSE if there are no more rows.
                           return(sqlsrv_fetch($this->result));
			}
		}

		// public function result($row, $campo) {
                //      //returns the contents of one cell from a MS SQL result set
                //     return(mssql_result($this->result, $row, $campo));
		// }

                // function cerrar() {
		//   sqlsrv_close ( $this->conexion );
                // }
	
	}
?>
